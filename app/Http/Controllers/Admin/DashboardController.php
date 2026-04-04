<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.admin');
    }

    public function antrian()
    {
        return view('admin.antrian');
    }
}
