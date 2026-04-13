<?php

namespace Tests\Feature\Auth;

use App\Models\EmailVerificationCode;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OtpVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    public function test_otp_verification_page_requires_auth(): void
    {
        $response = $this->get('/verifikasi-email');
        $response->assertRedirect('/login');
    }

    public function test_verified_user_is_redirected_from_otp_page(): void
    {
        $user = User::factory()->arsitek()->create();

        $response = $this->actingAs($user)->get('/verifikasi-email');
        $response->assertRedirect(route('arsitek.profile'));
    }

    public function test_unverified_user_can_see_otp_page(): void
    {
        $user = User::factory()->unverified()->arsitek()->create();

        $response = $this->actingAs($user)->get('/verifikasi-email');

        $response->assertStatus(200);
    }

    public function test_user_can_verify_with_valid_otp(): void
    {
        $user = User::factory()->unverified()->arsitek()->create();

        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => '123456',
            'expired_at' => now()->addMinutes(10),
        ]);

        $response = $this->actingAs($user)
            ->withSession(['otp_email' => $user->email])
            ->post('/verifikasi-email', ['code' => '123456']);

        $response->assertRedirect(route('arsitek.profile'));

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_invalid_otp_shows_error(): void
    {
        $user = User::factory()->unverified()->arsitek()->create();

        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => '123456',
            'expired_at' => now()->addMinutes(10),
        ]);

        $response = $this->actingAs($user)
            ->withSession(['otp_email' => $user->email])
            ->post('/verifikasi-email', ['code' => '999999']);

        $response->assertSessionHasErrors('code');
    }

    public function test_expired_otp_shows_appropriate_error(): void
    {
        $user = User::factory()->unverified()->arsitek()->create();

        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => '123456',
            'expired_at' => now()->subMinutes(1),
        ]);

        $response = $this->actingAs($user)
            ->withSession(['otp_email' => $user->email])
            ->post('/verifikasi-email', ['code' => '123456']);

        $response->assertSessionHasErrors('code');
    }

    public function test_user_can_resend_otp_after_cooldown(): void
    {
        Mail::fake();

        $user = User::factory()->unverified()->arsitek()->create();

        // Create an old code (created 2 minutes ago, past the 1min cooldown)
        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => '111111',
            'expired_at' => now()->addMinutes(8),
            'created_at' => now()->subMinutes(2),
            'updated_at' => now()->subMinutes(2),
        ]);

        $response = $this->actingAs($user)
            ->withSession(['otp_email' => $user->email])
            ->post('/verifikasi-email/resend');

        // Should succeed (not be blocked by cooldown)
        $response->assertSessionDoesntHaveErrors('email');
    }

    public function test_resend_respects_application_cooldown(): void
    {
        Mail::fake();

        $user = User::factory()->unverified()->arsitek()->create();

        // Use OtpService directly to verify the cooldown logic
        $otpService = app(OtpService::class);
        $this->assertTrue($otpService->canResend($user)); // no codes yet → can resend

        $otpService->generate($user); // generate a new code (just now)
        $this->assertFalse($otpService->canResend($user)); // should be blocked
    }

    public function test_session_fallback_to_auth_user(): void
    {
        $user = User::factory()->unverified()->arsitek()->create();

        // No otp_email in session — should fallback to auth user
        $response = $this->actingAs($user)->get('/verifikasi-email');

        $response->assertStatus(200);
    }
}
