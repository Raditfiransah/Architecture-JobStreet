<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Lowongan::query()->with(['user'])->withCount('lamarans');

        if ($request->search) {
            $query->where('posisi', 'like', "%{$request->search}%")
                  ->orWhere('perusahaan', 'like', "%{$request->search}%");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $lowongans = $query->latest()->paginate(10)->withQueryString();

        return \Inertia\Inertia::render('Admin/Lowongan/Index', [
            'lowongans' => $lowongans,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function show(\App\Models\Lowongan $lowongan)
    {
        $lowongan->load(['user', 'lamarans.user']);
        return \Inertia\Inertia::render('Admin/Lowongan/Show', [
            'lowongan' => $lowongan
        ]);
    }

    public function setujui(\App\Models\Lowongan $lowongan)
    {
        $lowongan->update(['status' => 'aktif']);
        return back()->with('message', 'Lowongan berhasil disetujui.');
    }

    public function tolak(Request $request, \App\Models\Lowongan $lowongan)
    {
        $lowongan->update(['status' => 'ditolak']);
        return back()->with('message', 'Lowongan berhasil ditolak.');
    }

    public function tutup(\App\Models\Lowongan $lowongan)
    {
        $lowongan->update(['status' => 'nonaktif']);
        return back()->with('message', 'Lowongan berhasil ditutup.');
    }
}
