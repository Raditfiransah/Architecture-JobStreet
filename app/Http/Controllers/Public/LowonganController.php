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
        $fallbackCompanyId = User::query()
            ->where('role', 'perusahaan')
            ->oldest()
            ->value('id');

        return $jobs->map(function (Lowongan $lowongan) {
            $companyUserId = $lowongan->user_id ?: User::query()
                ->where('role', 'perusahaan')
                ->where('name', $lowongan->perusahaan)
                ->value('id');

            return array_merge($lowongan->toArray(), [
                'company_profile_url' => $companyUserId
                    ? route('public.perusahaan.show', $companyUserId)
                    : null,
            ]);
        })->values()->map(function (array $job) use ($fallbackCompanyId) {
            if ($job['company_profile_url'] || !$fallbackCompanyId) {
                return $job;
            }

            $job['company_profile_url'] = route('public.perusahaan.show', $fallbackCompanyId);

            return $job;
        });
    }
}
