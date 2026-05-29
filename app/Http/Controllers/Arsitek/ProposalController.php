<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;
use App\Http\Requests\Marketplace\StoreProposalRequest;
use App\Http\Requests\Marketplace\UpdateProposalRequest;
use App\Models\Proposal;
use App\Models\Proyek;
use Illuminate\Database\QueryException;
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

    public function store(StoreProposalRequest $request, string $id)
    {
        // Check if project exists and is active
        $project = Proyek::findOrFail($id);
        if ($project->status !== 'aktif') {
            return back()->with('error', 'Proyek ini sudah ditutup untuk proposal baru.');
        }

        if ((int) $project->user_id === (int) auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mengirim proposal ke proyek sendiri.');
        }

        // Check if already bid
        $existing = Proposal::where('user_id', auth()->id())
            ->where('proyek_id', $id)
            ->first();
            
        if ($existing) {
            return back()->with('error', 'Anda sudah mengirimkan proposal untuk proyek ini.');
        }

        $validated = $request->validated();

        $attachment_path = null;
        if ($request->hasFile('attachment')) {
            $attachment_path = $request->file('attachment')->store('proposal/attachments', 'public');
        }

        try {
            Proposal::create([
                'user_id' => auth()->id(),
                'proyek_id' => $id,
                'bid_amount' => $validated['bid_amount'],
                'estimated_time' => $validated['estimated_time'],
                'description' => $validated['description'],
                'attachment_path' => $attachment_path,
                'status' => 'pending',
            ]);
        } catch (QueryException $exception) {
            if ($attachment_path) {
                Storage::disk('public')->delete($attachment_path);
            }

            return back()->with('error', 'Anda sudah mengirimkan proposal untuk proyek ini.');
        }

        return redirect()->route('arsitek.proposal.index')->with('status', 'Proposal berhasil dikirim.');
    }

    public function update(UpdateProposalRequest $request, string $id)
    {
        $proposal = Proposal::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $validated = $request->validated();

        $attachment_path = $proposal->attachment_path;
        if ($request->hasFile('attachment')) {
            if ($attachment_path) {
                Storage::disk('public')->delete($attachment_path);
            }
            $attachment_path = $request->file('attachment')->store('proposal/attachments', 'public');
        }

        $proposal->update([
            'bid_amount' => $validated['bid_amount'],
            'estimated_time' => $validated['estimated_time'],
            'description' => $validated['description'],
            'attachment_path' => $attachment_path,
        ]);

        return back()->with('status', 'Proposal berhasil diperbarui.');
    }
}
