<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    public function index()
    {
        return view('admin.lowongan.index');
    }

    public function show(string $id)
    {
        return view('admin.lowongan.show', compact('id'));
    }

    public function setujui(string $id)
    {
        // TODO: Implement - setujui lowongan
        return back()->with('status', 'Lowongan berhasil disetujui.');
    }

    public function tolak(Request $request, string $id)
    {
        // TODO: Implement - tolak lowongan
        return back()->with('status', 'Lowongan berhasil ditolak.');
    }

    public function tutup(string $id)
    {
        // TODO: Implement - tutup lowongan
        return back()->with('status', 'Lowongan berhasil ditutup.');
    }
}
