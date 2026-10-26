<?php

namespace App\Models;

use CodeIgniter\Model;

class SesiTesModel extends Model
{
    protected $table         = 'sesi_tes';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'peserta_id', 'waktu_mulai', 'waktu_selesai', 'waktu_kirim',
        'status', 'jumlah_soal', 'jumlah_benar', 'skor',
    ];

    /**
     * Kunci sesi dan hitung skor. Aman dipanggil berulang kali:
     * hanya sesi berstatus 'berjalan' yang diubah.
     */
    public function selesaikan(int $sesiId): void
    {
        $r = $this->db->query(
            'SELECT COUNT(*) AS total, COALESCE(SUM(j.pilihan = s.kunci), 0) AS benar
               FROM jawaban j
               JOIN soal s ON s.id = j.soal_id
              WHERE j.sesi_id = ?',
            [$sesiId]
        )->getRowArray();

        $total = (int) ($r['total'] ?? 0);
        $benar = (int) ($r['benar'] ?? 0);
        $skor  = $total > 0 ? round($benar / $total * 100, 2) : 0;

        $this->builder()
            ->where('id', $sesiId)
            ->where('status', 'berjalan')
            ->update([
                'status'       => 'selesai',
                'skor'         => $skor,
                'jumlah_benar' => $benar,
                'waktu_kirim'  => date('Y-m-d H:i:s'),
            ]);
    }

    /** Selesaikan semua sesi yang waktunya sudah habis (peserta menutup browser). */
    public function selesaikanKadaluarsa(): void
    {
        $ids = $this->builder()
            ->select('id')
            ->where('status', 'berjalan')
            ->where('waktu_selesai <', date('Y-m-d H:i:s'))
            ->get()->getResultArray();

        foreach ($ids as $row) {
            $this->selesaikan((int) $row['id']);
        }
    }
}
