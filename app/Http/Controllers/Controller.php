<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function ensureProfileVerified(): void
    {
        $user = auth()->user();

        if (! $user) {
            abort(403);
        }

        $isVerified = match ($user->role) {
            'arsitek' => $user->arsitekProfile?->isVerified() ?? false,
            'perusahaan' => $user->companyProfile?->isVerified() ?? false,
            'client' => $user->clientProfile?->isVerified() ?? false,
            default => true,
        };

        if ($isVerified) {
            return;
        }

        $routeName = match ($user->role) {
            'arsitek' => 'arsitek.verifikasi.index',
            'perusahaan' => 'perusahaan.verifikasi.index',
            'client' => 'client.verifikasi.index',
            default => 'dashboard',
        };

        $message = 'Dokumen profil Anda belum diverifikasi. Silakan selesaikan verifikasi dokumen terlebih dahulu untuk menggunakan fitur ini.';

        if (request()->expectsJson()) {
            abort(403, $message);
        }

        redirect()
            ->route($routeName)
            ->with('error', $message)
            ->send();

        exit;
    }
}
