<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function index(string $id)
    {
        return view('client.proposal.index', compact('id'));
    }

    public function show(string $id, string $propId)
    {
        return view('client.proposal.show', compact('id', 'propId'));
    }

    public function terima(string $propId)
    {
        // TODO: Implement - terima proposal
        return back()->with('status', 'Proposal berhasil diterima.');
    }

    public function tolak(string $propId)
    {
        // TODO: Implement - tolak proposal
        return back()->with('status', 'Proposal berhasil ditolak.');
    }
}
