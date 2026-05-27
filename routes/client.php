<?php

use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\ProyekController;
use App\Http\Controllers\Client\ProposalController;
use App\Http\Controllers\Client\InboxController;
use App\Http\Controllers\Client\PengaturanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:client'])
    ->prefix('profile/client')
    ->name('client.')
    ->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('profile');

    Route::get('/profil', [\App\Http\Controllers\Client\ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [\App\Http\Controllers\Client\ProfilController::class, 'update'])->name('profil.update');
    Route::post('/avatar', [\App\Http\Controllers\Client\ProfilController::class, 'updateAvatar'])->name('profil.avatar');

    // Kelola proyek
    Route::get('/proyek', [ProyekController::class, 'index'])->name('proyek.index');
    Route::get('/proyek/buat', [ProyekController::class, 'create'])->name('proyek.create');
    Route::post('/proyek', [ProyekController::class, 'store'])->name('proyek.store');
    Route::get('/proyek/{id}', [ProyekController::class, 'show'])->name('proyek.show');
    Route::get('/proyek/{id}/edit', [ProyekController::class, 'edit'])->name('proyek.edit');
    Route::put('/proyek/{id}', [ProyekController::class, 'update'])->name('proyek.update');
    Route::put('/proyek/{id}/tutup', [ProyekController::class, 'tutup'])->name('proyek.tutup');
    Route::delete('/proyek/{id}', [ProyekController::class, 'destroy'])->name('proyek.destroy');

    // Kelola proposal masuk
    Route::get('/proyek/{id}/proposal', [ProposalController::class, 'index'])->name('proposal.index');
    Route::get('/proyek/{id}/compare', [ProposalController::class, 'compare'])->name('proposal.compare');
    Route::get('/proyek/{id}/proposal/{propId}', [ProposalController::class, 'show'])->name('proposal.show');
    Route::post('/proposal/{propId}/terima', [ProposalController::class, 'terima'])->name('proposal.terima');
    Route::post('/proposal/{propId}/tolak', [ProposalController::class, 'tolak'])->name('proposal.tolak');

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
