<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOrgChartImgToBarangayContent extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('org_chart_img', 'barangay_content')) {
            $this->forge->addColumn('barangay_content', [
                'org_chart_img' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'img_logo',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('org_chart_img', 'barangay_content')) {
            $this->forge->dropColumn('barangay_content', 'org_chart_img');
        }
    }
}
