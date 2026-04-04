<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class InfoHubController extends Controller
{
    public function index()
    {
        return \Inertia\Inertia::render('Public/DefaultPublicPage', [
            'title' => 'Arsitektur Info Hub',
        ]);
    }

    public function show(string $slug)
    {
        return \Inertia\Inertia::render('Public/DefaultPublicPage', [
            'title' => 'Artikel: ' . $slug,
        ]);
    }
}
