<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return \Inertia\Inertia::render('Dashboard/Arsitek', [
            'user' => auth()->user(),
        ]);
    }
}
