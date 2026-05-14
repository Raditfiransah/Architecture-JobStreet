<?php

/**
 * Preservation Property Tests
 *
 * PURPOSE: Capture the EXISTING CORRECT behavior on UNFIXED code.
 *          These tests MUST PASS on the current (unfixed) codebase.
 *          They establish the baseline that must not regress after the fix is applied.
 *
 * WHAT IS TESTED (all on unfixed code):
 *   1. GET  /verifikasi-email  → 200 for an unverified authenticated user
 *   2. POST /verifikasi-email  → processes OTP submission correctly
 *   3. GET  /lupa-password     → 200 for a guest
 *   4. POST /lupa-password     → attempts to send reset link (Mail::assertSent)
 *   5. POST /login for a verified arsitek    → redirects to route('arsitek.dashboard')
 *   6. POST /login for a verified perusahaan → redirects to route('perusahaan.dashboard')
 *   7. POST /login for a verified client     → redirects to route('client.dashboard')
 *   8. POST /login for an inactive user      → validation error, user remains unauthenticated
 *   9. POST /logout → logs out and redirects to '/'
 *  10. GET  /login and GET /register → render correctly for guests
 *
 * PROPERTY-BASED TESTS:
 *   - For all verified users across all roles (arsitek, perusahaan, client),
 *     redirectAfterLogin() always returns the correct role dashboard route (never verification.notice)
 *   - For all inactive users, login always returns a validation error and the user remains unauthenticated
 *
 * Spec: .kiro/specs/auth-routes-missing/
 * Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7, 3.8, 3.9, 3.10
 */

namespace Tests\Feature\Auth;

