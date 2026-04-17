<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        
        return \Inertia\Inertia::render('Dashboard/Client', [
            'user' => $user,
            'clientProfile' => $user->clientProfile,
            'stats' => [
                'active_projects' => 0,
                'incoming_proposals' => 0,
                'completed_projects' => 0,
            ]
        ]);
    }
}
