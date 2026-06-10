<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Marketplace\StoreProyekRequest;
use App\Http\Requests\Marketplace\UpdateProyekRequest;
use App\Models\Proyek;
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
        $this->ensureProfileVerified();

        return Inertia::render('Client/Proyek/Create');
    }

    public function store(StoreProyekRequest $request)
    {
        $this->ensureProfileVerified();

        $validated = $request->validated();

        $attachment_path = null;
        if ($request->hasFile('attachment')) {
            $attachment_path = $request->file('attachment')->store('proyek/attachments', 'public');
        }

        Proyek::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'budget' => $validated['budget'],
            'category' => $validated['category'],
            'location' => $validated['location'],
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
        $this->ensureProfileVerified();

        $project = Proyek::where('user_id', auth()->id())->findOrFail($id);
        
        return Inertia::render('Client/Proyek/Edit', [
            'project' => $project
        ]);
    }

    public function update(UpdateProyekRequest $request, string $id)
    {
        $this->ensureProfileVerified();

        $project = Proyek::where('user_id', auth()->id())->findOrFail($id);

        if ($project->status !== 'aktif') {
            return back()->with('error', 'Proyek yang sudah ditutup tidak dapat diperbarui.');
        }

        $validated = $request->validated();

        $attachment_path = $project->attachment_path;
        if ($request->hasFile('attachment')) {
            if ($attachment_path) {
                Storage::disk('public')->delete($attachment_path);
            }
            $attachment_path = $request->file('attachment')->store('proyek/attachments', 'public');
        }

        $project->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'budget' => $validated['budget'],
            'category' => $validated['category'],
            'location' => $validated['location'],
            'attachment_path' => $attachment_path,
        ]);

        return redirect()->route('client.proyek.index')->with('status', 'Proyek berhasil diperbarui.');
    }

    public function tutup(string $id)
    {
        $this->ensureProfileVerified();

        $project = Proyek::where('user_id', auth()->id())->findOrFail($id);

        if ($project->status !== 'aktif') {
            return back()->with('error', 'Proyek ini sudah tidak aktif.');
        }
        
        $project->update([
            'status' => 'ditutup'
        ]);

        return back()->with('status', 'Proyek berhasil ditutup.');
    }

    public function destroy(string $id)
    {
        $this->ensureProfileVerified();

        $project = Proyek::where('user_id', auth()->id())->findOrFail($id);
        
        if ($project->attachment_path) {
            Storage::disk('public')->delete($project->attachment_path);
        }
        
        $project->delete();

        return redirect()->route('client.proyek.index')->with('status', 'Proyek berhasil dihapus.');
    }
}
