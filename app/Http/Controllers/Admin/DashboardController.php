<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return \Inertia\Inertia::render('Dashboard/Admin', [
            'stats' => [
                'total_users' => \App\Models\User::count(),
                'total_companies' => \App\Models\CompanyProfile::count(),
                'total_arsiteks' => \App\Models\ArsitekProfile::count(),
                'total_lowongan' => \App\Models\Lowongan::count(),
                'total_lamaran' => \App\Models\Lamaran::count(),
            ],
        ]);
    }

    public function antrian()
    {
        return view('admin.antrian');
    }
}
