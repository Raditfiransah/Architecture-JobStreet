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

        if (! $user || $user->email_verified_at) {
            return $next($request);
        }

        return redirect()->route('verification.notice');
    }
}
