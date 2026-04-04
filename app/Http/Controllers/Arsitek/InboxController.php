<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index()
    {
        return view('arsitek.inbox.index');
    }

    public function show(string $thread)
    {
        return view('arsitek.inbox.show', compact('thread'));
    }

    public function reply(Request $request, string $thread)
    {
        // TODO: Implement - kirim balasan
        return back()->with('status', 'Pesan berhasil dikirim.');
    }
}
