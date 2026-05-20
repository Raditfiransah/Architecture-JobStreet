<?php

use App\Http\Controllers\Public\LowonganController;
use App\Http\Controllers\Public\ProyekController;
use App\Http\Controllers\Public\ArsitekController;
use App\Http\Controllers\Public\InfoHubController;
use Illuminate\Support\Facades\Route;

// ─── Halaman Publik Tanpa Login ────────────────────────────────────────────
// Route di bawah ini dapat diakses oleh guest maupun user yang sudah login.

// Lowongan kerja arsitektur
Route::get('/lowongan', [LowonganController::class, 'index'])->name('lowongan.index');
Route::get('/lowongan/{lowongan}', [LowonganController::class, 'show'])->name('lowongan.show');

// Proyek arsitektur
Route::middleware('auth')->group(function () {
    Route::get('/proyek', [ProyekController::class, 'index'])->name('proyek.index');
    Route::get('/proyek/{proyek}', [ProyekController::class, 'show'])->name('proyek.show');

    // Direktori arsitek
    Route::get('/arsitek', [ArsitekController::class, 'index'])->name('arsitek.direktori');
    Route::get('/arsitek/{username}', [ArsitekController::class, 'show'])->name('arsitek.profil');

    // Info Hub
    Route::get('/info', [InfoHubController::class, 'index'])->name('info.index');
    Route::get('/info/{slug}', [InfoHubController::class, 'show'])->name('info.show');
});
