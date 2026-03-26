<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'email'         => 'admin@harxatech.com',
            'password_hash' => password_hash('harxatech123', PASSWORD_BCRYPT),
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        $this->db->table('super_admins')->insert($data);
    }
}
