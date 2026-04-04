<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function edit()
    {
        return view('perusahaan.profil.edit');
    }

    public function update(Request $request)
    {
        // TODO: Implement
        return back()->with('status', 'Profil perusahaan berhasil diperbarui.');
    }
}
