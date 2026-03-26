<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSlugToProjects extends Migration
{
    public function up()
    {
        $fields = [
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
                'after'      => 'project_name',
            ],
        ];
        $this->forge->addColumn('projects', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('projects', 'slug');
    }
}
