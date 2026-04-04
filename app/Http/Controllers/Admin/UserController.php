<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function show(string $id)
    {
        return view('admin.users.show', compact('id'));
    }

    public function verifikasi(string $id)
    {
        // TODO: Implement - verifikasi user
        return back()->with('status', 'User berhasil diverifikasi.');
    }

    public function suspend(string $id)
    {
        // TODO: Implement - suspend user
        return back()->with('status', 'User berhasil di-suspend.');
    }

    public function aktifkan(string $id)
    {
        // TODO: Implement - aktifkan user
        return back()->with('status', 'User berhasil diaktifkan kembali.');
    }
}
