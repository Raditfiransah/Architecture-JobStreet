<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ProyekController extends Controller
{
    public function index()
    {
        $projects = Proyek::where('user_id', auth()->id())
            ->withCount('proposals')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Client/Proyek/Index', [
            'projects' => $projects
        ]);
    }

    public function create()
    {
        return Inertia::render('Client/Proyek/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'budget' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'location' => 'required|string|max:100',
            'attachment' => 'nullable|file|mimes:pdf,zip,jpg,png,doc,docx|max:10240', // max 10MB
        ]);

        $attachment_path = null;
        if ($request->hasFile('attachment')) {
            $attachment_path = $request->file('attachment')->store('proyek/attachments', 'public');
        }

        Proyek::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'budget' => $request->budget,
            'category' => $request->category,
            'location' => $request->location,
            'attachment_path' => $attachment_path,
            'status' => 'aktif',
        ]);

        return redirect()->route('client.proyek.index')->with('status', 'Proyek berhasil dibuat.');
    }

    public function show(string $id)
    {
        $project = Proyek::where('user_id', auth()->id())
            ->with(['proposals' => function ($query) {
                $query->with(['user.arsitekProfile'])->orderBy('created_at', 'desc');
            }])
            ->findOrFail($id);

        return Inertia::render('Client/Proyek/Show', [
            'project' => $project
        ]);
    }

    public function edit(string $id)
    {
        $project = Proyek::where('user_id', auth()->id())->findOrFail($id);
        
        return Inertia::render('Client/Proyek/Edit', [
            'project' => $project
        ]);
    }

    public function update(Request $request, string $id)
    {
        $project = Proyek::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'budget' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'location' => 'required|string|max:100',
            'attachment' => 'nullable|file|mimes:pdf,zip,jpg,png,doc,docx|max:10240',
        ]);

        $attachment_path = $project->attachment_path;
        if ($request->hasFile('attachment')) {
            if ($attachment_path) {
                Storage::disk('public')->delete($attachment_path);
            }
            $attachment_path = $request->file('attachment')->store('proyek/attachments', 'public');
        }

        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            'budget' => $request->budget,
            'category' => $request->category,
            'location' => $request->location,
            'attachment_path' => $attachment_path,
        ]);

        return redirect()->route('client.proyek.index')->with('status', 'Proyek berhasil diperbarui.');
    }

    public function tutup(string $id)
    {
        $project = Proyek::where('user_id', auth()->id())->findOrFail($id);
        
        $project->update([
            'status' => 'ditutup'
        ]);

        return back()->with('status', 'Proyek berhasil ditutup.');
    }

    public function destroy(string $id)
    {
        $project = Proyek::where('user_id', auth()->id())->findOrFail($id);
        
        if ($project->attachment_path) {
            Storage::disk('public')->delete($project->attachment_path);
        }
        
        $project->delete();

        return redirect()->route('client.proyek.index')->with('status', 'Proyek berhasil dihapus.');
    }
}
