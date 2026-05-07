<?php

use Illuminate\Support\Facades\Route;

// ─── Landing Page ──────────────────────────────────────────────────────────────
Route::get('/', function () {
    return \Inertia\Inertia::render('Landing');
})->name('home');

// ─── Public Directories ────────────────────────────────────────────────────────
Route::get('/hire-arsitek', [\App\Http\Controllers\Public\ArsitekController::class, 'index'])->name('arsitek.index');
Route::get('/arsitek/{user}', [\App\Http\Controllers\Public\ProfileViewController::class, 'showArsitek'])->name('public.arsitek.show');
Route::get('/perusahaan/{user}', [\App\Http\Controllers\Public\ProfileViewController::class, 'showPerusahaan'])->name('public.perusahaan.show');

// ─── Auth (public + login/register + OTP + logout) ────────────────────────────
require __DIR__.'/auth.php';

// ─── Halaman Publik (lowongan, proyek, arsitek direktori, info hub) ────────────
require __DIR__.'/public.php';

// ─── Dashboard per role ───────────────────────────────────────────────────────
require __DIR__.'/arsitek.php';
require __DIR__.'/perusahaan.php';
require __DIR__.'/client.php';
require __DIR__.'/admin.php';

// ─── Health Check ─────────────────────────────────────────────────────────────
Route::get('/health', \App\Http\Controllers\HealthController::class)->name('health');

// ─── Redirect 301 — URL lama ke URL baru (ISU-01 migration safety net) ────────
// Simpan redirect ini setidaknya 3 bulan setelah deployment, lalu hapus.
Route::redirect('/profile/arsitek', '/dashboard/arsitek', 301);
Route::redirect('/profile/perusahaan', '/dashboard/perusahaan', 301);
Route::redirect('/profile/client', '/dashboard/client', 301);
