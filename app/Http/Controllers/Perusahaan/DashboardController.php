<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        $user->load('companyProfile');
        
        $lowonganIds = $user->lowongans()->pluck('id');
        
        $stats = [
            'total_lowongan' => $user->lowongans()->count(),
            'lowongan_aktif' => $user->lowongans()->where('status', 'aktif')->count(),
            'total_pelamar' => \App\Models\Lamaran::whereIn('lowongan_id', $lowonganIds)->count(),
            'shortlisted' => \App\Models\Lamaran::whereIn('lowongan_id', $lowonganIds)
                ->where('status', 'shortlisted')
                ->count(),
        ];

        $recentApplications = \App\Models\Lamaran::whereIn('lowongan_id', $lowonganIds)
            ->with(['user', 'lowongan'])
            ->latest()
            ->limit(5)
            ->get();

        return \Inertia\Inertia::render('Dashboard/Perusahaan', [
            'stats' => $stats,
            'recentApplications' => $recentApplications,
            'companyName' => $user->companyProfile->company_name ?? $user->name,
        ]);
    }
}
