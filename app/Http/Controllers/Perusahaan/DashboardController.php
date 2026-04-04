<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return \Inertia\Inertia::render('Dashboard/Perusahaan', [
            'user' => $user,
            'companyName' => $user->companyProfile->company_name ?? $user->name,
        ]);
    }
}
