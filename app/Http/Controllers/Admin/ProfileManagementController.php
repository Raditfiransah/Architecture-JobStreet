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
    public function show($type, $id)
    {
        $profile = match($type) {
            'company' => CompanyProfile::with('user')->findOrFail($id),
            'arsitek' => ArsitekProfile::with('user')->findOrFail($id),
            'client'  => ClientProfile::with('user')->findOrFail($id),
            default   => abort(404),
        };

        return Inertia::render('Admin/Profiles/Show', [
            'profile' => $profile,
            'type'    => $type,
        ]);
    }

    public function index(Request $request)
    {
        $type = $request->type ?: 'company';
        
        $data = match($type) {
            'company' => CompanyProfile::with('user')->latest()->paginate(10),
            'arsitek' => ArsitekProfile::with('user')->latest()->paginate(10),
            'client' => ClientProfile::with('user')->latest()->paginate(10),
        };

        $pendingSubmissions = collect()
            ->concat(CompanyProfile::with('user')->where('verification_status', 'pending')->get()->each(fn($p) => $p->type = 'company'))
            ->concat(ArsitekProfile::with('user')->where('verification_status', 'pending')->get()->each(fn($p) => $p->type = 'arsitek'))
            ->concat(ClientProfile::with('user')->where('verification_status', 'pending')->get()->each(fn($p) => $p->type = 'client'))
            ->sortByDesc('updated_at')
            ->values();

        return Inertia::render('Admin/Profiles/Index', [
            'profiles' => $data,
            'pendingSubmissions' => $pendingSubmissions,
            'filters' => ['type' => $type],
        ]);
    }

    public function verify(Request $request, $type, $id)
    {
        $profile = match($type) {
            'company' => CompanyProfile::findOrFail($id),
            'arsitek' => ArsitekProfile::findOrFail($id),
            'client' => ClientProfile::findOrFail($id),
            default => abort(404),
        };

        $profile->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
            'verification_note' => $request->note
        ]);

        // Audit Log
        \App\Models\AuditLog::create([
            'admin_id' => auth()->id(),
            'action' => 'verify_profile',
            'user_id' => $profile->user_id,
            'details' => json_encode([
                'profile_type' => $type,
                'profile_id' => $profile->id,
                'verification_note' => $request->note,
            ]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('message', 'Profil berhasil diverifikasi.');
    }

    public function reject(Request $request, $type, $id)
    {
        $request->validate([
            'note' => 'required|string|min:5'
        ]);

        $profile = match($type) {
            'company' => CompanyProfile::findOrFail($id),
            'arsitek' => ArsitekProfile::findOrFail($id),
            'client' => ClientProfile::findOrFail($id),
            default => abort(404),
        };

        $profile->update([
            'verification_status' => 'rejected',
            'verification_note' => $request->note
        ]);

        // Audit Log
        \App\Models\AuditLog::create([
            'admin_id' => auth()->id(),
            'action' => 'reject_profile',
            'user_id' => $profile->user_id,
            'details' => json_encode([
                'profile_type' => $type,
                'profile_id' => $profile->id,
                'verification_note' => $request->note,
            ]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('message', 'Profil berhasil ditolak.');
    }
}
