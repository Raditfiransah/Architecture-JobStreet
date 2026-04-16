<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        
        return \Inertia\Inertia::render('Profile/EditClient', [
            'user' => $user,
            'clientProfile' => $user->clientProfile,
        ]);
    }
}
