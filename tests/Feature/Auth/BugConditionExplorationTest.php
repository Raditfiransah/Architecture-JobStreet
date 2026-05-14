<?php

/**
 * Bug Condition Exploration Test
 *
 * PURPOSE: Confirm the three sub-conditions of the auth-routes-missing bug exist
 *          on UNFIXED code. These tests are EXPECTED TO FAIL on the current codebase.
 *          Failure here is the SUCCESS case — it proves the bugs exist.
 *
 * COUNTEREXAMPLES DOCUMENTED (observed on unfixed code):
 *
 * Sub-condition A — Missing routes (all return 404):
 *   - GET  /confirm-password  → HTTP 404  (expected: 200)
 *   - GET  /profile           → HTTP 404  (expected: 200)
 *   - PUT  /password          → HTTP 404  (expected: redirect / non-404)
 *   - URL::temporarySignedRoute('verification.verify', ...) → throws RouteNotFoundException
 *     (expected: returns a valid signed URL string)
 *
 * Sub-condition B — URL mismatches (routes registered under Indonesian paths only):
 *   - GET  /verify-email      → HTTP 404  (expected: 200; route is at /verifikasi-email)
 *   - GET  /forgot-password   → HTTP 404  (expected: 200; route is at /lupa-password)
 *   - POST /forgot-password   → HTTP 404, no ResetPassword notification dispatched
 *     (expected: non-404 + notification sent)
 *
 * Sub-condition C — Login skips email-verification check:
 *   - POST /login with valid credentials for an unverified, active arsitek
 *     → redirects to route('arsitek.dashboard')  (expected: route('verification.notice'))
 *
 * Spec: .kiro/specs/auth-routes-missing/
 * Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 1.7, 1.8, 1.9, 1.10, 1.11
 */

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class BugConditionExplorationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    // ─── Sub-condition A: Missing routes ─────────────────────────────────────

    /**
     * Sub-condition A: GET /confirm-password should return 200 for an authenticated user.
     * On unfixed code this returns 404 because the route is not registered.
     *
     * Validates: Requirement 1.6
     */
    public function test_get_confirm_password_returns_200_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/confirm-password');

        // EXPECTED TO FAIL on unfixed code: route does not exist → 404
        $response->assertStatus(200);
    }

    /**
     * Sub-condition A: GET /profile should return 200 for an authenticated user.
     * On unfixed code this returns 404 because the route is not registered.
     *
     * Validates: Requirement 1.9
     */
    public function test_get_profile_returns_200_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        // EXPECTED TO FAIL on unfixed code: route does not exist → 404
        $response->assertStatus(200);
    }

    /**
     * Sub-condition A: PUT /password should not return 404 for an authenticated user.
     * On unfixed code this returns 404 because the route is not registered.
     *
     * Validates: Requirement 1.8
     */
    public function test_put_password_does_not_return_404_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        // EXPECTED TO FAIL on unfixed code: route does not exist → 404
        // Assert the response is NOT 404 (any other status is acceptable here)
        $this->assertNotEquals(
            404,
            $response->getStatusCode(),
            'PUT /password returned 404 — the route is not registered.'
        );
    }

    /**
     * Sub-condition A: URL::temporarySignedRoute('verification.verify', ...) should not throw.
     * On unfixed code this throws RouteNotFoundException because the named route is not defined.
     *
     * Validates: Requirement 1.10
     */
    public function test_verification_verify_route_can_be_resolved_without_exception(): void
    {
        $user = User::factory()->unverified()->create();

        $threwException = false;
        $signedUrl = null;

        try {
            $signedUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->id, 'hash' => sha1($user->email)]
            );
        } catch (\Symfony\Component\Routing\Exception\RouteNotFoundException $e) {
            $threwException = true;
        } catch (\Illuminate\Routing\Exceptions\UrlGenerationException $e) {
            $threwException = true;
        } catch (\InvalidArgumentException $e) {
            $threwException = true;
        }

        // EXPECTED TO FAIL on unfixed code: throws RouteNotFoundException
        $this->assertFalse($threwException, 'URL::temporarySignedRoute threw an exception because verification.verify route is not defined.');
        $this->assertNotNull($signedUrl, 'Expected a valid signed URL but got null.');
    }

    // ─── Sub-condition B: URL mismatches ─────────────────────────────────────

    /**
     * Sub-condition B: GET /verify-email should return 200 for an unverified authenticated user.
     * On unfixed code this returns 404 because the route is registered as /verifikasi-email.
     *
     * Validates: Requirement 1.2
     */
    public function test_get_verify_email_returns_200_for_unverified_authenticated_user(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/verify-email');

        // EXPECTED TO FAIL on unfixed code: route is /verifikasi-email, not /verify-email → 404
        $response->assertStatus(200);
    }

    /**
     * Sub-condition B: GET /forgot-password should return 200 for a guest.
     * On unfixed code this returns 404 because the route is registered as /lupa-password.
     *
     * Validates: Requirement 1.4
     */
    public function test_get_forgot_password_returns_200_for_guest(): void
    {
        $response = $this->get('/forgot-password');

        // EXPECTED TO FAIL on unfixed code: route is /lupa-password, not /forgot-password → 404
        $response->assertStatus(200);
    }

    /**
     * Sub-condition B: POST /forgot-password should not return 404 and should dispatch
     * the ResetPassword notification.
     * On unfixed code this returns 404 and no notification is dispatched.
     *
     * Validates: Requirements 1.4, 1.5
     */
    public function test_post_forgot_password_does_not_return_404_and_dispatches_notification(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        // EXPECTED TO FAIL on unfixed code: route is /lupa-password → 404, no notification sent
        // Assert the response is NOT 404
        $this->assertNotEquals(
            404,
            $response->getStatusCode(),
            'POST /forgot-password returned 404 — the route is not registered (only /lupa-password exists).'
        );
        // Assert the ResetPassword notification was dispatched
        Notification::assertSentTo($user, ResetPassword::class);
    }

    // ─── Sub-condition C: Login skips verification ────────────────────────────

    /**
     * Sub-condition C: POST /login with valid credentials for an unverified, active arsitek
     * should redirect to route('verification.notice'), NOT to route('arsitek.dashboard').
     * On unfixed code, LoginController::redirectAfterLogin() does not check email_verified_at,
     * so it redirects straight to the role dashboard.
     *
     * Validates: Requirement 1.1
     */
    public function test_login_for_unverified_active_arsitek_redirects_to_verification_notice(): void
    {
        $user = User::factory()->unverified()->arsitek()->create(['is_active' => true]);

        RateLimiter::clear(strtolower($user->email).'|127.0.0.1');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        // EXPECTED TO FAIL on unfixed code: redirects to arsitek.dashboard instead
        $response->assertRedirect(route('verification.notice'));

        // Also assert it does NOT redirect to the role dashboard
        $this->assertNotEquals(
            route('arsitek.dashboard'),
            $response->headers->get('Location'),
            'Unverified user should NOT be redirected to arsitek.dashboard'
        );
    }
}
