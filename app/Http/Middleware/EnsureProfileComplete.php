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

        if ($request->is('profil/*') || $request->is('logout') || $request->is('verifikasi-email')) {
            return $next($request);
        }

        if ($user->isArsitek() && ! $user->arsitekProfile) {
            return redirect()->route('profil.lengkapi');
        }

        if ($user->isPerusahaan() && ! $user->companyProfile) {
            return redirect()->route('profil.lengkapi');
        }

        return $next($request);
    }
}
