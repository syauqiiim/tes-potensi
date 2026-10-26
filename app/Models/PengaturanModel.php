<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaturanModel extends Model
{
    protected $table         = 'pengaturan';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['durasi_menit', 'jumlah_soal', 'acak_soal', 'tes_dibuka', 'tampil_skor'];

    /** Ambil satu baris pengaturan (dengan nilai bawaan jika tabel kosong). */
    public function ambil(): array
    {
        $row = $this->orderBy('id', 'ASC')->first();

        return $row ?: [
            'id'           => 0,
            'durasi_menit' => 30,
            'jumlah_soal'  => 50,
            'acak_soal'    => 1,
            'tes_dibuka'   => 1,
            'tampil_skor'  => 1,
        ];
    }
}
