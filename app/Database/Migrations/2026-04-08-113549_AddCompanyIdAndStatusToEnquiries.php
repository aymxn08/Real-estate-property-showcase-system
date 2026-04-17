<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCompanyIdAndStatusToEnquiries extends Migration
{
    public function up()
    {
        $fields = [
            'company_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Allow null initially to not break existing data
                'after'      => 'project_id',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['New', 'Read', 'Contacted'],
                'default'    => 'New',
                'after'      => 'message',
            ],
        ];

        $this->forge->addColumn('enquiries', $fields);
        $this->forge->addForeignKey('company_id', 'companies', 'id', 'CASCADE', 'CASCADE', 'enquiries');
        $this->forge->processIndexes('enquiries');
    }

    public function down()
    {
        $this->forge->dropForeignKey('enquiries', 'enquiries_company_id_foreign');
        $this->forge->dropColumn('enquiries', ['company_id', 'status']);
    }
}
