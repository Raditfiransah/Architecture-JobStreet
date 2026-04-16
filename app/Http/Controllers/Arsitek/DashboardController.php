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
        return \Inertia\Inertia::render('Profile/EditArsitek', [
            'user' => $user,
            'arsitekProfile' => $user->arsitekProfile,
        ]);
    }
}
