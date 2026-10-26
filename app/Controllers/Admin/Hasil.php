<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SesiTesModel;

class Hasil extends BaseController
{
    private function ambilData(): array
    {
        // Sesi yang waktunya habis (browser ditutup) dinilai dulu.
        (new SesiTesModel())->selesaikanKadaluarsa();

        return \Config\Database::connect()->table('sesi_tes s')
            ->select('p.nama, p.kode, s.waktu_mulai, s.waktu_kirim, s.status, s.jumlah_soal, s.jumlah_benar, s.skor')
            ->join('peserta p', 'p.id = s.peserta_id')
            ->orderBy('s.id', 'DESC')
            ->get()->getResultArray();
    }

    public function index()
    {
        return view('admin/hasil_list', ['title' => 'Hasil Peserta', 'daftar' => $this->ambilData()]);
    }

    public function ekspor()
    {
        $fh = fopen('php://temp', 'r+');
        fwrite($fh, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8
        fputcsv($fh, ['Nama', 'Email/Kode', 'Waktu Mulai', 'Waktu Kirim', 'Status', 'Jumlah Soal', 'Benar', 'Skor']);

        foreach ($this->ambilData() as $r) {
            fputcsv($fh, [
                $this->aman($r['nama']),
                $this->aman($r['kode']),
                $r['waktu_mulai'],
                $r['waktu_kirim'],
                $r['status'],
                $r['jumlah_soal'],
                $r['jumlah_benar'],
                $r['skor'],
            ]);
        }

        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="hasil-tes-' . date('Ymd-His') . '.csv"')
            ->setBody($csv);
    }

    /** Cegah CSV/formula injection saat file dibuka di Excel. */
    private function aman($nilai): string
    {
        $nilai = (string) $nilai;

        return ($nilai !== '' && in_array($nilai[0], ['=', '+', '-', '@', "\t", "\r"], true)) ? "'" . $nilai : $nilai;
    }
}
