<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ArsitekProfile;
use App\Models\CompanyProfile;
use App\Models\ClientProfile;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function submitArsitek(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $profile = $user->arsitekProfile;
        
        $hasExistingIdentity = $profile && $profile->identity_document_url;
        $hasExistingLicense = $profile && $profile->license_document_url;

        $request->validate([
            'phone' => [
                'required', 'string', 'max:20',
                \Illuminate\Validation\Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'identity_document' => ($hasExistingIdentity ? 'nullable' : 'required') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'license_document' => ($hasExistingLicense ? 'nullable' : 'required') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'phone.unique' => 'Nomor telepon ini sudah digunakan oleh akun lain.',
        ]);

        $user->update(['phone' => $request->phone]);

        $identityPath = $profile ? $profile->identity_document_url : null;
        if ($request->hasFile('identity_document')) {
            $path = $request->file('identity_document')->store('verification/arsitek/identity', 'public');
            $identityPath = '/storage/' . $path;
        }

        $licensePath = $profile ? $profile->license_document_url : null;
        if ($request->hasFile('license_document')) {
            $path = $request->file('license_document')->store('verification/arsitek/license', 'public');
            $licensePath = '/storage/' . $path;
        }

        ArsitekProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'identity_document_url' => $identityPath,
                'license_document_url' => $licensePath,
                'verification_status' => 'pending',
                'verification_note' => null,
            ]
        );

        return back()->with('message', 'Pengajuan verifikasi berhasil dikirim!');
    }

    public function submitPerusahaan(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $profile = $user->companyProfile;
        
        $hasIdentity = $profile && $profile->identity_document_url;
        $hasNpwp = $profile && $profile->npwp_document_url;
        $hasAkta = $profile && $profile->akta_document_url;
        $hasSiup = $profile && $profile->siup_document_url;

        $request->validate([
            'phone' => [
                'required', 'string', 'max:20',
                \Illuminate\Validation\Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'identity_document' => ($hasIdentity ? 'nullable' : 'required') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'npwp_document' => ($hasNpwp ? 'nullable' : 'required') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'akta_document' => ($hasAkta ? 'nullable' : 'required') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'siup_document' => ($hasSiup ? 'nullable' : 'required') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'pic_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'phone.unique' => 'Nomor telepon ini sudah digunakan oleh akun lain.',
        ]);

        $user->update(['phone' => $request->phone]);

        $identityPath = $profile ? $profile->identity_document_url : null;
        if ($request->hasFile('identity_document')) {
            $path = $request->file('identity_document')->store('verification/perusahaan/identity', 'public');
            $identityPath = '/storage/' . $path;
        }

        $npwpPath = $profile ? $profile->npwp_document_url : null;
        if ($request->hasFile('npwp_document')) {
            $path = $request->file('npwp_document')->store('verification/perusahaan/npwp', 'public');
            $npwpPath = '/storage/' . $path;
        }

        $aktaPath = $profile ? $profile->akta_document_url : null;
        if ($request->hasFile('akta_document')) {
            $path = $request->file('akta_document')->store('verification/perusahaan/akta', 'public');
            $aktaPath = '/storage/' . $path;
        }

        $siupPath = $profile ? $profile->siup_document_url : null;
        if ($request->hasFile('siup_document')) {
            $path = $request->file('siup_document')->store('verification/perusahaan/siup', 'public');
            $siupPath = '/storage/' . $path;
        }

        $picPath = $profile ? $profile->pic_document_url : null;
        if ($request->hasFile('pic_document')) {
            $path = $request->file('pic_document')->store('verification/perusahaan/pic', 'public');
            $picPath = '/storage/' . $path;
        }

        CompanyProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'identity_document_url' => $identityPath,
                'npwp_document_url' => $npwpPath,
                'akta_document_url' => $aktaPath,
                'siup_document_url' => $siupPath,
                'pic_document_url' => $picPath,
                'verification_status' => 'pending',
                'verification_note' => null,
            ]
        );

        return back()->with('message', 'Pengajuan verifikasi berhasil dikirim!');
    }

    public function submitClient(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $profile = $user->clientProfile;

        $hasIdentity = $profile && $profile->identity_document_url;

        $request->validate([
            'phone' => [
                'required', 'string', 'max:20',
                \Illuminate\Validation\Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'identity_document' => ($hasIdentity ? 'nullable' : 'required') . '|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'domicile_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'project_ownership_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'phone.unique' => 'Nomor telepon ini sudah digunakan oleh akun lain.',
        ]);

        $user->update(['phone' => $request->phone]);

        $identityPath = $profile ? $profile->identity_document_url : null;
        if ($request->hasFile('identity_document')) {
            $path = $request->file('identity_document')->store('verification/client/identity', 'public');
            $identityPath = '/storage/' . $path;
        }
        
        $domicilePath = $profile ? $profile->domicile_document_url : null;
        if ($request->hasFile('domicile_document')) {
            $path = $request->file('domicile_document')->store('verification/client/domicile', 'public');
            $domicilePath = '/storage/' . $path;
        }

        $projectOwnershipPath = $profile ? $profile->project_ownership_document_url : null;
        if ($request->hasFile('project_ownership_document')) {
            $path = $request->file('project_ownership_document')->store('verification/client/project_ownership', 'public');
            $projectOwnershipPath = '/storage/' . $path;
        }

        ClientProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'identity_document_url' => $identityPath,
                'domicile_document_url' => $domicilePath,
                'project_ownership_document_url' => $projectOwnershipPath,
                'verification_status' => 'pending',
                'verification_note' => null,
            ]
        );

        return back()->with('message', 'Pengajuan verifikasi berhasil dikirim!');
    }
}
