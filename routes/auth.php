<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Public\LowonganController;
use App\Http\Controllers\Public\ArsitekController;
use App\Http\Controllers\Public\InfoHubController;
use Illuminate\Support\Facades\Route;

// ─── Halaman publik (guest + logged in bisa akses) ───────────────────
Route::get('/lowongan', [LowonganController::class, 'index'])->name('lowongan.index');
Route::get('/lowongan/{id}', [LowonganController::class, 'show'])->name('lowongan.show');

// ─── Auth (hanya untuk guest) ─────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/lupa-password', [PasswordResetController::class, 'showForm'])->name('password.request');
    Route::post('/lupa-password', [PasswordResetController::class, 'sendLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// ─── Fitur Utama (butuh login) ────────────────────────────────────────
Route::middleware('auth')->group(function () {
    // Arsitek
    Route::get('/arsitek', [ArsitekController::class, 'index'])->name('arsitek.direktori');
    Route::get('/arsitek/{username}', [ArsitekController::class, 'show'])->name('arsitek.profil');

    // Info Hub
    Route::get('/info', [InfoHubController::class, 'index'])->name('info.index');
    Route::get('/info/{slug}', [InfoHubController::class, 'show'])->name('info.show');

    // Verifikasi OTP (belum verified)
    Route::get('/verifikasi-email', [OtpVerificationController::class, 'showForm'])->name('verification.notice');
    Route::post('/verifikasi-email', [OtpVerificationController::class, 'verify'])->name('otp.verify');
    Route::post('/verifikasi-email/resend', [OtpVerificationController::class, 'resend'])
        ->middleware('throttle:3,1')
        ->name('otp.resend');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
