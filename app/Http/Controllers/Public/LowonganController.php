<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use Illuminate\Http\Request;

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

        return \Inertia\Inertia::render('Public/Lowongan/Index', [
            'title' => 'Daftar Lowongan Arsitek',
            'jobs' => $jobs,
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

        return \Inertia\Inertia::render('Public/Lowongan/Index', [
            'title' => $lowongan->posisi . ' — ' . $lowongan->perusahaan,
            'jobs' => [$lowongan],
            'filters' => [
                'q' => null,
                'l' => null,
            ],
        ]);
    }
}
