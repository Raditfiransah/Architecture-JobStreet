<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LowonganController;
use App\Http\Controllers\Admin\ProyekController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InfoHubController;
use App\Http\Controllers\Admin\LaporanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('dashboard/admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/antrian', [DashboardController::class, 'antrian'])->name('antrian');

    // Moderasi lowongan
    Route::get('/lowongan', [LowonganController::class, 'index'])->name('lowongan.index');
    Route::get('/lowongan/{id}', [LowonganController::class, 'show'])->name('lowongan.show');
    Route::post('/lowongan/{id}/setujui', [LowonganController::class, 'setujui'])->name('lowongan.setujui');
    Route::post('/lowongan/{id}/tolak', [LowonganController::class, 'tolak'])->name('lowongan.tolak');
    Route::post('/lowongan/{id}/tutup', [LowonganController::class, 'tutup'])->name('lowongan.tutup');

    // Moderasi proyek
    Route::get('/proyek', [ProyekController::class, 'index'])->name('proyek.index');
    Route::get('/proyek/{id}', [ProyekController::class, 'show'])->name('proyek.show');
    Route::post('/proyek/{id}/setujui', [ProyekController::class, 'setujui'])->name('proyek.setujui');
    Route::post('/proyek/{id}/tolak', [ProyekController::class, 'tolak'])->name('proyek.tolak');

    // Kelola user
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::post('/users/{id}/verifikasi', [UserController::class, 'verifikasi'])->name('users.verifikasi');
    Route::post('/users/{id}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{id}/aktifkan', [UserController::class, 'aktifkan'])->name('users.aktifkan');

    // Info Hub
    Route::get('/info', [InfoHubController::class, 'index'])->name('info.index');
    Route::post('/info/{id}/setujui', [InfoHubController::class, 'setujui'])->name('info.setujui');
    Route::post('/info/{id}/tolak', [InfoHubController::class, 'tolak'])->name('info.tolak');

    // Laporan konten
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/{id}/tindak', [LaporanController::class, 'tindak'])->name('laporan.tindak');
});
