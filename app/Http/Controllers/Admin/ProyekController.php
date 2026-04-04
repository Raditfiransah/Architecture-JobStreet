<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    public function index()
    {
        return view('admin.proyek.index');
    }

    public function show(string $id)
    {
        return view('admin.proyek.show', compact('id'));
    }

    public function setujui(string $id)
    {
        // TODO: Implement - setujui proyek
        return back()->with('status', 'Proyek berhasil disetujui.');
    }

    public function tolak(Request $request, string $id)
    {
        // TODO: Implement - tolak proyek
        return back()->with('status', 'Proyek berhasil ditolak.');
    }
}
