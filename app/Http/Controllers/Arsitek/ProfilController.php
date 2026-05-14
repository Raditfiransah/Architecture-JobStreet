<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;
use App\Http\Requests\Arsitek\UpdateProfilRequest;
use App\Models\ArsitekProfile;
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
        $user->load('arsitekProfile');
        
        return Inertia::render('Profile/EditArsitek', [
            'user' => $user,
            'arsitekProfile' => $user->arsitekProfile,
        ]);
    }

    public function update(UpdateProfilRequest $request)
    {
        DB::transaction(function () use ($request) {
            /** @var \App\Models\User $user */
            $user = $request->user();
            
            // Update User table for generic columns
            $user->update([
                'name' => trim($request->first_name . ' ' . $request->last_name),
                'location' => $request->location,
            ]);

            // Update Profile table
            ArsitekProfile::updateOrCreate(
                ['user_id' => $user->id],
                $request->validated()
            );
        });

        return back()->with('message', 'Profil berhasil diperbarui.');
    }

    public function preview(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->load('arsitekProfile');
        
        return Inertia::render('Public/Arsitek/Show', [
            'arsitek' => $user,
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:51200',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();
        
        $url = $this->fileUploadService->uploadAvatar(
            $request->file('avatar'), 
            'avatars', 
            $user->avatar_url
        );

        $user->update(['avatar_url' => $url]);

        return back()->with('message', 'Foto profil berhasil diperbarui.');
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'type' => 'required|in:identity,license',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();
        $profile = $user->arsitekProfile;
        
        $type = $request->input('type');
        $oldFile = $type === 'identity' ? $profile?->identity_document_url : $profile?->license_document_url;
        
        $path = $this->fileUploadService->uploadSecureDocument(
            $request->file('document'), 
            "documents/arsitek/{$user->id}/{$type}",
            $oldFile
        );

        $data = $type === 'identity' 
            ? ['identity_document_url' => $path] 
            : ['license_document_url' => $path];

        ArsitekProfile::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return back()->with('message', 'Dokumen berhasil diunggah.');
    }
}
