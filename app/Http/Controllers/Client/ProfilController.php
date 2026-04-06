<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'location' => 'nullable|string|max:200',
            'phone' => 'nullable|string|max:20',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->update([
            'name' => $request->name,
            'location' => $request->location,
            'phone' => $request->phone,
        ]);

        return back()->with('message', 'Profil berhasil diperbarui.');
    }
}