use App\Mail\ResetPasswordMail;
use App\Models\EmailVerificationCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class PreservationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    // ─── Example-based: Indonesian OTP routes ────────────────────────────────

    /**
     * Preservation 3.5: GET /verifikasi-email returns 200 for an unverified authenticated user.
     *
     * Validates: Requirement 3.5
     */
    public function test_get_verifikasi_email_returns_200_for_unverified_user(): void
    {
        $user = User::factory()->unverified()->arsitek()->create();

        $response = $this->actingAs($user)->get('/verifikasi-email');

        $response->assertStatus(200);
    }

    /**
     * Preservation 3.5: GET /verifikasi-email redirects a verified user to their dashboard.
     * The already-verified guard in OtpVerificationController::showForm() must not regress.
     *
     * Validates: Requirement 3.9
     */
    public function test_get_verifikasi_email_redirects_verified_user_to_dashboard(): void
    {
        $user = User::factory()->arsitek()->create(); // verified by default

        $response = $this->actingAs($user)->get('/verifikasi-email');

        $response->assertRedirect(route('arsitek.dashboard'));
    }

    /**
     * Preservation 3.5: POST /verifikasi-email processes a valid OTP submission correctly.
     *
     * Validates: Requirement 3.5
     */
    public function test_post_verifikasi_email_processes_valid_otp(): void
    {
        $user = User::factory()->unverified()->arsitek()->create();

        EmailVerificationCode::create([
            'user_id'    => $user->id,
            'code'       => '654321',
            'expired_at' => now()->addMinutes(10),
        ]);

        $response = $this->actingAs($user)->post('/verifikasi-email', ['code' => '654321']);

        $response->assertRedirect(route('arsitek.dashboard'));

        $user->refresh();
        $this->assertNotNull($user->email_verified_at, 'email_verified_at should be set after OTP verification');
    }

    /**
     * Preservation 3.5: POST /verifikasi-email returns an error for an invalid OTP.
     *
     * Validates: Requirement 3.5
     */
    public function test_post_verifikasi_email_rejects_invalid_otp(): void
    {
        $user = User::factory()->unverified()->arsitek()->create();

        EmailVerificationCode::create([
            'user_id'    => $user->id,
            'code'       => '654321',
            'expired_at' => now()->addMinutes(10),
        ]);

        $response = $this->actingAs($user)->post('/verifikasi-email', ['code' => '000000']);

        $response->assertSessionHasErrors('code');
    }

    // ─── Example-based: Indonesian forgot-password routes ────────────────────

    /**
     * Preservation 3.6: GET /lupa-password returns 200 for a guest.
     *
     * Validates: Requirement 3.6
     */
    public function test_get_lupa_password_returns_200_for_guest(): void
    {
        $response = $this->get('/lupa-password');

        $response->assertStatus(200);
    }

    /**
     * Preservation 3.6: POST /lupa-password sends the reset link via ResetPasswordMail.
     *
     * The User model overrides sendPasswordResetNotification() to use Mail::to()->send()
     * with App\Mail\ResetPasswordMail instead of the standard ResetPassword notification.
     *
     * Validates: Requirement 3.6
     */
    public function test_post_lupa_password_sends_reset_password_mail(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $response = $this->post('/lupa-password', ['email' => $user->email]);

        // Should not return 404 — the route exists
        $this->assertNotEquals(404, $response->getStatusCode());

        // The custom sendPasswordResetNotification() sends ResetPasswordMail via Mail::to()->send()
        Mail::assertSent(ResetPasswordMail::class);
    }

    // ─── Example-based: Login / register pages ───────────────────────────────

    /**
     * Preservation 3.8: GET /login renders correctly for guests.
     *
     * Validates: Requirement 3.8
     */
    public function test_get_login_renders_for_guest(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * Preservation 3.8: GET /register renders correctly for guests.
     *
     * Validates: Requirement 3.8
     */
    public function test_get_register_renders_for_guest(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    // ─── Example-based: Verified user role-based login redirects ─────────────

    /**
     * Preservation 3.1: POST /login for a verified arsitek redirects to arsitek.dashboard.
     *
     * Validates: Requirement 3.1
     */
    public function test_verified_arsitek_login_redirects_to_arsitek_dashboard(): void
    {
        $user = User::factory()->arsitek()->create(['is_active' => true]);
        RateLimiter::clear(strtolower($user->email).'|127.0.0.1');

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('arsitek.dashboard'));
    }

    /**
     * Preservation 3.2: POST /login for a verified perusahaan redirects to perusahaan.dashboard.
     *
     * Validates: Requirement 3.2
     */
    public function test_verified_perusahaan_login_redirects_to_perusahaan_dashboard(): void
    {
        $user = User::factory()->perusahaan()->create(['is_active' => true]);
        RateLimiter::clear(strtolower($user->email).'|127.0.0.1');

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('perusahaan.dashboard'));
    }

    /**
     * Preservation 3.3: POST /login for a verified client redirects to client.dashboard.
     *
     * Validates: Requirement 3.3
     */
    public function test_verified_client_login_redirects_to_client_dashboard(): void
    {
        $user = User::factory()->client()->create(['is_active' => true]);
        RateLimiter::clear(strtolower($user->email).'|127.0.0.1');

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('client.dashboard'));
    }

    // ─── Example-based: Inactive user rejection ──────────────────────────────

    /**
     * Preservation 3.4: POST /login for an inactive user returns a validation error
     * and the user remains unauthenticated.
     *
     * Validates: Requirement 3.4
     */
    public function test_inactive_user_login_returns_validation_error_and_stays_unauthenticated(): void
    {
        $user = User::factory()->arsitek()->create(['is_active' => false]);
        RateLimiter::clear(strtolower($user->email).'|127.0.0.1');

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    // ─── Example-based: Logout ───────────────────────────────────────────────

    /**
     * Preservation 3.7: POST /logout logs the user out and redirects to '/'.
     *
     * Validates: Requirement 3.7
     */
    public function test_logout_logs_out_and_redirects_to_home(): void
    {
        $user = User::factory()->arsitek()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    // ─── Property-based: Verified users always get role dashboard redirect ────

    /**
     * Property 3 (Preservation): For all verified users across all roles,
     * POST /login always redirects to the correct role dashboard route —
     * never to route('verification.notice').
     *
     * This property is tested across all three non-admin roles with multiple
     * users per role to provide broad coverage.
     *
     * Validates: Requirements 3.1, 3.2, 3.3
     */
    public function test_property_verified_users_always_redirect_to_role_dashboard_never_verification_notice(): void
    {
        $roleToExpectedRoute = [
            'arsitek'    => route('arsitek.dashboard'),
            'perusahaan' => route('perusahaan.dashboard'),
            'client'     => route('client.dashboard'),
        ];

        // Test multiple verified users per role to simulate property-based coverage
        foreach ($roleToExpectedRoute as $role => $expectedRoute) {
            for ($i = 0; $i < 3; $i++) {
                $user = User::factory()->{$role}()->create(['is_active' => true]);
                // email_verified_at is set by default in the factory
                $this->assertNotNull($user->email_verified_at, "Factory should create verified users by default");

                RateLimiter::clear(strtolower($user->email).'|127.0.0.1');

                $response = $this->post('/login', [
                    'email'    => $user->email,
                    'password' => 'password',
                ]);

                $this->assertAuthenticated(
                    null,
                    "Verified {$role} user #{$i} should be authenticated after login"
                );

                $response->assertRedirect(
                    $expectedRoute,
                    "Verified {$role} user #{$i} should redirect to {$expectedRoute}"
                );

                $this->assertNotEquals(
                    route('verification.notice'),
                    $response->headers->get('Location'),
                    "Verified {$role} user #{$i} must NOT be redirected to verification.notice"
                );

                // Log out before next iteration
                $this->post('/logout');
            }
        }
    }

    /**
     * Property 4 (Preservation): For all inactive users (regardless of role),
     * POST /login always returns a validation error and the user remains unauthenticated.
     *
     * Validates: Requirement 3.4
     */
    public function test_property_inactive_users_always_get_validation_error_and_remain_unauthenticated(): void
    {
        $roles = ['arsitek', 'perusahaan', 'client'];

        // Test multiple inactive users per role to simulate property-based coverage
        foreach ($roles as $role) {
            for ($i = 0; $i < 3; $i++) {
                $user = User::factory()->{$role}()->create(['is_active' => false]);

                RateLimiter::clear(strtolower($user->email).'|127.0.0.1');

                $response = $this->post('/login', [
                    'email'    => $user->email,
                    'password' => 'password',
                ]);

                $this->assertGuest(
                    null,
                    "Inactive {$role} user #{$i} must remain unauthenticated after login attempt"
                );

                $response->assertSessionHasErrors(
                    'email',
                    "Inactive {$role} user #{$i} login must produce a validation error on 'email'"
                );
            }
        }
    }
}
