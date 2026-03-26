<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProjectUnits extends Migration
{
    public function up()
    {
        // Table: project_units
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'company_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'project_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'unit_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'bedrooms' => [
                'type'       => 'INT',
                'constraint' => 5,
                'null'       => true,
            ],
            'bathrooms' => [
                'type'       => 'INT',
                'constraint' => 5,
                'null'       => true,
            ],
            'area' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Available', 'Sold', 'Booked'],
                'default'    => 'Available',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('company_id', 'companies', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('project_id', 'projects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('project_units');

        // Table: unit_images
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'unit_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'image_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('unit_id', 'project_units', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('unit_images');
    }

    public function down()
    {
        $this->forge->dropTable('unit_images');
        $this->forge->dropTable('project_units');
    }
}
