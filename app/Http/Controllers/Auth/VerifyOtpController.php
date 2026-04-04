<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResendOtpMail;
use App\Models\EmailVerificationCode;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class VerifyOtpController extends Controller
{
    /**
     * Resolve the user needing OTP verification.
     * Uses session('otp_email') with fallback to authenticated user.
     */
    private function resolveUser(): ?User
    {
        $email = session('otp_email');

        if ($email) {
            return User::where('email', $email)->first();
        }

        // Fallback: if user is logged in, use their email
        if (Auth::check()) {
            $user = Auth::user();
            session(['otp_email' => $user->email]); // restore session

            return $user;
        }

        return null;
    }

    public function show(): View|RedirectResponse
    {
        $user = $this->resolveUser();

        // If no user can be resolved, redirect to login
        if (! $user) {
            return redirect()->route('login')
                ->withErrors(['email' => __('Sesi telah berakhir. Silakan login kembali.')]);
        }

        // If already verified, redirect to dashboard
        if ($user->email_verified_at) {
            return redirect()->route($user->dashboardRoute())
                ->with('status', __('Email sudah diverifikasi.'));
        }

        return view('auth.verify-email', [
            'email' => $user->email,
        ]);
    }

    public function verify(Request $request, OtpService $otpService): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6', 'regex:/^\d{6}$/'],
        ]);

        $user = $this->resolveUser();

        if (! $user) {
            return redirect()->route('login')
                ->withErrors(['email' => __('Sesi telah berakhir. Silakan login kembali.')]);
        }

        $verificationCode = $otpService->validate($user, $request->code);

        if (! $verificationCode) {
            $existingCode = EmailVerificationCode::where('user_id', $user->id)
                ->where('code', $request->code)
                ->where('is_used', false)
                ->first();

            if ($existingCode && $existingCode->isExpired()) {
                return back()->withErrors(['code' => __('Kode sudah expired. Silakan kirim ulang kode baru.')]);
            }

            return back()->withErrors(['code' => __('Kode salah, silakan coba lagi.')]);
        }

        $otpService->markUsed($verificationCode);

        $user->email_verified_at = now();
        $user->save();

        // Ensure user is logged in after verification
        if (! Auth::check()) {
            Auth::login($user);
        }

        // Clean up OTP session
        session()->forget('otp_email');

        return redirect()->route($user->dashboardRoute())
            ->with('status', __('Email berhasil diverifikasi. Selamat datang!'));
    }

    public function resend(Request $request, OtpService $otpService): RedirectResponse
    {
        $user = $this->resolveUser();

        if (! $user) {
            return redirect()->route('login')
                ->withErrors(['email' => __('Sesi telah berakhir. Silakan login kembali.')]);
        }

        if (! $otpService->canResend($user)) {
            return back()->withErrors(['email' => __('Tunggu 1 menit sebelum mengirim ulang kode.')]);
        }

        $verificationCode = $otpService->generate($user);

        try {
            Mail::to($user->email)->send(new ResendOtpMail($verificationCode->code, $user->name));
        } catch (\Exception $e) {
            Log::error('Failed to resend OTP email: '.$e->getMessage());

            return back()->withErrors(['email' => __('Gagal mengirim email. Silakan coba lagi nanti.')]);
        }

        return back()->with('status', __('Kode verifikasi baru telah dikirim ke :email.', ['email' => $user->email]));
    }
}
