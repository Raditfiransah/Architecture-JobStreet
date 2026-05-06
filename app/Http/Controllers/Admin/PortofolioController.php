<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PortofolioController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\User::query()
            ->where('role', 'arsitek')
            ->withCount('portofolios');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return Inertia::render('Admin/Portofolio/Index', [
            'users'   => $users,
            'filters' => $request->only(['search']),
        ]);
    }

    public function show(\App\Models\User $user)
    {
        $portofolios = $user->portofolios()->latest()->get();

        return Inertia::render('Admin/Portofolio/Show', [
            'user'        => $user,
            'portofolios' => $portofolios,
        ]);
    }

    public function edit(Portofolio $portofolio)
    {
        return Inertia::render('Admin/Portofolio/Edit', [
            'portofolio' => $portofolio->load('user'),
        ]);
    }

    public function update(Request $request, Portofolio $portofolio)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_date'=> 'nullable|date',
            'link'        => 'nullable|url',
            'thumbnail'   => 'nullable|image|max:51200',
            'images.*'    => 'nullable|image|max:51200',
        ]);

        $data = $request->only(['title', 'description', 'project_date', 'link']);

        if ($request->hasFile('thumbnail')) {
            if ($portofolio->thumbnail) {
                Storage::disk('public')->delete($portofolio->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('portfolios/thumbnails', 'public');
        }

        if ($request->hasFile('images')) {
            if ($portofolio->images) {
                foreach ($portofolio->images as $oldImg) {
                    Storage::disk('public')->delete($oldImg);
                }
            }
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('portfolios/gallery', 'public');
            }
            $data['images'] = $images;
        }

        $portofolio->update($data);

        return redirect()
            ->route('admin.portofolio.show', $portofolio->user_id)
            ->with('message', 'Portofolio berhasil diperbarui.');
    }

    public function destroy(Portofolio $portofolio)
    {
        $userId = $portofolio->user_id;

        if ($portofolio->thumbnail) {
            Storage::disk('public')->delete($portofolio->thumbnail);
        }
        if ($portofolio->images) {
            foreach ($portofolio->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $portofolio->delete();

        return back()->with('message', 'Portofolio berhasil dihapus.');
    }

    public function destroyImage(Request $request, Portofolio $portofolio)
    {
        $request->validate(['path' => 'required|string']);

        $images = $portofolio->images ?? [];
        $images = array_filter($images, fn($img) => $img !== $request->path);
        $portofolio->update(['images' => array_values($images)]);

        Storage::disk('public')->delete($request->path);

        return back()->with('message', 'Gambar berhasil dihapus.');
    }
}
