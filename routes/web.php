<?php

use App\Http\Controllers\Intern\AttendanceController;
use App\Http\Controllers\Intern\PermissionController;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

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

    // Attendance history (placeholder or actual if method exists)
    // using dashboard temporarily since index doesn't exist
    Route::get('/attendance', [AttendanceController::class, 'dashboard'])->name('attendance.index');
});