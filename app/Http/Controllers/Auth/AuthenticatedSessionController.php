<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if (! $user->email_verified_at) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => __('Email belum diverifikasi. Silakan verifikasi email Anda terlebih dahulu.'),
            ]);
        }

        if (! $user->is_active) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => __('Akun dinonaktifkan, hubungi admin untuk mengaktifkan kembali.'),
            ]);
        }

        return redirect()->intended(route($user->dashboardRoute(), absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
