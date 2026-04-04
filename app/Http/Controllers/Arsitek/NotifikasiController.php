<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        return view('arsitek.notifikasi.index');
    }

    public function markAllRead()
    {
        // TODO: Implement - tandai semua notifikasi terbaca
        return back()->with('status', 'Semua notifikasi telah dibaca.');
    }
}
