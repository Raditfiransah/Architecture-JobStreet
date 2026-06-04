<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ProposalController extends Controller
{
    public function index(string $id)
    {
        return redirect()->route('client.proyek.show', $id);
    }

    public function show(string $id, string $propId)
    {
        $project = Proyek::where('user_id', auth()->id())->findOrFail($id);
        
        $proposal = Proposal::where('proyek_id', $id)
            ->with(['user.arsitekProfile'])
            ->findOrFail($propId);

        return Inertia::render('Client/Proyek/ProposalShow', [
            'project' => $project,
            'proposal' => $proposal
        ]);
    }

    public function terima(string $propId)
    {
        $proposal = Proposal::with('proyek')->findOrFail($propId);

        if ($proposal->proyek->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($proposal->proyek->status !== 'aktif') {
            return redirect()->route('client.proyek.show', $proposal->proyek_id)
                ->with('error', 'Proyek ini sudah ditutup.');
        }

        if ($proposal->status !== 'pending') {
            return redirect()->route('client.proyek.show', $proposal->proyek_id)
                ->with('error', 'Hanya proposal pending yang dapat diterima.');
        }

        DB::transaction(function () use ($proposal) {
            // Update this proposal status to diterima
            $proposal->update([
                'status' => 'diterima'
            ]);

            // Update other proposals for this project to ditolak
            Proposal::where('proyek_id', $proposal->proyek_id)
                ->where('id', '!=', $proposal->id)
                ->update([
                    'status' => 'ditolak'
                ]);

            // Close the project
            $proposal->proyek->update([
                'status' => 'ditutup'
            ]);
        });

        return redirect()->route('client.proyek.show', $proposal->proyek_id)
            ->with('status', 'Proposal berhasil diterima. Arsitek telah dipilih dan proyek telah ditutup.');
    }

    public function tolak(string $propId)
    {
        $proposal = Proposal::with('proyek')->findOrFail($propId);

        if ($proposal->proyek->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($proposal->proyek->status !== 'aktif') {
            return redirect()->route('client.proyek.show', $proposal->proyek_id)
                ->with('error', 'Proyek ini sudah ditutup.');
        }

        if ($proposal->status !== 'pending') {
            return redirect()->route('client.proyek.show', $proposal->proyek_id)
                ->with('error', 'Hanya proposal pending yang dapat ditolak.');
        }

        $proposal->update([
            'status' => 'ditolak'
        ]);

        return redirect()->route('client.proyek.show', $proposal->proyek_id)
            ->with('status', 'Proposal berhasil ditolak.');
    }

    public function compare(Request $request, string $id)
    {
        $project = Proyek::where('user_id', auth()->id())->findOrFail($id);

        $idsString = $request->query('ids');
        if (!$idsString) {
            return redirect()->route('client.proyek.show', $id)
                ->with('error', 'Silakan pilih proposal untuk dibandingkan.');
        }

        $ids = explode(',', $idsString);

        $proposals = Proposal::where('proyek_id', $id)
            ->whereIn('id', $ids)
            ->with(['user.arsitekProfile'])
            ->get();

        return Inertia::render('Client/Proyek/Compare', [
            'project' => $project,
            'proposals' => $proposals
        ]);
    }
}
