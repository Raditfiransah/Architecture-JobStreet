<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        return \Inertia\Inertia::render('Auth/Login', [
            'status' => session('status'),
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tambahkan pengecekan is_active
        if (Auth::attempt(array_merge($credentials, ['is_active' => true]), $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended($this->redirectAfterLogin($request));
        }

        // Cek apakah user ada tapi tidak aktif
        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user && ! $user->is_active) {
            return back()->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi admin.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function redirectAfterLogin(Request $request): string
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        
        return match ($user->role) {
            'arsitek' => route('arsitek.dashboard'),
            'perusahaan' => route('perusahaan.dashboard'),
            'client' => route('client.dashboard'),
            'admin' => route('admin.dashboard'),
            default => route('home'),
        };
    }
}
