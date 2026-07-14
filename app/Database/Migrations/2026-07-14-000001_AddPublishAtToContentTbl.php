<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPublishAtToContentTbl extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('publish_at', 'content_tbl')) {
            $this->forge->addColumn('content_tbl', [
                'publish_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'category',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('publish_at', 'content_tbl')) {
            $this->forge->dropColumn('content_tbl', 'publish_at');
        }
    }
}
