<?php

namespace App\Controllers;

use App\Models\AdminModel;

class Auth extends BaseController
{
    private const MAKS_PERCOBAAN = 5;
    private const BLOKIR_DETIK   = 300;

    public function login()
    {
        if (session()->get('admin_id')) {
            return redirect()->to(base_url('admin'));
        }

        return view('auth/login', ['title' => 'Login Admin']);
    }

    public function attempt()
    {
        $kunciCache = 'login_' . md5((string) $this->request->getIPAddress());
        $percobaan  = (int) cache($kunciCache);

        if ($percobaan >= self::MAKS_PERCOBAAN) {
            return redirect()->back()->with('error', 'Terlalu banyak percobaan. Coba lagi dalam 5 menit.');
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $admin = (new AdminModel())->where('username', $username)->first();

        if (! $admin || ! password_verify($password, $admin['password'])) {
            cache()->save($kunciCache, $percobaan + 1, self::BLOKIR_DETIK);
            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

        cache()->delete($kunciCache);
        session()->regenerate();
        session()->set(['admin_id' => (int) $admin['id'], 'admin_nama' => $admin['username']]);

        return redirect()->to(base_url('admin'));
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(base_url('login'));
    }
}
