<?php

use App\Http\Controllers\ProfileController;
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

Route::middleware(['auth', 'email.verified'])->group(function () {
    Route::middleware('role:arsitek')->prefix('dashboard/arsitek')->group(function () {
        Route::get('/', function () {
            return view('dashboard.arsitek');
        })->name('dashboard.arsitek');
    });

    Route::middleware('role:perusahaan')->prefix('dashboard/perusahaan')->group(function () {
        Route::get('/', function () {
            return view('dashboard.perusahaan');
        })->name('dashboard.perusahaan');
    });

    Route::middleware('role:client')->prefix('dashboard/client')->group(function () {
        Route::get('/', function () {
            return view('dashboard.client');
        })->name('dashboard.client');
    });

    Route::middleware('role:admin')->prefix('dashboard/admin')->group(function () {
        Route::get('/', function () {
            return view('dashboard.admin');
        })->name('dashboard.admin');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
