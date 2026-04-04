<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerificationCode;
use Illuminate\Http\Request;

class OtpVerificationController extends Controller
{
    public function showForm(Request $request)
    {
        if (auth()->user()->email_verified_at) {
            return redirect()->route(auth()->user()->dashboardRoute());
        }

        return \Inertia\Inertia::render('Auth/VerifyEmail', [
            'status' => session('status'),
            'email' => $request->user()->email,
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = auth()->user();

        $otp = EmailVerificationCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
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

    public function resend()
    {
        $user = auth()->user();

        if ($user->email_verified_at) {
            return redirect()->route($user->dashboardRoute());
        }

        // Generate and send new OTP (implementation depends on your mail setup)
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(15),
        ]);

        // TODO: Send OTP via email notification

        return back()->with('status', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}
