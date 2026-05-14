<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailVerificationCode;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SecurityController extends Controller
{
    public function index()
    {
        $verificationCodes = EmailVerificationCode::with('user')->latest()->limit(50)->get();
        $passwordResets = DB::table('password_reset_tokens')->latest()->limit(50)->get();

        return Inertia::render('Admin/Security/Index', [
            'verificationCodes' => $verificationCodes,
            'passwordResets' => $passwordResets,
        ]);
    }
}
