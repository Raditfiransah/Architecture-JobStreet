<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function tindak(Request $request, string $id)
    {
        // TODO: Implement - tindak laporan konten
        return back()->with('status', 'Laporan berhasil ditindaklanjuti.');
    }
}
