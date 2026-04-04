<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class ProyekController extends Controller
{
    public function index()
    {
        return \Inertia\Inertia::render('Public/DefaultPublicPage', [
            'title' => 'Eksplorasi Proyek Arsitektur',
        ]);
    }

    public function show(string $id)
    {
        return \Inertia\Inertia::render('Public/DefaultPublicPage', [
            'title' => 'Detail Proyek #' . $id,
        ]);
    }
}
