<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return \Inertia\Inertia::render('Dashboard/Admin', [
            'user' => auth()->user(),
        ]);
    }

    public function antrian()
    {
        return view('admin.antrian');
    }
}
