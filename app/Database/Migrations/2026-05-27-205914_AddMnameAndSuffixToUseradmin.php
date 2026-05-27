<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMnameAndSuffixToUseradmin extends Migration
{
    public function up()
    {
        $fields = [
            'mname' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'fname',
            ],
            'suffix' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'lname',
            ],
        ];

        $this->forge->addColumn('useradmin', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('useradmin', ['mname', 'suffix']);
    }
}
