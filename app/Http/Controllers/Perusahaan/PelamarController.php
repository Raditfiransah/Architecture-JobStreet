<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PelamarController extends Controller
{
    public function all()
    {
        $lowongans = auth()->user()->lowongans()->with(['lamarans.user', 'lamarans.lowongan'])->get();
        
        $allLamarans = $lowongans->flatMap->lamarans->sortByDesc('applied_at');

        return \Inertia\Inertia::render('Perusahaan/Pelamar/All', [
            'lamarans' => $allLamarans
        ]);
    }

    public function index(string $id)
    {
        $lowongan = auth()->user()->lowongans()->with('lamarans.user')->findOrFail($id);

        return \Inertia\Inertia::render('Perusahaan/Pelamar/Index', [
            'lowongan' => $lowongan,
            'lamarans' => $lowongan->lamarans
        ]);
    }

    public function show(string $id, string $appId)
    {
        $lowongan = auth()->user()->lowongans()->findOrFail($id);
        $lamaran = \App\Models\Lamaran::with(['user.arsitekProfile', 'lowongan'])
            ->where('lowongan_id', $id)
            ->findOrFail($appId);

        return \Inertia\Inertia::render('Perusahaan/Pelamar/Show', [
            'lamaran' => $lamaran
        ]);
    }

    public function updateStatus(Request $request, string $appId)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewing,shortlisted,interview,rejected,accepted'
        ]);

        $lamaran = \App\Models\Lamaran::whereHas('lowongan', function($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($appId);

        $lamaran->update(['status' => $validated['status']]);

        return back()->with('success', 'Status lamaran berhasil diperbarui.');
    }

    public function shortlist(string $appId)
    {
        $lamaran = \App\Models\Lamaran::whereHas('lowongan', function($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($appId);

        $lamaran->update(['status' => 'shortlisted']);

        return back()->with('success', 'Pelamar berhasil di-shortlist.');
    }
}
