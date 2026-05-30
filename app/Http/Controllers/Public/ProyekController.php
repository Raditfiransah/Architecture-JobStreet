<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProyekController extends Controller
{
    public function index(Request $request)
    {
        $query = Proyek::where('status', 'aktif')
            ->with(['user', 'proposals']);

        // Search query
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Filter by category
        if ($request->filled('c')) {
            $query->where('category', $request->input('c'));
        }

        // Filter by location
        if ($request->filled('l')) {
            $query->where('location', 'like', '%' . $request->input('l') . '%');
        }

        $projects = $query->orderBy('created_at', 'desc')->get();

        return Inertia::render('Public/Proyek/Index', [
            'projects' => $projects,
            'filters' => $request->only(['q', 'c', 'l']),
            'title' => 'Eksplorasi Proyek Arsitektur',
        ]);
    }

    public function show(string $id)
    {
        $project = Proyek::with(['user'])->findOrFail($id);

        $myProposal = null;
        if (auth()->check() && auth()->user()->isArsitek()) {
            $myProposal = Proposal::where('user_id', auth()->id())
                ->where('proyek_id', $id)
                ->first();
        }

        return Inertia::render('Public/Proyek/Show', [
            'project' => $project,
            'myProposal' => $myProposal,
            'title' => 'Detail Proyek: ' . $project->title,
        ]);
    }
}
