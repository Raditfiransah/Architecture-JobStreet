<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('arsitek.pengaturan.index');
    }

    public function updatePassword(Request $request)
    {
        // TODO: Implement
        return back()->with('status', 'Password berhasil diperbarui.');
    }

    public function updateNotifikasi(Request $request)
    {
        // TODO: Implement
        return back()->with('status', 'Pengaturan notifikasi berhasil diperbarui.');
    }

    public function deleteAkun(Request $request)
    {
        // TODO: Implement - hapus akun
        return redirect()->route('home');
    }
}
