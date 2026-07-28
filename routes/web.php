<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Route Halaman Login (Form)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Route Proses Pengecekan Login Sederhana
Route::post('/login', function (Request $request) {
    $username = $request->input('email'); // Mengambil input dari form
    $password = $request->input('password');

    $storedUsername = session('admin_username', 'admin');
    $storedPassword = session('admin_password', 'admin');
    $adminActive = session('admin_active', true);

    // Cek apakah akun admin aktif
    if (!$adminActive) {
        return back()->withErrors(['error' => 'Akun admin dinonaktifkan. Hubungi administrator untuk mengaktifkan kembali.']);
    }

    // Validasi username/password dari session atau default
    if ($username === $storedUsername && $password === $storedPassword) {
        session(['admin_logged_in' => true]);
        return redirect('/admin/dashboard');
    }

    return back()->withErrors(['error' => 'Username atau password salah!']);
});

// Route Logout
Route::get('/logout', function (Request $request) {
    $request->session()->forget('admin_logged_in');
    return redirect('/login');
});

// Route Pengaturan Admin
Route::get('/admin/settings', function () {
    if (!session('admin_logged_in')) {
        return redirect('/login');
    }

    $currentUsername = session('admin_username', 'admin');
    $adminActive = session('admin_active', true);
    return view('admin.settings', compact('currentUsername', 'adminActive'));
});

Route::post('/admin/settings', function (Request $request) {
    if (!session('admin_logged_in')) {
        return redirect('/login');
    }

    $request->validate([
        'username' => 'required|string|max:255',
        'password' => 'nullable|string|min:3',
        'active' => 'nullable|boolean',
    ]);

    session(['admin_username' => $request->input('username')]);
    if ($request->filled('password')) {
        session(['admin_password' => $request->input('password')]);
    }
    session(['admin_active' => $request->boolean('active')]);

    return back()->with('success', 'Pengaturan akun berhasil diperbarui.');
});

// Route untuk Dashboard Admin (Dilindungi pengaman session)
Route::get('/admin/dashboard', function () {
    if (!session('admin_logged_in')) {
        return redirect('/login');
    }
    return view('admin.dashboard');
});