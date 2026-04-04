<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index()
    {
        return view('perusahaan.inbox.index');
    }

    public function show(string $thread)
    {
        return view('perusahaan.inbox.show', compact('thread'));
    }

    public function reply(Request $request, string $thread)
    {
        // TODO: Implement
        return back()->with('status', 'Pesan berhasil dikirim.');
    }
}
