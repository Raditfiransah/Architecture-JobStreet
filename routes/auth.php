<?php

use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ─── Auth (hanya untuk guest) ─────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    // Indonesian-language routes (keep intact)
    Route::get('/lupa-password', [PasswordResetController::class, 'showForm'])->name('password.request');
    Route::post('/lupa-password', [PasswordResetController::class, 'sendLink'])->name('password.email');

    // English-path aliases for forgot-password (Task 3.2)
    Route::get('/forgot-password', [PasswordResetController::class, 'showForm']);
    Route::post('/forgot-password', [PasswordResetController::class, 'sendLink']);

    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// ─── Auth (perlu login) ───────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    // Verifikasi OTP — Indonesian routes (keep intact, remove verification.notice name)
    Route::get('/verifikasi-email', [OtpVerificationController::class, 'showForm']);
    Route::post('/verifikasi-email', [OtpVerificationController::class, 'verify'])->name('otp.verify');
    Route::post('/verifikasi-email/resend', [OtpVerificationController::class, 'resend'])
        ->middleware('throttle:3,1')
        ->name('otp.resend');

    // English-path aliases for OTP/verification routes (Task 3.1)
    Route::get('/verify-email', [OtpVerificationController::class, 'showForm'])->name('verification.notice');
    Route::post('/verify-email', [OtpVerificationController::class, 'verify']);
    Route::post('/verify-email/resend', [OtpVerificationController::class, 'resend'])
        ->middleware('throttle:3,1');

    // Email verification via signed URL (Task 3.1)
    Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Confirm password (Task 3.3)
    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Update password (Task 3.4)
    Route::put('/password', [PasswordController::class, 'update'])->name('password.change');

    // Profile (Task 3.5)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
