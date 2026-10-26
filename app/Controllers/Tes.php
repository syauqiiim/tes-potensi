<?php

namespace App\Controllers;

use App\Models\JawabanModel;
use App\Models\PengaturanModel;
use App\Models\PesertaModel;
use App\Models\SesiTesModel;
use App\Models\SoalModel;

class Tes extends BaseController
{
    /**
     * Ambil sesi milik peserta yang sedang login.
     * Jika waktunya sudah habis, sesi langsung dikunci dan dinilai.
     */
    private function sesiPeserta(): ?array
    {
        $pesertaId = session()->get('peserta_id');
        if (! $pesertaId) {
            return null;
        }

        $model = new SesiTesModel();
        $sesi  = $model->where('peserta_id', $pesertaId)->first();

        if ($sesi && $sesi['status'] === 'berjalan' && time() >= strtotime($sesi['waktu_selesai'])) {
            $model->selesaikan((int) $sesi['id']);
            $sesi = $model->find($sesi['id']);
        }

        return $sesi ?: null;
    }

    private function arahkan(array $sesi)
    {
        return redirect()->to(base_url($sesi['status'] === 'selesai' ? 'tes/hasil' : 'tes/soal/1'));
    }

    public function instruksi()
    {
        if (! session()->get('peserta_id')) {
            return redirect()->to(base_url());
        }

        $sesi = $this->sesiPeserta();
        if ($sesi) {
            return $this->arahkan($sesi);
        }

        $peserta = (new PesertaModel())->find(session()->get('peserta_id'));

        return view('tes/instruksi', [
            'title'   => 'Instruksi Tes',
            'set'     => (new PengaturanModel())->ambil(),
            'peserta' => $peserta,
        ]);
    }

    public function mulai()
    {
        if (! session()->get('peserta_id')) {
            return redirect()->to(base_url());
        }

        // Sudah punya sesi -> lanjutkan, jangan buat baru.
        $sesi = $this->sesiPeserta();
        if ($sesi) {
            return $this->arahkan($sesi);
        }

        $set = (new PengaturanModel())->ambil();
        if (! (int) $set['tes_dibuka']) {
            return redirect()->to(base_url('instruksi'))->with('error', 'Tes belum dibuka oleh admin.');
        }

        $soalModel = new SoalModel();
        $soalModel->select('id')->where('aktif', 1);
        if ((int) $set['acak_soal']) {
            $soalModel->orderBy('RAND()', '', false);
        } else {
            $soalModel->orderBy('COALESCE(nomor_urut, 999999)', 'ASC', false)->orderBy('id', 'ASC');
        }
        $daftar = $soalModel->findAll((int) $set['jumlah_soal']);

        if (count($daftar) === 0) {
            return redirect()->to(base_url('instruksi'))->with('error', 'Belum ada soal aktif. Hubungi admin.');
        }

        $db   = \Config\Database::connect();
        $now  = time();
        $stop = $now + ((int) $set['durasi_menit'] * 60);

        try {
            $db->transStart();

            $sesiId = (int) (new SesiTesModel())->insert([
                'peserta_id'    => session()->get('peserta_id'),
                'waktu_mulai'   => date('Y-m-d H:i:s', $now),
                'waktu_selesai' => date('Y-m-d H:i:s', $stop),
                'status'        => 'berjalan',
                'jumlah_soal'   => count($daftar),
            ], true);

            $baris = [];
            foreach ($daftar as $i => $s) {
                $baris[] = [
                    'sesi_id' => $sesiId,
                    'soal_id' => (int) $s['id'],
                    'urutan'  => $i + 1,
                    'pilihan' => null,
                ];
            }
            (new JawabanModel())->insertBatch($baris);

            $db->transComplete();
        } catch (\Throwable $e) {
            // Kemungkinan klik ganda: sesi sudah dibuat oleh permintaan lain.
            log_message('error', 'Gagal membuat sesi tes: ' . $e->getMessage());
            return redirect()->to(base_url('instruksi'));
        }

        return redirect()->to(base_url('tes/soal/1'));
    }

    public function soal($no = 1)
    {
        $sesi = $this->sesiPeserta();
        if (! $sesi) {
            return redirect()->to(base_url());
        }
        if ($sesi['status'] === 'selesai') {
            return redirect()->to(base_url('tes/hasil'));
        }

        $db = \Config\Database::connect();

        $semua = $db->table('jawaban')
            ->select('urutan, pilihan')
            ->where('sesi_id', $sesi['id'])
            ->orderBy('urutan', 'ASC')
            ->get()->getResultArray();

        $total = count($semua);
        $no    = max(1, min((int) $no, $total));

        // PENTING: kolom "kunci" sengaja tidak diambil agar tidak sampai ke browser.
        $soal = $db->table('jawaban j')
            ->select('j.soal_id, j.pilihan, j.urutan, s.teks_soal, s.opsi_a, s.opsi_b, s.opsi_c')
            ->join('soal s', 's.id = j.soal_id')
            ->where('j.sesi_id', $sesi['id'])
            ->where('j.urutan', $no)
            ->get()->getRowArray();

        if (! $soal) {
            return redirect()->to(base_url('tes/soal/1'));
        }

        return view('tes/soal', [
            'title' => 'Soal ' . $no . ' dari ' . $total,
            'sesi'  => $sesi,
            'soal'  => $soal,
            'semua' => $semua,
            'no'    => $no,
            'total' => $total,
        ]);
    }

    public function simpanJawaban()
    {
        $sesi = $this->sesiPeserta();
        if (! $sesi || $sesi['status'] !== 'berjalan') {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'habis']);
        }

        $data    = $this->request->getJSON(true) ?? [];
        $soalId  = (int) ($data['soal_id'] ?? 0);
        $pilihan = $data['pilihan'] ?? null;

        if ($pilihan === '') {
            $pilihan = null;
        }
        if ($soalId < 1 || ($pilihan !== null && ! in_array($pilihan, ['A', 'B', 'C'], true))) {
            return $this->response->setStatusCode(422)->setJSON(['status' => 'error']);
        }

        \Config\Database::connect()->table('jawaban')
            ->where('sesi_id', $sesi['id'])
            ->where('soal_id', $soalId)
            ->update(['pilihan' => $pilihan]);

        return $this->response->setJSON(['status' => 'ok']);
    }

    public function sisaWaktu()
    {
        $sesi = $this->sesiPeserta();
        if (! $sesi) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'tidak_ada']);
        }

        $selesai = strtotime($sesi['waktu_selesai']);

        return $this->response->setJSON([
            'status'        => $sesi['status'],
            'sisa'          => max(0, $selesai - time()),
            'server_now'    => time() * 1000,
            'waktu_selesai' => $selesai * 1000,
        ]);
    }

    public function selesai()
    {
        $sesi = $this->sesiPeserta();
        if (! $sesi) {
            return redirect()->to(base_url());
        }

        if ($sesi['status'] === 'berjalan') {
            (new SesiTesModel())->selesaikan((int) $sesi['id']);
        }

        return redirect()->to(base_url('tes/hasil'));
    }

    public function hasil()
    {
        $sesi = $this->sesiPeserta();
        if (! $sesi) {
            return redirect()->to(base_url());
        }
        if ($sesi['status'] === 'berjalan') {
            return redirect()->to(base_url('tes/soal/1'));
        }

        return view('tes/hasil', [
            'title'   => 'Hasil Tes',
            'sesi'    => $sesi,
            'peserta' => (new PesertaModel())->find($sesi['peserta_id']),
            'set'     => (new PengaturanModel())->ambil(),
        ]);
    }
}
