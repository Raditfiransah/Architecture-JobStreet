<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\ResendOtpMail;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request, OtpService $otpService): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Unverified user → redirect to OTP verification instead of blocking
        if (! $user->email_verified_at) {
            $verificationCode = $otpService->generate($user);

            try {
                Mail::to($user->email)->send(new ResendOtpMail($verificationCode->code, $user->name));
            } catch (\Exception $e) {
                Log::error('Failed to send OTP email on login: '.$e->getMessage());
            }

            session(['otp_email' => $user->email]);

            return redirect()->route('verification.notice')
                ->with('status', __('Email belum diverifikasi. Kode verifikasi baru telah dikirim ke :email.', ['email' => $user->email]));
        }

        // Deactivated account
        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
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
