<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PengaturanModel;
use App\Models\PesertaModel;
use App\Models\SesiTesModel;
use App\Models\SoalModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $sesi = new SesiTesModel();
        $sesi->selesaikanKadaluarsa();

        $set = (new PengaturanModel())->ambil();

        return view('admin/dashboard', [
            'title'         => 'Dashboard',
            'set'           => $set,
            'soalAktif'     => (new SoalModel())->where('aktif', 1)->countAllResults(),
            'soalTotal'     => (new SoalModel())->countAllResults(),
            'pesertaTotal'  => (new PesertaModel())->countAllResults(),
            'sesiBerjalan'  => (new SesiTesModel())->where('status', 'berjalan')->countAllResults(),
            'sesiSelesai'   => (new SesiTesModel())->where('status', 'selesai')->countAllResults(),
        ]);
    }
}
