<?php

namespace Tests\Feature\Auth;

use App\Models\EmailVerificationCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/verifikasi-email');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified_with_otp(): void
    {
        $user = User::factory()->unverified()->arsitek()->create();

        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => '123456',
            'expired_at' => now()->addMinutes(10),
        ]);

        $response = $this->actingAs($user)
            ->post('/verifikasi-email', ['code' => '123456']);

        $this->assertNotNull($user->fresh()->email_verified_at);
        $response->assertRedirect(route('arsitek.profile'));
    }

    public function test_email_is_not_verified_with_invalid_otp(): void
    {
        $user = User::factory()->unverified()->arsitek()->create();

        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => '123456',
            'expired_at' => now()->addMinutes(10),
        ]);

        $response = $this->actingAs($user)
            ->post('/verifikasi-email', ['code' => '999999']);

        $this->assertNull($user->fresh()->email_verified_at);
        $response->assertSessionHasErrors('code');
    }
}
