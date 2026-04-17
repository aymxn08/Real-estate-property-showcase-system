<?php
 
namespace App\Database\Migrations;
 
use CodeIgniter\Database\Migration;
 
class AddIsDeletedToCompanies extends Migration
{
    public function up()
    {
        $fields = [
            'is_deleted' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'password_hash'
            ],
        ];
        $this->forge->addColumn('companies', $fields);
    }
 
    public function down()
    {
        $this->forge->dropColumn('companies', 'is_deleted');
    }
}
