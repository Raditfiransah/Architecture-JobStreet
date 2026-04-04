<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class ProyekController extends Controller
{
    public function index()
    {
        return view('public.proyek.index');
    }

    public function show(string $id)
    {
        return view('public.proyek.show', compact('id'));
    }
}
