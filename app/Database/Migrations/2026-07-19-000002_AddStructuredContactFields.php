<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStructuredContactFields extends Migration
{
    private const TABLES = ['department_content', 'barangay_content'];

    public function up()
    {
        foreach (self::TABLES as $table) {
            $fields = [
                'phone_number' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                    'after' => 'contact',
                ],
                'landline' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                    'after' => 'phone_number',
                ],
                'email_address' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'landline',
                ],
                'office_address' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'email_address',
                ],
            ];

            foreach ($fields as $name => $definition) {
                if (! $this->db->fieldExists($name, $table)) {
                    $this->forge->addColumn($table, [$name => $definition]);
                }
            }

            $this->migrateLegacyContacts($table);
        }
    }

    public function down()
    {
        foreach (self::TABLES as $table) {
            foreach (['office_address', 'email_address', 'landline', 'phone_number'] as $field) {
                if ($this->db->fieldExists($field, $table)) {
                    $this->forge->dropColumn($table, $field);
                }
            }
        }
    }

    private function migrateLegacyContacts(string $table): void
    {
        $rows = $this->db->table($table)
            ->select('ID, contact')
            ->where('contact IS NOT NULL', null, false)
            ->get()
            ->getResultArray();

        foreach ($rows as $row) {
            $contactFields = $this->extractContactFields((string) $row['contact']);
            if ($contactFields !== []) {
                $this->db->table($table)->where('ID', $row['ID'])->update($contactFields);
            }
        }
    }

    private function extractContactFields(string $html): array
    {
        $text = preg_replace('/<(?:br\s*\/?>|\/p|\/li|\/div)>/i', "\n", $html);
        $text = html_entity_decode(strip_tags((string) $text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $lines = array_values(array_filter(array_map(
            static fn (string $line): string => trim((string) preg_replace('/\s+/', ' ', $line)),
            preg_split('/\R+/', $text) ?: []
        ), static fn (string $line): bool => $line !== ''));

        $result = [];
        $fieldMap = [
            'phone' => 'phone_number',
            'phone number' => 'phone_number',
            'mobile' => 'phone_number',
            'mobile number' => 'phone_number',
            'landline' => 'landline',
            'email' => 'email_address',
            'email address' => 'email_address',
            'address' => 'office_address',
            'office address' => 'office_address',
        ];

        foreach ($lines as $index => $line) {
            if (!preg_match('/^(phone(?:\s+number)?|mobile(?:\s+number)?|landline|email(?:\s+address)?|office\s+address|address)\s*:?\s*(.*)$/i', $line, $matches)) {
                continue;
            }

            $label = strtolower(trim($matches[1]));
            $field = $fieldMap[$label] ?? null;
            if (!$field || isset($result[$field])) {
                continue;
            }

            $value = trim($matches[2]);
            if ($value === '' && isset($lines[$index + 1])) {
                $value = $lines[$index + 1];
            }

            if (in_array($field, ['phone_number', 'landline'], true)) {
                if (preg_match('/(?:\+?\d[\d\s()\-]{3,}\d|\(\d[\d\s()\-]{3,}\d|\d{5,})/', $value, $number)) {
                    $value = trim($number[0]);
                } else {
                    continue;
                }
            } elseif ($field === 'email_address') {
                if (!preg_match('/[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}/i', $value, $email)) {
                    continue;
                }
                $value = $email[0];
            }

            if ($value !== '') {
                $result[$field] = $value;
            }
        }

        return $result;
    }
}
