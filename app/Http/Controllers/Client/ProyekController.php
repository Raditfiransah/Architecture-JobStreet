<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    public function index()
    {
        return \Inertia\Inertia::render('Client/Proyek/Index', [
            'projects' => [] // TODO: Fetch from actual model once implemented
        ]);
    }

    public function create()
    {
        return view('client.proyek.create');
    }

    public function store(Request $request)
    {
        // TODO: Implement
        return redirect()->route('client.proyek.index')->with('status', 'Proyek berhasil dibuat.');
    }

    public function show(string $id)
    {
        return view('client.proyek.show', compact('id'));
    }

    public function edit(string $id)
    {
        return view('client.proyek.edit', compact('id'));
    }

    public function update(Request $request, string $id)
    {
        // TODO: Implement
        return redirect()->route('client.proyek.index')->with('status', 'Proyek berhasil diperbarui.');
    }

    public function tutup(string $id)
    {
        // TODO: Implement
        return back()->with('status', 'Proyek berhasil ditutup.');
    }

    public function destroy(string $id)
    {
        // TODO: Implement
        return redirect()->route('client.proyek.index')->with('status', 'Proyek berhasil dihapus.');
    }
}
