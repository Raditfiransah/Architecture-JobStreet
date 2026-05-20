<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);

        // Clear the login rate limiter for test IP
        // LoginRequest uses email|ip as the throttle key
        RateLimiter::clear(md5(''));
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_verified_active_arsitek_can_login(): void
    {
        $user = User::factory()->arsitek()->create(['is_active' => true]);

        // Clear any rate limiter for this specific email
        RateLimiter::clear(strtolower($user->email).'|127.0.0.1');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('arsitek.dashboard'));
    }

    public function test_verified_active_perusahaan_can_login(): void
    {
        $user = User::factory()->perusahaan()->create(['is_active' => true]);

        RateLimiter::clear(strtolower($user->email).'|127.0.0.1');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('perusahaan.dashboard'));
    }

    public function test_verified_active_client_can_login(): void
    {
        $user = User::factory()->client()->create(['is_active' => true]);

        RateLimiter::clear(strtolower($user->email).'|127.0.0.1');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('client.dashboard'));
    }

    public function test_unverified_user_is_redirected_to_otp_page(): void
    {
        Mail::fake();

        $user = User::factory()->unverified()->arsitek()->create(['is_active' => true]);

        RateLimiter::clear(strtolower($user->email).'|127.0.0.1');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::factory()->arsitek()->create(['is_active' => false]);

        RateLimiter::clear(strtolower($user->email).'|127.0.0.1');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    public function test_wrong_password_fails(): void
    {
        $user = User::factory()->arsitek()->create();

        RateLimiter::clear(strtolower($user->email).'|127.0.0.1');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->arsitek()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
