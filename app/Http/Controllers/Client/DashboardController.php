<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return \Inertia\Inertia::render('Dashboard/Client', [
            'user' => auth()->user(),
        ]);
    }
}
