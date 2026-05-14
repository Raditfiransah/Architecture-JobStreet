<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LowonganController;
use App\Http\Controllers\Admin\ProyekController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InfoHubController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ProfileManagementController;
use App\Http\Controllers\Admin\PortofolioController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\SecurityController;
use Illuminate\Support\Facades\Route;

// ─── Dashboard Admin (authenticated) ─────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('dashboard/admin')
    ->name('admin.')
    ->group(function () {

    // Profil admin
    Route::get('/profil', [\App\Http\Controllers\Admin\ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [\App\Http\Controllers\Admin\ProfilController::class, 'update'])->name('profil.update');
    Route::post('/avatar', [\App\Http\Controllers\Admin\ProfilController::class, 'updateAvatar'])->name('profil.avatar');

    // Dashboard utama
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/antrian', [DashboardController::class, 'antrian'])->name('antrian');

    // Moderasi lowongan
    Route::get('/lowongan', [LowonganController::class, 'index'])->name('lowongan.index');
    Route::get('/lowongan/{lowongan}', [LowonganController::class, 'show'])->name('lowongan.show');
    Route::post('/lowongan/{lowongan}/setujui', [LowonganController::class, 'setujui'])->name('lowongan.setujui');
    Route::post('/lowongan/{lowongan}/tolak', [LowonganController::class, 'tolak'])->name('lowongan.tolak');
    Route::post('/lowongan/{lowongan}/tutup', [LowonganController::class, 'tutup'])->name('lowongan.tutup');

    // Moderasi proyek
    Route::get('/proyek', [ProyekController::class, 'index'])->name('proyek.index');
    Route::get('/proyek/{proyek}', [ProyekController::class, 'show'])->name('proyek.show');
    Route::post('/proyek/{proyek}/setujui', [ProyekController::class, 'setujui'])->name('proyek.setujui');
    Route::post('/proyek/{proyek}/tolak', [ProyekController::class, 'tolak'])->name('proyek.tolak');

    // Kelola user
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/aktifkan', [UserController::class, 'aktifkan'])->name('users.aktifkan');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Info Hub
    Route::get('/info', [InfoHubController::class, 'index'])->name('info.index');
    Route::post('/info/{infoHub}/setujui', [InfoHubController::class, 'setujui'])->name('info.setujui');
    Route::post('/info/{infoHub}/tolak', [InfoHubController::class, 'tolak'])->name('info.tolak');

    // Laporan konten
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/{laporan}/tindak', [LaporanController::class, 'tindak'])->name('laporan.tindak');

    // Profile Management
    Route::get('/profiles', [ProfileManagementController::class, 'index'])->name('profiles.index');
    Route::get('/profiles/{type}/{profile}', [ProfileManagementController::class, 'show'])->name('profiles.show');
    Route::post('/profiles/{type}/{profile}/verify', [ProfileManagementController::class, 'verify'])->name('profiles.verify');
    Route::post('/profiles/{type}/{profile}/reject', [ProfileManagementController::class, 'reject'])->name('profiles.reject');

    // Portofolio Management
    Route::get('/portofolio', [PortofolioController::class, 'index'])->name('portofolio.index');
    Route::get('/portofolio/user/{user}', [PortofolioController::class, 'show'])->name('portofolio.show');
    Route::get('/portofolio/{portofolio}/edit', [PortofolioController::class, 'edit'])->name('portofolio.edit');
    Route::put('/portofolio/{portofolio}', [PortofolioController::class, 'update'])->name('portofolio.update');
    Route::delete('/portofolio/{portofolio}', [PortofolioController::class, 'destroy'])->name('portofolio.destroy');
    Route::delete('/portofolio/{portofolio}/image', [PortofolioController::class, 'destroyImage'])->name('portofolio.destroy-image');

    // System Monitoring
    Route::get('/system', [SystemController::class, 'index'])->name('system.index');
    Route::post('/system/clear-failed', [SystemController::class, 'clearFailedJobs'])->name('system.clear-failed');

    // Security Monitoring
    Route::get('/security', [SecurityController::class, 'index'])->name('security.index');
});
