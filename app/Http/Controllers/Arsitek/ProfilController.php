<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function edit()
    {
        return view('arsitek.profil.edit');
    }

    public function update(Request $request)
    {
        // TODO: Implement profil update
        return back()->with('status', 'Profil berhasil diperbarui.');
    }

    public function preview()
    {
        return view('arsitek.profil.preview');
    }

    public function updateAvatar(Request $request)
    {
        // TODO: Implement avatar upload
        return back()->with('status', 'Avatar berhasil diperbarui.');
    }
}
