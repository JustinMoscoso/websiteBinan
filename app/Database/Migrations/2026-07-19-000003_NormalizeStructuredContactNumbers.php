<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NormalizeStructuredContactNumbers extends Migration
{
    public function up()
    {
        foreach (['department_content', 'barangay_content'] as $table) {
            $rows = $this->db->table($table)
                ->select('ID, phone_number, landline')
                ->get()
                ->getResultArray();

            foreach ($rows as $row) {
                $phone = trim((string) ($row['phone_number'] ?? ''));
                $landline = trim((string) ($row['landline'] ?? ''));
                $update = [];

                $normalizedMobile = $this->normalizeMobile($phone);
                if ($normalizedMobile !== null) {
                    $update['phone_number'] = $normalizedMobile;
                } else {
                    $phoneAsLandline = $this->normalizeLandline($phone);
                    if ($phoneAsLandline !== null && $landline === '') {
                        $update['phone_number'] = null;
                        $update['landline'] = $phoneAsLandline;
                        $landline = $phoneAsLandline;
                    }
                }

                $normalizedLandline = $this->normalizeLandline($landline);
                if ($normalizedLandline !== null) {
                    $update['landline'] = $normalizedLandline;
                }

                if ($update !== []) {
                    $this->db->table($table)->where('ID', $row['ID'])->update($update);
                }
            }
        }
    }

    public function down()
    {
        // Formatting is intentionally retained; the legacy contact HTML remains unchanged.
    }

    private function normalizeMobile(string $value): ?string
    {
        $digits = preg_replace('/\D+/', '', $value) ?? '';
        if (str_starts_with($digits, '63')) {
            $digits = substr($digits, 2);
        } elseif (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        if (!preg_match('/^9\d{9}$/', $digits)) {
            return null;
        }

        return '+63 ' . substr($digits, 0, 3) . ' ' . substr($digits, 3, 3) . ' ' . substr($digits, 6, 4);
    }

    private function normalizeLandline(string $value): ?string
    {
        $digits = preg_replace('/\D+/', '', $value) ?? '';
        if (str_starts_with($digits, '63')) {
            $digits = substr($digits, 2);
        }

        if (preg_match('/^049(\d{7})$/', $digits, $matches)) {
            return '(049) ' . substr($matches[1], 0, 3) . '-' . substr($matches[1], 3, 4);
        }

        if (preg_match('/^02(\d{8})$/', $digits, $matches)) {
            return '(02) ' . substr($matches[1], 0, 4) . '-' . substr($matches[1], 4, 4);
        }

        if (preg_match('/^\d{7}$/', $digits)) {
            return '(049) ' . substr($digits, 0, 3) . '-' . substr($digits, 3, 4);
        }

        return null;
    }
}
