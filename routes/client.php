<?php

use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\ProyekController;
use App\Http\Controllers\Client\ProposalController;
use App\Http\Controllers\Client\InboxController;
use App\Http\Controllers\Client\PengaturanController;
use Illuminate\Support\Facades\Route;

// ─── Dashboard Client (authenticated) ────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:client'])
    ->prefix('dashboard/client')
    ->name('client.')
    ->group(function () {

    // Dashboard utama
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profil', [\App\Http\Controllers\Client\ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [\App\Http\Controllers\Client\ProfilController::class, 'update'])->name('profil.update');
    Route::post('/avatar', [\App\Http\Controllers\Client\ProfilController::class, 'updateAvatar'])->name('profil.avatar');

    // Kelola proyek
    Route::get('/proyek', [ProyekController::class, 'index'])->name('proyek.index');
    Route::get('/proyek/buat', [ProyekController::class, 'create'])->name('proyek.create');
    Route::post('/proyek', [ProyekController::class, 'store'])->name('proyek.store');
    Route::get('/proyek/{proyek}', [ProyekController::class, 'show'])->name('proyek.show');
    Route::get('/proyek/{proyek}/edit', [ProyekController::class, 'edit'])->name('proyek.edit');
    Route::put('/proyek/{proyek}', [ProyekController::class, 'update'])->name('proyek.update');
    Route::put('/proyek/{proyek}/tutup', [ProyekController::class, 'tutup'])->name('proyek.tutup');
    Route::delete('/proyek/{proyek}', [ProyekController::class, 'destroy'])->name('proyek.destroy');

    // Kelola proposal masuk — 'proposal-masuk' membedakan dari arsitek.proposal (yang diajukan)
    Route::get('/proyek/{proyek}/proposal', [ProposalController::class, 'index'])->name('proposal-masuk.index');
    Route::get('/proyek/{proyek}/proposal/{proposal}', [ProposalController::class, 'show'])->name('proposal-masuk.show');
    Route::post('/proposal/{proposal}/terima', [ProposalController::class, 'terima'])->name('proposal-masuk.terima');
    Route::post('/proposal/{proposal}/tolak', [ProposalController::class, 'tolak'])->name('proposal-masuk.tolak');

    /*
    // Inbox
    Route::get('/inbox', [InboxController::class, 'index'])->name('inbox.index');
    Route::get('/inbox/{thread}', [InboxController::class, 'show'])->name('inbox.show');
    Route::post('/inbox/{thread}', [InboxController::class, 'reply'])->name('inbox.reply');
    */
    // Verifikasi
    Route::inertia('/verifikasi', 'Client/Verifikasi')->name('verifikasi.index');

    // Pengaturan
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan/password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
});
