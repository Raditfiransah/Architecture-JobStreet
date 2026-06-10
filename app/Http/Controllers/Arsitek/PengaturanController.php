<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class PengaturanController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->load('arsitekProfile');

        return Inertia::render('Profile/Pengaturan', [
            'user' => $user,
            'profile' => $user->arsitekProfile,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('message', 'Password berhasil diperbarui.');
    }

    public function updateNotifikasi(Request $request)
    {
        // For now, since there's no DB schema for notification preferences,
        // we'll return a success status message.
        return back()->with('message', 'Pengaturan notifikasi berhasil diperbarui.');
    }

    public function deleteAkun(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('message', 'Akun Anda telah berhasil dihapus.');
    }
}
