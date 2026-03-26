<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateProjectsTable extends Migration
{
    public function up()
    {
        $fields = [
            'latitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,8',
                'null'       => true,
                'after'      => 'location',
            ],
            'longitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => true,
                'after'      => 'latitude',
            ],
            'price_start' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => true,
                'after'      => 'longitude',
            ],
            'price_end' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => true,
                'after'      => 'price_start',
            ],
        ];
        $this->forge->addColumn('projects', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('projects', ['latitude', 'longitude', 'price_start', 'price_end']);
    }
}
