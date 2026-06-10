<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LowonganController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $l = $request->input('l');

        $query = Lowongan::query()
            ->publiclyAvailable()
            ->latest();

        if ($q) {
            $query->where(function ($qb) use ($q) {
                $qb->where('posisi', 'like', "%{$q}%")
                   ->orWhere('perusahaan', 'like', "%{$q}%")
                   ->orWhere('deskripsi', 'like', "%{$q}%");
            });
        }

        if ($l) {
            $query->where('kota', 'like', "%{$l}%");
        }

        $jobs = $query->get();

        return Inertia::render('Public/Lowongan/Index', [
            'title' => 'Daftar Lowongan Arsitek',
            'jobs' => $this->formatJobs($jobs),
            'filters' => [
                'q' => $q,
                'l' => $l,
            ],
        ]);
    }

    public function show(string $id)
    {
        $lowongan = Lowongan::query()
            ->publiclyAvailable()
            ->findOrFail($id);

        return Inertia::render('Public/Lowongan/Index', [
            'title' => $lowongan->posisi . ' — ' . $lowongan->perusahaan,
            'jobs' => $this->formatJobs(collect([$lowongan])),
            'filters' => [
                'q' => null,
                'l' => null,
            ],
        ]);
    }

    private function formatJobs($jobs)
    {
        // Pre-load all perusahaan users for matching — done once, not per-job
        $companies = User::query()
            ->where('role', 'perusahaan')
            ->with('companyProfile')
            ->get();

        // Index 1: by user.name exact (lowercase, trimmed)
        $byUserName = $companies->keyBy(fn(User $u) => strtolower(trim($u->name)));

        // Index 2: by company_profile.company_name exact (lowercase, trimmed)
        $byProfileName = $companies
            ->filter(fn(User $u) => filled($u->companyProfile?->company_name))
            ->keyBy(fn(User $u) => strtolower(trim($u->companyProfile->company_name)));

        $findCompany = function (Lowongan $lowongan) use ($companies, $byUserName, $byProfileName): ?User {
            // Priority 1: explicit user_id — most reliable
            if ($lowongan->user_id) {
                $found = $companies->find($lowongan->user_id);
                // Only return if the found user is actually perusahaan role
                return ($found && $found->role === 'perusahaan') ? $found : null;
            }

            $needle = strtolower(trim($lowongan->perusahaan));

            if (blank($needle)) {
                return null;
            }

            // Priority 2: exact match on user.name
            if (isset($byUserName[$needle])) {
                return $byUserName[$needle];
            }

            // Priority 3: exact match on company_profile.company_name
            if (isset($byProfileName[$needle])) {
                return $byProfileName[$needle];
            }

            // No match — do NOT fall back to any random company
            return null;
        };

        return $jobs->map(function (Lowongan $lowongan) use ($findCompany) {
            $match = $findCompany($lowongan);

            return array_merge($lowongan->toArray(), [
                'company_profile_url' => $match
                    ? route('public.perusahaan.show', $match->id)
                    : null,
            ]);
        })->values();
    }
}
