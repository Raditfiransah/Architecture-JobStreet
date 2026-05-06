<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PortofolioController extends Controller
{
    public function index(Request $request)
    {
        $query = Portofolio::with('user');

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $portofolios = $query->latest()->paginate(12)->withQueryString();

        return Inertia::render('Admin/Portofolio/Index', [
            'portofolios' => $portofolios,
            'filters' => $request->only(['search']),
        ]);
    }

    public function destroy(Portofolio $portofolio)
    {
        $portofolio->delete();
        return back()->with('message', 'Portofolio berhasil dihapus.');
    }
}
