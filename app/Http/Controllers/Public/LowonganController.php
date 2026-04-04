<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class LowonganController extends Controller
{
    public function index()
    {
        // TODO: Implement - List semua lowongan publik
        return view('public.lowongan.index');
    }

    public function show(string $id)
    {
        // TODO: Implement - Detail lowongan
        return view('public.lowongan.show', compact('id'));
    }
}
