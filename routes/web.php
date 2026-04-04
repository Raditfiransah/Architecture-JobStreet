<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->email_verified_at) {
            return redirect()->route($user->dashboardRoute());
        }

        return redirect()->route('verification.notice');
    }

    return view('welcome');
})->name('home');

// ─── Auth (public + login/register + OTP + logout) ──────────
require __DIR__.'/auth.php';

// ─── Dashboard per role ──────────────────────────────────────
require __DIR__.'/arsitek.php';
require __DIR__.'/perusahaan.php';
require __DIR__.'/client.php';
require __DIR__.'/admin.php';
