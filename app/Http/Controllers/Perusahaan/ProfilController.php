<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:150',
            'industry' => 'nullable|string|max:100',
            'company_size' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:200',
            'website' => 'nullable|url|max:500',
            'description' => 'nullable|string',
        ]);

        $user = $request->user();
        
        $user->companyProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => $request->company_name,
                'industry' => $request->industry,
                'company_size' => $request->company_size,
                'location' => $request->location,
                'company_website' => $request->website,
                'company_desc' => $request->description,
            ]
        );

        return back()->with('message', 'Profil perusahaan berhasil diperbarui.');
    }
}
