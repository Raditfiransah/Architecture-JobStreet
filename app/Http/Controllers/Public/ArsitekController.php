<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class ArsitekController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\ArsitekProfile::query()->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        $arsiteks = $query->latest()->paginate(12)->withQueryString();
        
        return \Inertia\Inertia::render('Public/Arsitek/Index', [
            'arsiteks' => $arsiteks,
            'filters' => $request->only(['search', 'location']),
        ]);
    }

    public function show(string $username)
    {
        return \Inertia\Inertia::render('Public/DefaultPublicPage', [
            'title' => 'Profil Arsitek: ' . $username,
        ]);
    }
}
