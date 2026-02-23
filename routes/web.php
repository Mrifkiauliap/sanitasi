<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Pengaturan\ManajemenUserController;
use App\Http\Controllers\ManajemenData\WilayahController;
use App\Http\Controllers\ManajemenData\SanitasiController;
use App\Http\Controllers\PenyaluranAirController;
use App\Http\Controllers\LaporanKondisiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chart-data');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pengaturan
    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::resource('manajemen-user', ManajemenUserController::class);
        Route::put('/manajemen-user/{manajemen_user}/status', [ManajemenUserController::class, 'updateStatus'])->name('manajemen-user.status');
    });

    // Manajemen Data
    Route::prefix('manajemen-data')->name('manajemen-data.')->group(function () {
        Route::resource('wilayah', WilayahController::class);
        Route::resource('sanitasi', SanitasiController::class);
    });

    Route::resource('penyaluran-air', PenyaluranAirController::class);
    Route::patch('penyaluran-air/{penyaluranAir}/status', [PenyaluranAirController::class, 'updateStatus'])->name('penyaluran-air.status');


    // Laporan Kondisi (export harus di atas resource)
    Route::get('laporan-kondisi/export', [LaporanKondisiController::class, 'export'])->name('laporan-kondisi.export');
    Route::resource('laporan-kondisi', LaporanKondisiController::class);
});

require __DIR__.'/auth.php';
