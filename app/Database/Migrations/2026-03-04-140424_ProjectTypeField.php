<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProjectTypeField extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'project_type_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'field_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'field_type' => [
                'type'       => 'ENUM',
                'constraint' => ['Text', 'Number', 'Dropdown', 'Checkbox'],
                'default'    => 'Text',
            ],
            'is_mandatory' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'options_json' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('project_type_id', 'project_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('project_type_fields');
    }

    public function down()
    {
        $this->forge->dropTable('project_type_fields');
    }
}
