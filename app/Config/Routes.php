<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ---------- Peserta ----------
$routes->get('/', 'Home::index');
$routes->post('identitas', 'Home::identitas');

$routes->get('instruksi', 'Tes::instruksi');
$routes->post('tes/mulai', 'Tes::mulai');
$routes->get('tes/soal/(:num)', 'Tes::soal/$1');
$routes->post('tes/simpan-jawaban', 'Tes::simpanJawaban', ['filter' => 'cekwaktu']);
$routes->get('tes/sisa-waktu', 'Tes::sisaWaktu');
$routes->post('tes/selesai', 'Tes::selesai');
$routes->get('tes/hasil', 'Tes::hasil');

// ---------- Auth admin ----------
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout');

// ---------- Admin ----------
$routes->group('admin', ['filter' => 'adminauth'], static function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');

    $routes->get('soal', 'Admin\Soal::index');
    $routes->get('soal/tambah', 'Admin\Soal::tambah');
    $routes->post('soal/tambah', 'Admin\Soal::tambah');
    $routes->get('soal/edit/(:num)', 'Admin\Soal::edit/$1');
    $routes->post('soal/edit/(:num)', 'Admin\Soal::edit/$1');
    $routes->post('soal/toggle/(:num)', 'Admin\Soal::toggle/$1');

    $routes->get('hasil', 'Admin\Hasil::index');
    $routes->get('hasil/ekspor', 'Admin\Hasil::ekspor');

    $routes->get('pengaturan', 'Admin\Pengaturan::index');
    $routes->post('pengaturan', 'Admin\Pengaturan::index');
});
