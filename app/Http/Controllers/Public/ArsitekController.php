<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class ArsitekController extends Controller
{
    public function index()
    {
        return view('public.arsitek.index');
    }

    public function show(string $username)
    {
        return view('public.arsitek.show', compact('username'));
    }
}
