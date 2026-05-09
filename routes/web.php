<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return \Inertia\Inertia::render('Landing');
})->name('home');

// ─── Public Directories ───────────────────────────────
Route::get('/hire-arsitek', [\App\Http\Controllers\Public\ArsitekController::class, 'index'])->name('arsitek.index');
Route::get('/arsitek/{id}', [\App\Http\Controllers\Public\ProfileViewController::class, 'showArsitek'])->name('public.arsitek.show');
Route::get('/perusahaan/{id}', [\App\Http\Controllers\Public\ProfileViewController::class, 'showPerusahaan'])->name('public.perusahaan.show');
Route::get('/info-hub', [\App\Http\Controllers\Public\InfoHubController::class, 'index'])->name('public.info.index');

// ─── Auth (public + login/register + OTP + logout) ──────────
require __DIR__.'/auth.php';

// ─── Dashboard per role ──────────────────────────────────────
require __DIR__.'/arsitek.php';
require __DIR__.'/perusahaan.php';
require __DIR__.'/client.php';
require __DIR__.'/admin.php';

Route::get('/health', \App\Http\Controllers\HealthController::class);
