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

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

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
    Route::get('/attendance', [AttendanceController::class, 'calendar'])->name('attendance.index');
});

use App\Http\Controllers\Admin\AdminController;

// ADMIN AUTH & ROUTES (Session-based from Sagos)
Route::prefix('admin')->middleware(['auth'])->group(function () {
    
    Route::middleware(\App\Http\Middleware\CheckAdminRole::class)->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/user/store', [AdminController::class, 'storeUser'])->name('admin.user.store');
        Route::post('/user/{id}/update', [AdminController::class, 'updateUser'])->name('admin.user.update');
        Route::post('/user/{id}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.user.reset_password');
        Route::delete('/user/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.delete');
        Route::post('/permission/{id}', [AdminController::class, 'updatePermission'])->name('admin.permission.update');
        Route::get('/user/{id}/calendar', [AdminController::class, 'getUserCalendar'])->name('admin.user.calendar');
    });
});