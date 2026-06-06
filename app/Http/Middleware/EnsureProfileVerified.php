<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return $next($request);
        }

        $isVerified = match ($user->role) {
            'arsitek' => $user->arsitekProfile?->isVerified() ?? false,
            'perusahaan' => $user->companyProfile?->isVerified() ?? false,
            default => true,
        };

        if ($isVerified) {
            return $next($request);
        }

        $routeName = match ($user->role) {
            'arsitek' => 'arsitek.verifikasi.index',
            'perusahaan' => 'perusahaan.verifikasi.index',
            default => 'dashboard',
        };

        if ($request->expectsJson()) {
            abort(403, 'Profil Anda harus diverifikasi admin terlebih dahulu.');
        }

        return redirect()
            ->route($routeName)
            ->with('error', 'Profil Anda harus diverifikasi admin terlebih dahulu sebelum melakukan aksi ini.');
    }
}
