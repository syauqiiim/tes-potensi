<?php

namespace App\Filters;

use App\Models\SesiTesModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Menolak penyimpanan jawaban jika sesi tidak berjalan
 * atau waktu sudah lewat dari waktu_selesai + toleransi.
 */
class CekWaktuTes implements FilterInterface
{
    private const TOLERANSI_DETIK = 5;

    public function before(RequestInterface $request, $arguments = null)
    {
        $pesertaId = session()->get('peserta_id');
        $sesi      = $pesertaId ? (new SesiTesModel())->where('peserta_id', $pesertaId)->first() : null;

        if (
            ! $sesi
            || $sesi['status'] !== 'berjalan'
            || time() > strtotime($sesi['waktu_selesai']) + self::TOLERANSI_DETIK
        ) {
            return service('response')
                ->setStatusCode(403)
                ->setJSON(['status' => 'habis', 'message' => 'Waktu tes sudah berakhir.']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
