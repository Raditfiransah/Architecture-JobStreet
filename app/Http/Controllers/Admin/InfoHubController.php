<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InfoHubController extends Controller
{
    public function index()
    {
        return view('admin.info.index');
    }

    public function setujui(string $id)
    {
        // TODO: Implement - setujui artikel
        return back()->with('status', 'Artikel berhasil disetujui.');
    }

    public function tolak(Request $request, string $id)
    {
        // TODO: Implement - tolak artikel
        return back()->with('status', 'Artikel berhasil ditolak.');
    }
}
