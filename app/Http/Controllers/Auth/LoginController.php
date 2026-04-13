<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResendOtpMail;

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

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Reject inactive users
            if (! $user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            // Redirect unverified users to OTP verification page
            if (! $user->email_verified_at) {
                // Generate and send OTP for unverified users
                try {
                    $otpService = app(OtpService::class);
                    if ($otpService->canResend($user)) {
                        $verificationCode = $otpService->generate($user);
                        Mail::to($user->email)->send(new ResendOtpMail($verificationCode->code, $user->name));
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to send OTP on login: '.$e->getMessage());
                }

                session(['otp_email' => $user->email]);

                return redirect()->route('verification.notice');
            }

            // Redirect verified users to their role-based dashboard
            return redirect()->intended(route($user->dashboardRoute()));
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
}
