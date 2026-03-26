<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProjectFieldValue extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'project_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'project_type_field_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('project_id', 'projects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('project_type_field_id', 'project_type_fields', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('project_field_values');
    }

    public function down()
    {
        $this->forge->dropTable('project_field_values');
    }
}
