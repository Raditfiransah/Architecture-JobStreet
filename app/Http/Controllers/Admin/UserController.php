<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\User::query()->where('role', '!=', 'admin');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return \Inertia\Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'role']),
        ]);
    }

    public function show(\App\Models\User $user)
    {
        $user->load(['arsitekProfile', 'companyProfile', 'clientProfile']);
        return \Inertia\Inertia::render('Admin/Users/Show', [
            'user' => $user
        ]);
    }

    public function suspend(\App\Models\User $user)
    {
        $user->update(['is_active' => false]);
        return back()->with('message', 'User berhasil di-suspend.');
    }

    public function aktifkan(\App\Models\User $user)
    {
        $user->update(['is_active' => true]);
        return back()->with('message', 'User berhasil diaktifkan kembali.');
    }

    public function destroy(\App\Models\User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('message', 'User berhasil dihapus.');
    }
}
