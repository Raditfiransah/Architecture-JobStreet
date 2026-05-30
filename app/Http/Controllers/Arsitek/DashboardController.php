<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('arsitekProfile');
        
        // Count active architect proposals that are currently pending
        $activeProposalsCount = Proposal::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
            
        return \Inertia\Inertia::render('Dashboard/Arsitek', [
            'user' => $user,
            'arsitekProfile' => $user->arsitekProfile,
            'stats' => [
                'lamaran_dikirim' => $user->lamarans()->count(),
                'proposal_aktif' => $activeProposalsCount,
                'profil_dilihat' => 0, // Placeholder
            ]
        ]);
    }
}
