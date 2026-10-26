<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PengaturanModel;
use App\Models\SoalModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Soal extends BaseController
{
    private array $rules = [
        'teks_soal'  => 'required|min_length[3]',
        'opsi_a'     => 'required|max_length[255]',
        'opsi_b'     => 'required|max_length[255]',
        'opsi_c'     => 'required|max_length[255]',
        'kunci'      => 'required|in_list[A,B,C]',
        'nomor_urut' => 'permit_empty|is_natural_no_zero',
        'kategori'   => 'permit_empty|max_length[50]',
    ];

    private function dataForm(): array
    {
        $nomor = $this->request->getPost('nomor_urut');

        return [
            'teks_soal'  => trim((string) $this->request->getPost('teks_soal')),
            'opsi_a'     => trim((string) $this->request->getPost('opsi_a')),
            'opsi_b'     => trim((string) $this->request->getPost('opsi_b')),
            'opsi_c'     => trim((string) $this->request->getPost('opsi_c')),
            'kunci'      => (string) $this->request->getPost('kunci'),
            'nomor_urut' => ($nomor === null || $nomor === '') ? null : (int) $nomor,
            'kategori'   => trim((string) $this->request->getPost('kategori')) ?: null,
            'aktif'      => $this->request->getPost('aktif') ? 1 : 0,
        ];
    }

    public function index()
    {
        $model = new SoalModel();
        $set   = (new PengaturanModel())->ambil();

        return view('admin/soal_list', [
            'title'     => 'Bank Soal',
            'daftar'    => $model->orderBy('COALESCE(nomor_urut, 999999)', 'ASC', false)->orderBy('id', 'ASC')->findAll(),
            'soalAktif' => (new SoalModel())->where('aktif', 1)->countAllResults(),
            'set'       => $set,
        ]);
    }

    public function tambah()
    {
        if (strtolower($this->request->getMethod()) === 'post') {
            if (! $this->validate($this->rules)) {
                return redirect()->back()->withInput()->with('error', implode(' • ', $this->validator->getErrors()));
            }

            (new SoalModel())->insert($this->dataForm());

            return redirect()->to(base_url('admin/soal'))->with('success', 'Soal berhasil disimpan.');
        }

        return view('admin/soal_form', ['title' => 'Tambah Soal', 'soal' => null, 'aksi' => base_url('admin/soal/tambah')]);
    }

    public function edit($id)
    {
        $model = new SoalModel();
        $soal  = $model->find((int) $id);
        if (! $soal) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (strtolower($this->request->getMethod()) === 'post') {
            if (! $this->validate($this->rules)) {
                return redirect()->back()->withInput()->with('error', implode(' • ', $this->validator->getErrors()));
            }

            $model->update((int) $id, $this->dataForm());

            return redirect()->to(base_url('admin/soal'))->with('success', 'Soal berhasil diperbarui.');
        }

        return view('admin/soal_form', [
            'title' => 'Edit Soal',
            'soal'  => $soal,
            'aksi'  => base_url('admin/soal/edit/' . (int) $id),
        ]);
    }

    /** Aktifkan / nonaktifkan soal (tidak dihapus agar riwayat jawaban aman). */
    public function toggle($id)
    {
        $model = new SoalModel();
        $soal  = $model->find((int) $id);
        if (! $soal) {
            throw PageNotFoundException::forPageNotFound();
        }

        $model->update((int) $id, ['aktif' => $soal['aktif'] ? 0 : 1]);

        return redirect()->to(base_url('admin/soal'))->with('success', 'Status soal diubah.');
    }
}
