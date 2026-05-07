<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileViewController extends Controller
{
    public function showArsitek(Request $request, User $user)
    {
        // Pastikan user yang diminta memang role arsitek
        abort_if($user->role !== 'arsitek', 404);

        $user->load('arsitekProfile');

        $visitor = $request->user();
        $isPublic = !$visitor;
        $isAdmin  = $visitor && $visitor->isAdmin();

        // Strip sensitive info if visitor is not logged in
        if ($isPublic) {
            $user->makeHidden(['email', 'phone', 'location']);
            if ($user->arsitekProfile) {
                $user->arsitekProfile->makeHidden(['identity_document_url', 'license_document_url']);
            }
        } elseif (!$isAdmin && $user->arsitekProfile) {
            // Hide sensitive documents from regular users too
            $user->arsitekProfile->makeHidden(['identity_document_url', 'license_document_url']);
        }

        return Inertia::render('Public/Arsitek/Show', [
            'arsitek'  => $user,
            'isPublic' => $isPublic,
            'isAdmin'  => $isAdmin,
        ]);
    }

    public function showPerusahaan(Request $request, User $user)
    {
        // Pastikan user yang diminta memang role perusahaan
        abort_if($user->role !== 'perusahaan', 404);

        $user->load('companyProfile');

        $visitor = $request->user();
        $isPublic = !$visitor;
        $isAdmin  = $visitor && $visitor->isAdmin();

        if ($isPublic) {
            $user->makeHidden(['email', 'phone']);
            if ($user->companyProfile) {
                $user->companyProfile->makeHidden(['identity_document_url']);
            }
        } elseif (!$isAdmin && $user->companyProfile) {
            $user->companyProfile->makeHidden(['identity_document_url']);
        }

        return Inertia::render('Public/Perusahaan/Show', [
            'perusahaan' => $user,
            'isPublic'   => $isPublic,
            'isAdmin'    => $isAdmin,
        ]);
    }
}
