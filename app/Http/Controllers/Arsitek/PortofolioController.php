<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PortofolioController extends Controller
{
    public function index()
    {
        return view('arsitek.portofolio.index');
    }

    public function create()
    {
        return view('arsitek.portofolio.create');
    }

    public function store(Request $request)
    {
        // TODO: Implement
        return redirect()->route('arsitek.portofolio.index')->with('status', 'Portofolio berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        return view('arsitek.portofolio.edit', compact('id'));
    }

    public function update(Request $request, string $id)
    {
        // TODO: Implement
        return redirect()->route('arsitek.portofolio.index')->with('status', 'Portofolio berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        // TODO: Implement
        return redirect()->route('arsitek.portofolio.index')->with('status', 'Portofolio berhasil dihapus.');
    }

    public function reorder(Request $request)
    {
        // TODO: Implement drag-and-drop reorder
        return response()->json(['status' => 'ok']);
    }
}
