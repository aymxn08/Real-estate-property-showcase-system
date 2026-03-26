<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameLocationToAddress extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('projects', [
            'location' => [
                'name' => 'address',
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('projects', [
            'address' => [
                'name' => 'location',
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
        ]);
    }
}
