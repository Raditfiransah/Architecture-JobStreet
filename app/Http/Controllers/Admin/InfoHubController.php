<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\InfoHub;
use App\Http\Requests\StoreInfoHubRequest;
use App\Http\Requests\UpdateInfoHubRequest;

class InfoHubController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/InfoHub/Index', [
            'infohubs' => InfoHub::query()
                ->with('admin:id,name')
                ->latest()
                ->paginate(12)
                ->through(fn (InfoHub $infoHub): array => $this->serializeInfoHub($infoHub)),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/InfoHub/Create', [
            'categories' => ['Event', 'Sayembara', 'Magang'],
        ]);
    }

    public function store(StoreInfoHubRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['gambar_poster'] = $request->file('gambar_poster')->store('info-hub', 'public');
        $data['admin_id'] = auth()->id();

        InfoHub::create($data);

        return redirect()
            ->route('admin.info.index')
            ->with('success', 'Postingan mading berhasil dipublikasikan.');
    }

    public function edit(InfoHub $infoHub): Response
    {
        return Inertia::render('Admin/InfoHub/Edit', [
            'categories' => ['Event', 'Sayembara', 'Magang'],
            'infoHub' => $this->serializeInfoHub($infoHub),
        ]);
    }

    public function update(UpdateInfoHubRequest $request, InfoHub $infoHub): RedirectResponse
    {
        $data = $request->safe()->except('gambar_poster');

        if ($request->hasFile('gambar_poster')) {
            if ($infoHub->gambar_poster) {
                Storage::disk('public')->delete($infoHub->gambar_poster);
            }

            $data['gambar_poster'] = $request->file('gambar_poster')->store('info-hub', 'public');
        }

        $infoHub->update($data);

        return redirect()
            ->route('admin.info.index')
            ->with('success', 'Postingan mading berhasil diperbarui.');
    }

    public function destroy(InfoHub $infoHub): RedirectResponse
    {
        if ($infoHub->gambar_poster) {
            Storage::disk('public')->delete($infoHub->gambar_poster);
        }

        $infoHub->delete();

        return redirect()
            ->route('admin.info.index')
            ->with('success', 'Postingan mading berhasil dihapus.');
    }

    public function setujui(string $id)
    {
        // TODO: Implement - setujui artikel
        return back()->with('status', 'Artikel berhasil disetujui.');
    }

    public function tolak(Request $request, string $id)
    {
        // TODO: Implement - tolak artikel
        return back()->with('status', 'Artikel berhasil ditolak.');
    }

    private function serializeInfoHub(InfoHub $infoHub): array
    {
        return [
            'id' => $infoHub->id,
            'admin_name' => $infoHub->admin?->name,
            'judul' => $infoHub->judul,
            'kategori' => $infoHub->kategori,
            'deskripsi' => $infoHub->deskripsi,
            'gambar_poster' => $infoHub->gambar_poster,
            'image_url' => $infoHub->gambar_poster ? Storage::url($infoHub->gambar_poster) : null,
            'created_at' => $infoHub->created_at?->toIso8601String(),
        ];
    }
}
