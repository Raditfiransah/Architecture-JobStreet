<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    public function index()
    {
        return view('perusahaan.lowongan.index');
    }

    public function create()
    {
        return view('perusahaan.lowongan.create');
    }

    public function store(Request $request)
    {
        // TODO: Implement
        return redirect()->route('perusahaan.lowongan.index')->with('status', 'Lowongan berhasil dibuat.');
    }

    public function edit(string $id)
    {
        return view('perusahaan.lowongan.edit', compact('id'));
    }

    public function update(Request $request, string $id)
    {
        // TODO: Implement
        return redirect()->route('perusahaan.lowongan.index')->with('status', 'Lowongan berhasil diperbarui.');
    }

    public function tutup(string $id)
    {
        // TODO: Implement - tutup lowongan
        return back()->with('status', 'Lowongan berhasil ditutup.');
    }

    public function perpanjang(string $id)
    {
        // TODO: Implement - perpanjang lowongan
        return back()->with('status', 'Lowongan berhasil diperpanjang.');
    }

    public function destroy(string $id)
    {
        // TODO: Implement
        return redirect()->route('perusahaan.lowongan.index')->with('status', 'Lowongan berhasil dihapus.');
    }
}
