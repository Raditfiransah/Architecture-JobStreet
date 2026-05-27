<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ProposalController extends Controller
{
    public function index()
    {
        $proposals = Proposal::where('user_id', auth()->id())
            ->with(['proyek.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Arsitek/Proposal/Index', [
            'proposals' => $proposals
        ]);
    }

    public function show(string $id)
    {
        $proposal = Proposal::where('user_id', auth()->id())
            ->with(['proyek.user'])
            ->findOrFail($id);

        return Inertia::render('Arsitek/Proposal/Show', [
            'proposal' => $proposal
        ]);
    }

    public function store(Request $request, string $id)
    {
        // Check if project exists and is active
        $project = Proyek::findOrFail($id);
        if ($project->status !== 'aktif') {
            return back()->with('error', 'Proyek ini sudah ditutup untuk proposal baru.');
        }

        // Check if already bid
        $existing = Proposal::where('user_id', auth()->id())
            ->where('proyek_id', $id)
            ->first();
            
        if ($existing) {
            return back()->with('error', 'Anda sudah mengirimkan proposal untuk proyek ini.');
        }

        $request->validate([
            'bid_amount' => 'required|numeric|min:0',
            'estimated_time' => 'required|integer|min:1', // hari
            'description' => 'required|string', // pitch
            'attachment' => 'nullable|file|mimes:pdf,zip,jpg,png,doc,docx|max:10240', // 10MB
        ]);

        $attachment_path = null;
        if ($request->hasFile('attachment')) {
            $attachment_path = $request->file('attachment')->store('proposal/attachments', 'public');
        }

        Proposal::create([
            'user_id' => auth()->id(),
            'proyek_id' => $id,
            'bid_amount' => $request->bid_amount,
            'estimated_time' => $request->estimated_time,
            'description' => $request->description,
            'attachment_path' => $attachment_path,
            'status' => 'pending',
        ]);

        return redirect()->route('arsitek.proposal.index')->with('status', 'Proposal berhasil dikirim.');
    }

    public function update(Request $request, string $id)
    {
        $proposal = Proposal::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $request->validate([
            'bid_amount' => 'required|numeric|min:0',
            'estimated_time' => 'required|integer|min:1',
            'description' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,zip,jpg,png,doc,docx|max:10240',
        ]);

        $attachment_path = $proposal->attachment_path;
        if ($request->hasFile('attachment')) {
            if ($attachment_path) {
                Storage::disk('public')->delete($attachment_path);
            }
            $attachment_path = $request->file('attachment')->store('proposal/attachments', 'public');
        }

        $proposal->update([
            'bid_amount' => $request->bid_amount,
            'estimated_time' => $request->estimated_time,
            'description' => $request->description,
            'attachment_path' => $attachment_path,
        ]);

        return back()->with('status', 'Proposal berhasil diperbarui.');
    }
}
