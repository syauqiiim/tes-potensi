<?php

namespace App\Controllers;

use App\Models\PengaturanModel;
use App\Models\PesertaModel;

class Home extends BaseController
{
    public function index()
    {
        return view('beranda', [
            'title' => 'Tes Potensi',
            'set'   => (new PengaturanModel())->ambil(),
        ]);
    }

    public function identitas()
    {
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'kode' => 'required|min_length[3]|max_length[100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', 'Nama (min. 3 huruf) dan email / kode peserta (min. 3 karakter) wajib diisi.');
        }

        $nama = trim((string) $this->request->getPost('nama'));
        $kode = mb_strtolower(trim((string) $this->request->getPost('kode')));

        $model   = new PesertaModel();
        $peserta = $model->where('kode', $kode)->first();

        if ($peserta) {
            $pesertaId = (int) $peserta['id'];
        } else {
            $pesertaId = (int) $model->insert(['nama' => $nama, 'kode' => $kode], true);
        }

        session()->regenerate();
        session()->set(['peserta_id' => $pesertaId]);

        return redirect()->to(base_url('instruksi'));
    }
}
