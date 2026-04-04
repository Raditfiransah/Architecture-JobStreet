<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class LowonganController extends Controller
{
    public function index()
    {
        return \Inertia\Inertia::render('Public/DefaultPublicPage', [
            'title' => 'Daftar Lowongan Arsitek',
        ]);
    }

    public function show(string $id)
    {
        return \Inertia\Inertia::render('Public/DefaultPublicPage', [
            'title' => 'Detail Lowongan #' . $id,
        ]);
    }
}
