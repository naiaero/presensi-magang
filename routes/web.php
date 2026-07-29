<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Intern\AttendanceController;
use App\Http\Controllers\Intern\PermissionController;
use App\Http\Controllers\Auth\AuthController;

// Root redirect
Route::get('/', function () {
    return redirect()->route('login');
});

// INTERN AUTH (Laravel Auth)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// INTERN ROUTES
Route::middleware(['auth'])->prefix('intern')->name('intern.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AttendanceController::class, 'dashboard'])->name('dashboard');

    // Presensi
    Route::get('/attendance/scan', [AttendanceController::class, 'scanView'])->name('attendance.scan');
    Route::post('/attendance/in', [AttendanceController::class, 'storeIn'])->name('attendance.store_in');
    Route::post('/attendance/out', [AttendanceController::class, 'storeOut'])->name('attendance.store_out');

    // Izin
    Route::get('/permission/create', [PermissionController::class, 'create'])->name('permission.create');
    Route::post('/permission/store', [PermissionController::class, 'store'])->name('permission.store');

    // Profil
    Route::get('/profile', [\App\Http\Controllers\Intern\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update-password', [\App\Http\Controllers\Intern\ProfileController::class, 'updatePassword'])->name('profile.update_password');

    // Attendance history
    Route::get('/attendance', [AttendanceController::class, 'dashboard'])->name('attendance.index');
});

// ADMIN AUTH & ROUTES (Session-based from Sagos)
Route::prefix('admin')->group(function () {
    
    Route::get('/login', function () {
        return view('admin.login');
    })->name('admin.login');

    Route::post('/login', function (Request $request) {
        $username = $request->input('email');
        $password = $request->input('password');

        $storedUsername = session('admin_username', 'admin');
        $storedPassword = session('admin_password', 'admin');
        $adminActive = session('admin_active', true);

        if (!$adminActive) {
            return back()->withErrors(['error' => 'Akun admin dinonaktifkan. Hubungi administrator untuk mengaktifkan kembali.']);
        }

        if ($username === $storedUsername && $password === $storedPassword) {
            session(['admin_logged_in' => true]);
            return redirect('/admin/dashboard');
        }

        return back()->withErrors(['error' => 'Username atau password salah!']);
    });

    Route::get('/logout', function (Request $request) {
        $request->session()->forget('admin_logged_in');
        return redirect('/admin/login');
    });

    Route::get('/settings', function () {
        if (!session('admin_logged_in')) {
            return redirect('/admin/login');
        }

        $currentUsername = session('admin_username', 'admin');
        $adminActive = session('admin_active', true);
        return view('admin.settings', compact('currentUsername', 'adminActive'));
    });

    Route::post('/settings', function (Request $request) {
        if (!session('admin_logged_in')) {
            return redirect('/admin/login');
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

    Route::get('/dashboard', function () {
        if (!session('admin_logged_in')) {
            return redirect('/admin/login');
        }
        return view('admin.dashboard');
    });
});