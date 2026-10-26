<?php

namespace App\Models;

use CodeIgniter\Model;

class SoalModel extends Model
{
    protected $table         = 'soal';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['nomor_urut', 'teks_soal', 'opsi_a', 'opsi_b', 'opsi_c', 'kunci', 'aktif', 'kategori'];
}
