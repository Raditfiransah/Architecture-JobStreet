<?php

namespace App\Http\Controllers\Arsitek;

use App\Http\Controllers\Controller;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PortofolioController extends Controller
{
    public function index()
    {
        $portofolios = Auth::user()->portofolios;
        
        return Inertia::render('Arsitek/Portofolio/Index', [
            'portofolios' => $portofolios
        ]);
    }

    public function create()
    {
        return Inertia::render('Arsitek/Portofolio/Create');
    }

    public function store(Request $request)
    {
        $this->ensureProfileVerified();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_date' => 'nullable|date',
            'link' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:51200',
            'images.*' => 'nullable|image|max:51200',
        ]);

        $data = $request->only(['title', 'description', 'project_date', 'link']);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('portfolios/thumbnails', 'public');
        }

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('portfolios/gallery', 'public');
            }
            $data['images'] = $images;
        }

        Portofolio::create($data);

        return redirect()->route('arsitek.portofolio.index')->with('status', 'Portofolio berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $portofolio = Portofolio::where('user_id', Auth::id())->findOrFail($id);
        
        return Inertia::render('Arsitek/Portofolio/Edit', [
            'portofolio' => $portofolio
        ]);
    }

    public function update(Request $request, string $id)
    {
        $this->ensureProfileVerified();

        $portofolio = Portofolio::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_date' => 'nullable|date',
            'link' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:51200',
            'images.*' => 'nullable|image|max:51200',
        ]);

        $data = $request->only(['title', 'description', 'project_date', 'link']);

        if ($request->hasFile('thumbnail')) {
            if ($portofolio->thumbnail) {
                Storage::delete('public/' . $portofolio->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('portfolios/thumbnails', 'public');
        }

        if ($request->hasFile('images')) {
            // Option: Replace all images or append? 
            // Standard approach: if new images provided, we often replace or have a separate management.
            // For now, let's replace for simplicity as a first version.
            if ($portofolio->images) {
                foreach ($portofolio->images as $oldImage) {
                    Storage::delete('public/' . $oldImage);
                }
            }
            
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('portfolios/gallery', 'public');
            }
            $data['images'] = $images;
        }

        $portofolio->update($data);

        return redirect()->route('arsitek.portofolio.index')->with('status', 'Portofolio berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->ensureProfileVerified();

        $portofolio = Portofolio::where('user_id', Auth::id())->findOrFail($id);
        
        if ($portofolio->thumbnail) {
            Storage::delete('public/' . $portofolio->thumbnail);
        }
        
        if ($portofolio->images) {
            foreach ($portofolio->images as $image) {
                Storage::delete('public/' . $image);
            }
        }
        
        $portofolio->delete();

        return redirect()->route('arsitek.portofolio.index')->with('status', 'Portofolio berhasil dihapus.');
    }

    public function reorder(Request $request)
    {
        $this->ensureProfileVerified();

        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:portofolios,id',
            'orders.*.order' => 'required|integer',
        ]);

        foreach ($request->orders as $item) {
            Portofolio::where('user_id', Auth::id())
                ->where('id', $item['id'])
                ->update(['order' => $item['order']]);
        }

        return response()->json(['status' => 'ok']);
    }
}
