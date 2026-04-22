<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProfileFileUploadService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    public function __construct(
        private readonly ProfileFileUploadService $fileUploadService
    ) {}

    public function edit(Request $request)
    {
        return Inertia::render('Profile/EditAdmin', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'location' => ['required', 'string', 'in:Super Admin,Moderator,Content Manager'], // SKPL uses location as Jabatan for Admin
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with('message', 'Profil admin berhasil diperbarui.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
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
