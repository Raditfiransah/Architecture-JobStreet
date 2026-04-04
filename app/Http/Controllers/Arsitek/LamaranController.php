<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LamaranController extends Controller
{
    public function index()
    {
        return view('arsitek.lamaran.index');
    }

    public function show(string $id)
    {
        return view('arsitek.lamaran.show', compact('id'));
    }

    public function store(Request $request, string $id)
    {
        // TODO: Implement - melamar ke lowongan
        return back()->with('status', 'Lamaran berhasil dikirim.');
    }

    public function withdraw(string $id)
    {
        // TODO: Implement - tarik lamaran
        return redirect()->route('arsitek.lamaran.index')->with('status', 'Lamaran berhasil ditarik.');
    }
}
