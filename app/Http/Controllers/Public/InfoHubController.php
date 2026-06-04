<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\InfoHub;
use Illuminate\Support\Facades\Storage;

class InfoHubController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/InfoHub/Index', [
            'title' => 'Arsitektur Info Hub',
            'infohubs' => InfoHub::query()
                ->latest()
                ->paginate(12)
                ->through(fn (InfoHub $infoHub): array => $this->serializeInfoHub($infoHub)),
        ]);
    }

    public function show(InfoHub $infoHub)
    {
        return Inertia::render('Public/InfoHub/Show', [
            'title' => 'Artikel: ' . $infoHub->judul,
            'infoHub' => $this->serializeInfoHub($infoHub),
        ]);
    }

    private function serializeInfoHub(InfoHub $infoHub): array
    {
        return [
            'id' => $infoHub->id,
            'judul' => $infoHub->judul,
            'kategori' => $infoHub->kategori,
            'deskripsi' => $infoHub->deskripsi,
            'gambar_poster' => $infoHub->gambar_poster,
            'image_url' => $infoHub->gambar_poster ? Storage::url($infoHub->gambar_poster) : null,
            'created_at' => $infoHub->created_at?->toIso8601String(),
            'href' => route('info.show', $infoHub),
        ];
    }
}
