<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index()
    {
        return view('client.inbox.index');
    }

    public function show(string $thread)
    {
        return view('client.inbox.show', compact('thread'));
    }

    public function reply(Request $request, string $thread)
    {
        // TODO: Implement
        return back()->with('status', 'Pesan berhasil dikirim.');
    }
}
