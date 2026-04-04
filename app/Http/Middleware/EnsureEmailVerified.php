<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Allow if no user (handled by auth middleware) or already verified
        if (! $user || $user->email_verified_at) {
            return $next($request);
        }

        // Set session for OTP page to pick up
        session(['otp_email' => $user->email]);

        return redirect()->route('verification.notice')
            ->with('warning', __('Anda harus memverifikasi email terlebih dahulu.'));
    }
}
