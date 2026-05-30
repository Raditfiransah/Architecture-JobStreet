<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ─── Landing Page ──────────────────────────────────────────────────────────────
Route::get('/', function () {
    return Inertia::render('Landing');
})->name('home');

// ─── Dashboard redirect (used by email verification) ──────────────────────────
Route::middleware('auth')->get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = request()->user();

    return redirect()->route($user->dashboardRoute());
})->name('dashboard');

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
