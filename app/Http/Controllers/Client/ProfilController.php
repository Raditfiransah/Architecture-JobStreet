<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\UpdateProfilRequest;
use App\Models\ClientProfile;
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
        $user->load('clientProfile');
        
        return Inertia::render('Profile/EditClient', [
            'user' => $user,
            'clientProfile' => $user->clientProfile,
        ]);
    }

    public function update(UpdateProfilRequest $request)
    {
        DB::transaction(function () use ($request) {
            /** @var \App\Models\User $user */
            $user = $request->user();
            
            // Update User table for generic columns
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'location' => $request->address,
                'phone' => $request->phone,
            ]);

            // Update Profile table
            ClientProfile::updateOrCreate(
                ['user_id' => $user->id],
                $request->validated()
            );
        });

        return back()->with('message', 'Profil client berhasil diperbarui.');
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
}
