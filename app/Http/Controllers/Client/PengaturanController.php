<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('client.pengaturan.index');
    }

    public function updatePassword(Request $request)
    {
        // TODO: Implement
        return back()->with('status', 'Password berhasil diperbarui.');
    }
}
