<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArsitekProfile;
use App\Models\ClientProfile;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileManagementController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type ?: 'company';
        
        $data = match($type) {
            'company' => CompanyProfile::with('user')->latest()->paginate(10),
            'arsitek' => ArsitekProfile::with('user')->latest()->paginate(10),
            'client' => ClientProfile::with('user')->latest()->paginate(10),
        };

        return Inertia::render('Admin/Profiles/Index', [
            'profiles' => $data,
            'filters' => ['type' => $type],
        ]);
    }

    public function verify(Request $request, $type, $id)
    {
        $profile = match($type) {
            'company' => CompanyProfile::findOrFail($id),
            'arsitek' => ArsitekProfile::findOrFail($id),
            default => abort(404),
        };

        $profile->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
            'verification_note' => $request->note
        ]);

        return back()->with('message', 'Profil berhasil diverifikasi.');
    }

    public function reject(Request $request, $type, $id)
    {
        $profile = match($type) {
            'company' => CompanyProfile::findOrFail($id),
            'arsitek' => ArsitekProfile::findOrFail($id),
            default => abort(404),
        };

        $profile->update([
            'verification_status' => 'rejected',
            'verification_note' => $request->note
        ]);

        return back()->with('message', 'Profil berhasil ditolak.');
    }
}
