<?php

use App\Http\Controllers\Perusahaan\DashboardController;
use App\Http\Controllers\Perusahaan\ProfilController;
use App\Http\Controllers\Perusahaan\LowonganController;
use App\Http\Controllers\Perusahaan\PelamarController;
use App\Http\Controllers\Perusahaan\InboxController;
use App\Http\Controllers\Perusahaan\PengaturanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:perusahaan'])
    ->prefix('profile/perusahaan')
    ->name('perusahaan.')
    ->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('profile');

    // Profil perusahaan
    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::post('/logo', [ProfilController::class, 'updateLogo'])->name('profil.logo');
    Route::post('/profil/document', [ProfilController::class, 'uploadDocument'])->name('profil.document');

    // Kelola lowongan
    Route::get('/lowongan', [LowonganController::class, 'index'])->name('lowongan.index');
    Route::get('/lowongan/buat', [LowonganController::class, 'create'])->name('lowongan.create');
    Route::post('/lowongan', [LowonganController::class, 'store'])->name('lowongan.store');
    Route::get('/lowongan/{id}/edit', [LowonganController::class, 'edit'])->name('lowongan.edit');
    Route::put('/lowongan/{id}', [LowonganController::class, 'update'])->name('lowongan.update');
    Route::put('/lowongan/{id}/tutup', [LowonganController::class, 'tutup'])->name('lowongan.tutup');
    Route::put('/lowongan/{id}/perpanjang', [LowonganController::class, 'perpanjang'])->name('lowongan.perpanjang');
    Route::delete('/lowongan/{id}', [LowonganController::class, 'destroy'])->name('lowongan.destroy');

    // Kelola pelamar
    Route::get('/kandidat', [PelamarController::class, 'all'])->name('pelamar.all');
    Route::get('/lowongan/{id}/pelamar', [PelamarController::class, 'index'])->name('pelamar.index');
    Route::get('/lowongan/{id}/pelamar/{appId}', [PelamarController::class, 'show'])->name('pelamar.show');
    Route::put('/lamaran/{appId}/status', [PelamarController::class, 'updateStatus'])->name('lamaran.status');
    Route::post('/lamaran/{appId}/shortlist', [PelamarController::class, 'shortlist'])->name('lamaran.shortlist');

    /*
    // Inbox
    Route::get('/inbox', [InboxController::class, 'index'])->name('inbox.index');
    Route::get('/inbox/{thread}', [InboxController::class, 'show'])->name('inbox.show');
    Route::post('/inbox/{thread}', [InboxController::class, 'reply'])->name('inbox.reply');
    */

    // Pengaturan
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan/password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
});
