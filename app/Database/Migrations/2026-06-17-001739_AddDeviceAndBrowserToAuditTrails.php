<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeviceAndBrowserToAuditTrails extends Migration
{
    public function up()
    {
        $fields = [
            'device' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'processDetails',
            ],
            'browser' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'device',
            ],
        ];

        $this->forge->addColumn('audit_trails', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('audit_trails', ['device', 'browser']);
    }
}
