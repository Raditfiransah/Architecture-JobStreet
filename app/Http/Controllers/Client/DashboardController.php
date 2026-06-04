<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $activeProjectsCount = Proyek::where('user_id', $user->id)->where('status', 'aktif')->count();
        $completedProjectsCount = Proyek::where('user_id', $user->id)->where('status', 'selesai')->count();
        
        // Count incoming proposals that are pending for this client's projects
        $incomingProposalsCount = Proposal::whereIn('proyek_id', function ($query) use ($user) {
            $query->select('id')->from('proyek')->where('user_id', $user->id);
        })->where('status', 'pending')->count();
        
        // Fetch 3 most recent projects with their proposal counts
        $recentProjects = Proyek::where('user_id', $user->id)
            ->withCount('proposals')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
            
        return \Inertia\Inertia::render('Dashboard/Client', [
            'user' => $user,
            'clientProfile' => $user->clientProfile,
            'projects' => $recentProjects,
            'stats' => [
                'active_projects' => $activeProjectsCount,
                'incoming_proposals' => $incomingProposalsCount,
                'completed_projects' => $completedProjectsCount,
            ]
        ]);
    }
}
