<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use Illuminate\Support\Facades\Route;

// ─── Auth (hanya untuk guest) ─────────────────────────────────────────────────
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

// ─── Auth (perlu login) ───────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    // Verifikasi OTP (belum verified)
    Route::get('/verifikasi-email', [OtpVerificationController::class, 'showForm'])->name('verification.notice');
    Route::post('/verifikasi-email', [OtpVerificationController::class, 'verify'])->name('otp.verify');
    Route::post('/verifikasi-email/resend', [OtpVerificationController::class, 'resend'])
        ->middleware('throttle:3,1')
        ->name('otp.resend');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
