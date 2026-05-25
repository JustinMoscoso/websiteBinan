<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfileImageToUserAdmin extends Migration
{
    public function up()
    {
        $fields = [
            'profile_image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'email',
            ],
        ];

        $this->forge->addColumn('useradmin', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('useradmin', 'profile_image');
    }
}
