<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user || ! $user->isArsitek()) {
            return $next($request);
        }

        if ($request->is('profil/*') || $request->is('logout')) {
            return $next($request);
        }

        if (! $user->companyProfile) {
            return redirect()->route('profil.lengkapi');
        }

        return $next($request);
    }
}
