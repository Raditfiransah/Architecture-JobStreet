<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResendOtpMail;
use App\Models\CompanyProfile;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
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
            ]);
        }

        $verificationCode = $otpService->generate($user);

        try {
            Mail::to($user->email)->send(new ResendOtpMail($verificationCode->code, $user->name));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send OTP email: '.$e->getMessage());
        }

        auth()->login($user);

        session(['otp_email' => $user->email]);

        return redirect()->route('verification.notice')
            ->with('status', __('Registrasi berhasil! Kode verifikasi telah dikirim ke :email.', ['email' => $user->email]));
    }
}
