<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PelamarController extends Controller
{
    public function index(string $id)
    {
        return view('perusahaan.pelamar.index', compact('id'));
    }

    public function show(string $id, string $appId)
    {
        return view('perusahaan.pelamar.show', compact('id', 'appId'));
    }

    public function updateStatus(Request $request, string $appId)
    {
        // TODO: Implement - update status lamaran
        return back()->with('status', 'Status lamaran berhasil diperbarui.');
    }

    public function shortlist(string $appId)
    {
        // TODO: Implement - shortlist pelamar
        return back()->with('status', 'Pelamar berhasil di-shortlist.');
    }
}
