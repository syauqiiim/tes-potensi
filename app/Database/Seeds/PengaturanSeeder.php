<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('pengaturan')->insert([
            'durasi_menit' => 30,
            'jumlah_soal'  => 50,
            'acak_soal'    => 1,
            'tes_dibuka'   => 1,
            'tampil_skor'  => 1,
        ]);
    }
}
