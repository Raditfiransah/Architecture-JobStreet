<?php

namespace App\Services;

use App\Models\EmailVerificationCode;
use App\Models\User;
use Carbon\Carbon;

class OtpService
{
    public function generate(User $user): EmailVerificationCode
    {
        $user->emailVerificationCodes()->where('is_used', false)->delete();

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return $user->emailVerificationCodes()->create([
            'code' => $code,
            'expired_at' => Carbon::now()->addMinutes(10),
        ]);
    }

    public function validate(User $user, string $code): EmailVerificationCode|false
    {
        $verificationCode = $user->emailVerificationCodes()
            ->where('code', $code)
            ->where('is_used', false)
            ->first();

        if (! $verificationCode) {
            return false;
        }

        if ($verificationCode->isExpired()) {
            return false;
        }

        return $verificationCode;
    }

    public function markUsed(EmailVerificationCode $code): void
    {
        $code->update([
            'is_used' => true,
            'used_at' => now(),
        ]);
    }

    public function canResend(User $user): bool
    {
        $lastCode = $user->emailVerificationCodes()
            ->latest()
            ->first();

        if (! $lastCode) {
            return true;
        }

        return $lastCode->created_at->addMinute()->isPast();
    }
}
