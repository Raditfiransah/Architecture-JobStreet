<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Perusahaan\UpdateProfilRequest;
use App\Models\CompanyProfile;
use App\Services\ProfileFileUploadService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    public function __construct(
        private readonly ProfileFileUploadService $fileUploadService
    ) {}

    public function edit(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->load('companyProfile');
        
        return Inertia::render('Profile/EditPerusahaan', [
            'user' => $user,
            'companyProfile' => $user->companyProfile,
        ]);
    }

    public function update(UpdateProfilRequest $request)
    {
        DB::transaction(function () use ($request) {
            /** @var \App\Models\User $user */
            $user = $request->user();
            
            // Update User table for generic columns
            $user->update([
                'name' => $request->company_name,
                'location' => $request->location,
                'phone' => $request->phone,
            ]);

            // Update Profile table
            CompanyProfile::updateOrCreate(
                ['user_id' => $user->id],
                $request->validated()
            );
        });

        return back()->with('message', 'Profil perusahaan berhasil diperbarui.');
    }
    
    public function updateLogo(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:51200',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();
        $profile = $user->companyProfile;
        
        $url = $this->fileUploadService->uploadAvatar(
            $request->file('avatar'), 
            'logos', 
            $profile?->company_logo_url
        );

        CompanyProfile::updateOrCreate(
            ['user_id' => $user->id],
            ['company_logo_url' => $url]
        );
        
        $user->update(['avatar_url' => $url]);

        return back()->with('message', 'Logo perusahaan berhasil diperbarui.');
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf|max:5120',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();
        $profile = $user->companyProfile;
        
        $path = $this->fileUploadService->uploadSecureDocument(
            $request->file('document'), 
            "documents/perusahaan/{$user->id}/identity",
            $profile?->identity_document_url
        );

        CompanyProfile::updateOrCreate(
            ['user_id' => $user->id],
            ['identity_document_url' => $path]
        );

        return back()->with('message', 'Dokumen identitas berhasil diunggah.');
    }
}
