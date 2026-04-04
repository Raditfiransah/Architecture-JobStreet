<?php

use App\Http\Controllers\Arsitek\DashboardController;
use App\Http\Controllers\Arsitek\ProfilController;
use App\Http\Controllers\Arsitek\PortofolioController;
use App\Http\Controllers\Arsitek\LamaranController;
use App\Http\Controllers\Arsitek\ProposalController;
use App\Http\Controllers\Arsitek\InboxController;
use App\Http\Controllers\Arsitek\NotifikasiController;
use App\Http\Controllers\Arsitek\PengaturanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:arsitek'])
    ->prefix('dashboard/arsitek')
    ->name('arsitek.')
    ->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::get('/profil/preview', [ProfilController::class, 'preview'])->name('profil.preview');
    Route::put('/avatar', [ProfilController::class, 'updateAvatar'])->name('profil.avatar');

    // Portofolio
    Route::get('/portofolio', [PortofolioController::class, 'index'])->name('portofolio.index');
    Route::get('/portofolio/tambah', [PortofolioController::class, 'create'])->name('portofolio.create');
    Route::post('/portofolio', [PortofolioController::class, 'store'])->name('portofolio.store');
    Route::get('/portofolio/{id}/edit', [PortofolioController::class, 'edit'])->name('portofolio.edit');
    Route::put('/portofolio/{id}', [PortofolioController::class, 'update'])->name('portofolio.update');
    Route::delete('/portofolio/{id}', [PortofolioController::class, 'destroy'])->name('portofolio.destroy');
    Route::post('/portofolio/reorder', [PortofolioController::class, 'reorder'])->name('portofolio.reorder');

    // Lamaran kerja
    Route::get('/lamaran', [LamaranController::class, 'index'])->name('lamaran.index');
    Route::get('/lamaran/{id}', [LamaranController::class, 'show'])->name('lamaran.show');
    Route::delete('/lamaran/{id}', [LamaranController::class, 'withdraw'])->name('lamaran.withdraw');

    // Proposal proyek
    Route::get('/proposal', [ProposalController::class, 'index'])->name('proposal.index');
    Route::get('/proposal/{id}', [ProposalController::class, 'show'])->name('proposal.show');
    Route::put('/proposal/{id}', [ProposalController::class, 'update'])->name('proposal.update');

    // Inbox async
    Route::get('/inbox', [InboxController::class, 'index'])->name('inbox.index');
    Route::get('/inbox/{thread}', [InboxController::class, 'show'])->name('inbox.show');
    Route::post('/inbox/{thread}', [InboxController::class, 'reply'])->name('inbox.reply');

    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.readAll');

    // Pengaturan
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan/password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
    Route::put('/pengaturan/notifikasi', [PengaturanController::class, 'updateNotifikasi'])->name('pengaturan.notifikasi');
    Route::delete('/pengaturan/akun', [PengaturanController::class, 'deleteAkun'])->name('pengaturan.delete');
});

// Route melamar — di luar prefix dashboard karena URL-nya dari halaman publik
Route::post('/lowongan/{id}/lamar', [LamaranController::class, 'store'])
    ->middleware(['auth', 'verified', 'role:arsitek'])
    ->name('arsitek.lamaran.store');

// Route kirim proposal — sama, dari halaman publik
Route::post('/proyek/{id}/proposal', [ProposalController::class, 'store'])
    ->middleware(['auth', 'verified', 'role:arsitek'])
    ->name('arsitek.proposal.store');
