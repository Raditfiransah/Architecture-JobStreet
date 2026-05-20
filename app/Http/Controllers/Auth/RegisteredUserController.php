<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResendOtpMail;
use App\Models\ArsitekProfile;
use App\Models\CompanyProfile;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request, OtpService $otpService): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:150', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:arsitek,perusahaan,client'],
            'agree_to_terms' => ['required', 'accepted'],
            'company_name' => ['nullable', 'required_if:role,perusahaan', 'string', 'min:3', 'max:150'],
            'company_website' => ['nullable', 'url'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->role === 'perusahaan') {
            CompanyProfile::create([
                'user_id' => $user->id,
                'company_name' => $request->company_name,
                'company_website' => $request->company_website,
                'verification_status' => 'unverified',
            ]);
        } elseif ($request->role === 'arsitek') {
            ArsitekProfile::create([
                'user_id' => $user->id,
                'verification_status' => 'unverified',
            ]);
        }

        $verificationCode = $otpService->generate($user);
        
        // Log OTP as fallback for development
        \Illuminate\Support\Facades\Log::info("OTP created for user {$user->email}: {$verificationCode->code}");

        try {
            Mail::to($user->email)->send(new ResendOtpMail($verificationCode->code, $user->name));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send OTP email: '.$e->getMessage());
        }

        Auth::login($user);

        session(['otp_email' => $user->email]);

        return redirect()->route('verification.notice')
            ->with('message', __('Registrasi berhasil! Kode verifikasi telah dikirim ke :email.', ['email' => $user->email]));
    }
}
