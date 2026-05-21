<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LamaranController extends Controller
{
    public function index()
    {
        $lamarans = Lamaran::with(['lowongan'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return Inertia::render('Arsitek/Lamaran/Index', [
            'lamarans' => $lamarans
        ]);
    }

    public function show(string $id)
    {
        $lamaran = Lamaran::with(['lowongan'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return Inertia::render('Arsitek/Lamaran/Show', [
            'lamaran' => $lamaran
        ]);
    }

    public function store(Request $request, Lowongan $lowongan)
    {
        // This is called from the public job listing
        $request->validate([
            'notes' => 'nullable|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // Check if already applied
        $existing = Lamaran::where('user_id', Auth::id())
            ->where('lowongan_id', $lowongan->id)
            ->first();
            
        if ($existing) {
            return back()->with('error', 'Anda sudah melamar lowongan ini.');
        }

        $cv_path = null;
        if ($request->hasFile('cv')) {
            $cv_path = $request->file('cv')->store('applications/cvs', 'public');
        }

        Lamaran::create([
            'user_id' => Auth::id(),
            'lowongan_id' => $lowongan->id,
            'status' => 'pending',
            'cv_path' => $cv_path,
            'notes' => $request->notes,
        ]);

        return back()->with('status', 'Lamaran berhasil dikirim.');
    }

    public function withdraw(string $id)
    {
        $lamaran = Lamaran::where('user_id', Auth::id())->findOrFail($id);
        
        // Only allow withdraw if still pending or reviewing? 
        // For simplicity, allow any time for now but in real world might have limits.
        $lamaran->delete();

        return redirect()->route('arsitek.lamaran.index')->with('status', 'Lamaran berhasil ditarik.');
    }
}
