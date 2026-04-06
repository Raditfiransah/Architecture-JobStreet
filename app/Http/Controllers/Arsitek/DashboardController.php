<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('arsitekProfile');
        return \Inertia\Inertia::render('Arsitek/Profile', [
            'user' => $user,
            'arsitekProfile' => $user->arsitekProfile,
        ]);
    }
}
