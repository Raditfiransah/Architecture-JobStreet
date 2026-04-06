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
        return \Inertia\Inertia::render('Profile/Edit', [
            'user' => $user,
            'companyProfile' => $user->companyProfile,
            'companyName' => $user->companyProfile->company_name ?? $user->name,
        ]);
    }
}
