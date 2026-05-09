<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerificationCode;
use App\Mail\ResendOtpMail;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OtpVerificationController extends Controller
{
    public function showForm(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        if ($user->email_verified_at) {
            return redirect()->route($user->dashboardRoute());
        }

        return \Inertia\Inertia::render('Auth/VerifyEmail', [
            'status' => session('status'),
            'email' => $request->user()->email,
            'title' => 'Kode Verifikasi',
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();

        $otp = EmailVerificationCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('expired_at', '>', now())
            ->latest()
            ->first();

        if (! $otp) {
            return back()->withErrors(['code' => 'Kode OTP tidak valid atau sudah kadaluarsa.']);
        }

        $user->forceFill(['email_verified_at' => now()])->save();
        $otp->delete();

        return redirect()->route($user->dashboardRoute())
            ->with('status', 'Email berhasil diverifikasi!');
    }

    public function resend(Request $request, OtpService $otpService)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($user->email_verified_at) {
            return redirect()->route($user->dashboardRoute());
        }

        if (! $otpService->canResend($user)) {
             return back()->withErrors(['resend' => 'Tunggu sebentar sebelum mencoba lagi.']);
        }

        // Generate and log new OTP
        $verificationCode = $otpService->generate($user);
        
        \Illuminate\Support\Facades\Log::info("Resending OTP for user {$user->email}: {$verificationCode->code}");

        // Send OTP via email
        try {
            Mail::to($user->email)->send(new ResendOtpMail($verificationCode->code, $user->name));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to resend OTP email: '.$e->getMessage());
        }

        return back()->with('status', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}
