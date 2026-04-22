<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        $user->load('arsitekProfile');
        
        return \Inertia\Inertia::render('Dashboard/Arsitek', [
            'user' => $user,
            'arsitekProfile' => $user->arsitekProfile,
            'stats' => [
                'lamaran_dikirim' => $user->lamarans()->count(),
                'proposal_aktif' => 0, // Placeholder
                'profil_dilihat' => 0, // Placeholder
            ]
        ]);
    }
}
