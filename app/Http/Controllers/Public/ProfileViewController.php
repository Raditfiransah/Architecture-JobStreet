<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileViewController extends Controller
{
    public function showArsitek(Request $request, string $id)
    {
        $arsitek = User::with('arsitekProfile')
            ->where('id', $id)
            ->where('role', 'arsitek')
            ->firstOrFail();

        // Control what data is sent to the frontend based on the visitor's authentication and role
        $visitor = $request->user();
        $isPublic = !$visitor;
        $isAdmin = $visitor && $visitor->isAdmin();

        // Strip sensitive info if visitor is not logged in
        if ($isPublic) {
            $arsitek->makeHidden(['email', 'phone', 'location']);
            if ($arsitek->arsitekProfile) {
                $arsitek->arsitekProfile->makeHidden(['identity_document_url', 'license_document_url']);
            }
        } elseif (!$isAdmin && $arsitek->arsitekProfile) {
            // Hide sensitive documents from regular users too
            $arsitek->arsitekProfile->makeHidden(['identity_document_url', 'license_document_url']);
        }

        return Inertia::render('Public/Arsitek/Show', [
            'arsitek' => $arsitek,
            'isPublic' => $isPublic,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function showPerusahaan(Request $request, string $id)
    {
        $perusahaan = User::with('companyProfile')
            ->where('id', $id)
            ->where('role', 'perusahaan')
            ->firstOrFail();

        $visitor = $request->user();
        $isPublic = !$visitor;
        $isAdmin = $visitor && $visitor->isAdmin();

        if ($isPublic) {
            $perusahaan->makeHidden(['email', 'phone']);
            if ($perusahaan->companyProfile) {
                $perusahaan->companyProfile->makeHidden(['identity_document_url']);
            }
        } elseif (!$isAdmin && $perusahaan->companyProfile) {
            $perusahaan->companyProfile->makeHidden(['identity_document_url']);
        }

        return Inertia::render('Public/Perusahaan/Show', [
            'perusahaan' => $perusahaan,
            'isPublic' => $isPublic,
            'isAdmin' => $isAdmin,
        ]);
    }
}
