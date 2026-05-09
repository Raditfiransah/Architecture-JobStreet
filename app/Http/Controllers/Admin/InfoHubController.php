<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\InfoHub;
use App\Http\Requests\StoreInfoHubRequest;

class InfoHubController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/InfoHub/Index', [
            'infohubs' => InfoHub::latest()->paginate(12)
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/InfoHub/Create');
    }

    public function store(StoreInfoHubRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('gambar_poster')) {
            $data['gambar_poster'] = $request->file('gambar_poster')->store('infohub', 'public');
        }
        
        $data['admin_id'] = auth()->id();
        
        InfoHub::create($data);
        
        return redirect()->route('admin.info.index')->with('success', 'InfoHub berhasil ditambahkan.');
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
}
