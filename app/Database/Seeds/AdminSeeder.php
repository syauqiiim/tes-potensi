<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // GANTI password ini setelah login pertama (menu Pengaturan).
        $this->db->table('admin')->insert([
            'username' => 'admin',
            'password' => password_hash('admin12345', PASSWORD_DEFAULT),
        ]);
    }
}
