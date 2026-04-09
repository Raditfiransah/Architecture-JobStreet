<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return \Inertia\Inertia::render('Landing');
})->name('home');

// ─── Public Arsitek Directory ───────────────────────────────
Route::get('/hire-arsitek', [\App\Http\Controllers\Public\ArsitekController::class, 'index'])->name('arsitek.index');

// ─── Auth (public + login/register + OTP + logout) ──────────
require __DIR__.'/auth.php';

// ─── Dashboard per role ──────────────────────────────────────
require __DIR__.'/arsitek.php';
require __DIR__.'/perusahaan.php';
require __DIR__.'/client.php';
require __DIR__.'/admin.php';

Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});
