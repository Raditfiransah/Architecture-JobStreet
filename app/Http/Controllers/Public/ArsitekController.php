<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class ArsitekController extends Controller
{
    public function index()
    {
        return \Inertia\Inertia::render('Public/DefaultPublicPage', [
            'title' => 'Direktori Arsitek Indonesia',
        ]);
    }

    public function show(string $username)
    {
        return \Inertia\Inertia::render('Public/DefaultPublicPage', [
            'title' => 'Profil Arsitek: ' . $username,
        ]);
    }
}
