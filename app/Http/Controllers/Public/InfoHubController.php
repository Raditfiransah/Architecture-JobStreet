<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class InfoHubController extends Controller
{
    public function index()
    {
        return view('public.info.index');
    }

    public function show(string $slug)
    {
        return view('public.info.show', compact('slug'));
    }
}
