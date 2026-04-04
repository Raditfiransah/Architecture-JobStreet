<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function index()
    {
        return view('arsitek.proposal.index');
    }

    public function show(string $id)
    {
        return view('arsitek.proposal.show', compact('id'));
    }

    public function store(Request $request, string $id)
    {
        // TODO: Implement - kirim proposal ke proyek
        return back()->with('status', 'Proposal berhasil dikirim.');
    }

    public function update(Request $request, string $id)
    {
        // TODO: Implement - edit proposal
        return back()->with('status', 'Proposal berhasil diperbarui.');
    }
}
