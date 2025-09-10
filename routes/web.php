<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\PenggajianController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('login/otp', [AuthController::class, 'showOtpForm'])->name('otp.form');
    Route::post('login/otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('karyawan', KaryawanController::class);

    Route::get('karyawan/{karyawan}/proyek/create', [ProyekController::class, 'create'])->name('proyek.create');
    Route::post('karyawan/{karyawan}/proyek', [ProyekController::class, 'store'])->name('proyek.store');
    
    // Rute baru untuk pembatalan
    Route::post('proyek/{proyek}/batalkan', [ProyekController::class, 'batalkanPembayaran'])->name('proyek.batalkan');
    Route::delete('proyek/{proyek}', [ProyekController::class, 'destroy'])->name('proyek.destroy');

    Route::get('penggajian', [PenggajianController::class, 'index'])->name('penggajian.index');
    Route::post('penggajian/cetak', [PenggajianController::class, 'cetakSlip'])->name('penggajian.cetak');
    Route::get('penggajian/export', [PenggajianController::class, 'exportExcel'])->name('penggajian.export');
});