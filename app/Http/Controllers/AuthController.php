<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses pengecekan login sederhana
    public function login(Request $request)
    {
        $username = $request->input('email'); // Menggunakan input 'email' dari form sebelumnya
        $password = $request->input('password');

        // Validasi hardcode sementara sesuai permintaan
        if ($username === 'admin' && $password === 'admin') {
            // Simpan status login di session
            session(['admin_logged_in' => true]);
            
            // Redirect ke dashboard admin
            return redirect('/admin/dashboard');
        }

        return back()->withErrors(['error' => 'Username atau password salah!']);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->session()->forget('admin_logged_in');
        return redirect('/login');
    }
}