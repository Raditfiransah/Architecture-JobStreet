<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function showForm()
    {
        return \Inertia\Inertia::render('Auth/Register');
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:arsitek,perusahaan,client'],
        ];

        if ($request->role === 'perusahaan') {
            $rules['company_name'] = ['required', 'string', 'max:255'];
            $rules['company_website'] = ['nullable', 'url', 'max:255'];
        }

        $request->validate($rules);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($user->role === 'perusahaan') {
            $user->companyProfile()->create([
                'company_name' => $request->company_name,
                'company_website' => $request->company_website,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}
