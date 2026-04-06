<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ArsitekProfile;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user()->load('arsitekProfile');
        
        return Inertia::render('Profile/Edit', [
            'user' => $user,
            'arsitekProfile' => $user->arsitekProfile,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'status_pekerjaan' => 'nullable|string|max:100',
            'is_student' => 'boolean',
            'location' => 'nullable|string|max:200',
            'school' => 'nullable|string|max:200',
            'degree_type' => 'nullable|string|max:100',
            'preferences' => 'nullable|array',
        ]);

        $user = $request->user();
        
        ArsitekProfile::updateOrCreate(
            ['user_id' => $user->id],
            $request->only([
                'first_name',
                'last_name',
                'status_pekerjaan',
                'is_student',
                'location',
                'school',
                'degree_type',
                'preferences',
            ])
        );

        return back()->with('message', 'Profil berhasil diperbarui.');
    }

    public function preview(Request $request)
    {
        $user = $request->user()->load('arsitekProfile');
        
        return Inertia::render('Dashboard/Arsitek/ProfilPreview', [
            'player' => $user, // Using 'player' or 'user' as per project convention if exists
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);

        $user = $request->user();
        
        if ($user->avatar_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar_url));
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar_url' => Storage::url($path)]);

        return back()->with('message', 'Avatar berhasil diperbarui.');
    }
}
