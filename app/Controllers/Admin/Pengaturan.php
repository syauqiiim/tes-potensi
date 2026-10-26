<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\PengaturanModel;

class Pengaturan extends BaseController
{
    public function index()
    {
        $model = new PengaturanModel();

        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = [
                'durasi_menit' => 'required|is_natural_no_zero|less_than_equal_to[600]',
                'jumlah_soal'  => 'required|is_natural_no_zero|less_than_equal_to[500]',
                'password_baru' => 'permit_empty|min_length[8]|max_length[72]',
            ];

            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', implode(' • ', $this->validator->getErrors()));
            }

            $data = [
                'durasi_menit' => (int) $this->request->getPost('durasi_menit'),
                'jumlah_soal'  => (int) $this->request->getPost('jumlah_soal'),
                'acak_soal'    => $this->request->getPost('acak_soal') ? 1 : 0,
                'tes_dibuka'   => $this->request->getPost('tes_dibuka') ? 1 : 0,
                'tampil_skor'  => $this->request->getPost('tampil_skor') ? 1 : 0,
            ];

            $set = $model->ambil();
            if ((int) $set['id'] > 0) {
                $model->update((int) $set['id'], $data);
            } else {
                $model->insert($data);
            }

            $passwordBaru = (string) $this->request->getPost('password_baru');
            if ($passwordBaru !== '') {
                (new AdminModel())->update((int) session()->get('admin_id'), [
                    'password' => password_hash($passwordBaru, PASSWORD_DEFAULT),
                ]);
            }

            return redirect()->to(base_url('admin/pengaturan'))->with('success', 'Pengaturan disimpan.');
        }

        return view('admin/pengaturan', ['title' => 'Pengaturan', 'set' => $model->ambil()]);
    }
}
