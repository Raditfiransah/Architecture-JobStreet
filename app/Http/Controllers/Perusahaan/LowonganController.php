<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    public function index()
    {
        $lowongans = auth()->user()->lowongans()
            ->withCount('lamarans')
            ->latest()
            ->get();

        return \Inertia\Inertia::render('Perusahaan/Lowongan/Index', [
            'lowongans' => $lowongans
        ]);
    }

    public function create()
    {
        return \Inertia\Inertia::render('Perusahaan/Lowongan/Form', [
            'isEdit' => false
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'posisi' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'tipe' => 'required|in:Full Time,Part Time,Freelance,Contract,Internship',
            'gaji' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'syarat' => 'required|array',
            'tanggung_jawab' => 'required|array',
            'deadline' => 'nullable|date',
        ]);

        $companyName = auth()->user()->companyProfile->company_name ?? auth()->user()->name;
        
        // Simple initial generation
        $initials = strtoupper(substr($companyName, 0, 2));

        auth()->user()->lowongans()->create(array_merge($validated, [
            'perusahaan' => $companyName,
            'inisial' => $initials,
            'status' => 'aktif',
        ]));

        return redirect()->route('perusahaan.lowongan.index')->with('success', 'Lowongan berhasil diterbitkan.');
    }

    public function edit(string $id)
    {
        $lowongan = auth()->user()->lowongans()->findOrFail($id);

        return \Inertia\Inertia::render('Perusahaan/Lowongan/Form', [
            'lowongan' => $lowongan,
            'isEdit' => true
        ]);
    }

    public function update(Request $request, string $id)
    {
        $lowongan = auth()->user()->lowongans()->findOrFail($id);

        $validated = $request->validate([
            'posisi' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'tipe' => 'required|in:Full Time,Part Time,Freelance,Contract,Internship',
            'gaji' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'syarat' => 'required|array',
            'tanggung_jawab' => 'required|array',
            'deadline' => 'nullable|date',
        ]);

        $lowongan->update($validated);

        return redirect()->route('perusahaan.lowongan.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function tutup(string $id)
    {
        $lowongan = auth()->user()->lowongans()->findOrFail($id);
        $lowongan->update(['status' => 'ditutup']);
        return back()->with('success', 'Lowongan telah ditutup.');
    }

    public function perpanjang(string $id)
    {
        $lowongan = auth()->user()->lowongans()->findOrFail($id);
        $lowongan->update(['status' => 'aktif']);
        return back()->with('success', 'Lowongan telah diaktifkan kembali.');
    }

    public function destroy(string $id)
    {
        $lowongan = auth()->user()->lowongans()->findOrFail($id);
        $lowongan->delete();
        return redirect()->route('perusahaan.lowongan.index')->with('success', 'Lowongan berhasil dihapus.');
    }
}
