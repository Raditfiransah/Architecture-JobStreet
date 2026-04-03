<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResendOtpMail;
use App\Models\EmailVerificationCode;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class VerifyOtpController extends Controller
{
    public function show(): View
    {
        return view('auth.verify-email');
    }

    public function verify(Request $request, OtpService $otpService): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6', 'digits:6'],
        ]);

        $user = User::where('email', session('otp_email'))->first();

        if (! $user) {
            return back()->withErrors(['code' => __('Kode tidak valid, minta kode baru.')]);
        }

        $verificationCode = $otpService->validate($user, $request->code);

        if (! $verificationCode) {
            $existingCode = EmailVerificationCode::where('user_id', $user->id)
                ->where('code', $request->code)
                ->where('is_used', false)
                ->first();

            if ($existingCode && $existingCode->isExpired()) {
                return back()->withErrors(['code' => __('Kode sudah expired, kirim ulang.')]);
            }

            return back()->withErrors(['code' => __('Kode salah, silakan coba lagi.')]);
        }

        $otpService->markUsed($verificationCode);

        $user->update([
            'email_verified_at' => now(),
        ]);

        return redirect()->route('login')
            ->with('status', __('Email berhasil diverifikasi. Silakan login.'));
    }

    public function resend(Request $request, OtpService $otpService): RedirectResponse
    {
        $user = User::where('email', session('otp_email'))->first();

        if (! $user) {
            return back()->withErrors(['email' => __('Email tidak ditemukan.')]);
        }

        if (! $otpService->canResend($user)) {
            return back()->withErrors(['email' => __('Tunggu 1 menit sebelum mengirim ulang kode.')]);
        }

        $verificationCode = $otpService->generate($user);

        Mail::to($user->email)->send(new ResendOtpMail($verificationCode->code, $user->name));

        return back()->with('status', __('Kode verifikasi baru telah dikirim ke :email.', ['email' => $user->email]));
    }
}
