<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\InfoHub;

class InfoHubController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/InfoHub/Index', [
            'title' => 'Arsitektur Info Hub',
            'infohubs' => InfoHub::latest()->paginate(12),
        ]);
    }

    public function show(string $slug)
    {
        return Inertia::render('Public/DefaultPublicPage', [
            'title' => 'Artikel: ' . $slug,
        ]);
    }
}
