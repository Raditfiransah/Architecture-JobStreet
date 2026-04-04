<?php

namespace Tests\Feature\Auth;

use App\Models\EmailVerificationCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_arsitek_can_register(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Test Arsitek',
            'email' => 'arsitek@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'arsitek',
            'agree_to_terms' => '1',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice'));

        $this->assertDatabaseHas('users', [
            'email' => 'arsitek@example.com',
            'role' => 'arsitek',
        ]);

        // OTP should be generated
        $user = User::where('email', 'arsitek@example.com')->first();
        $this->assertDatabaseHas('email_verification_codes', [
            'user_id' => $user->id,
            'is_used' => false,
        ]);
    }

    public function test_perusahaan_can_register_with_company_profile(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Test Perusahaan',
            'email' => 'perusahaan@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'perusahaan',
            'agree_to_terms' => '1',
            'company_name' => 'PT Test Indonesia',
            'company_website' => 'https://pttest.com',
        ]);

        $this->assertAuthenticated();

        $this->assertDatabaseHas('company_profiles', [
            'company_name' => 'PT Test Indonesia',
        ]);
    }

    public function test_perusahaan_requires_company_name(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Test Perusahaan',
            'email' => 'perusahaan@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'perusahaan',
            'agree_to_terms' => '1',
        ]);

        $response->assertSessionHasErrors('company_name');
    }

    public function test_arsitek_does_not_require_company_name(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Test Arsitek',
            'email' => 'arsitek@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'arsitek',
            'agree_to_terms' => '1',
        ]);

        $response->assertSessionDoesntHaveErrors('company_name');
        $this->assertAuthenticated();
    }

    public function test_registration_requires_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'agree_to_terms' => '1',
        ]);

        $response->assertSessionHasErrors('role');
    }

    public function test_registration_requires_terms_agreement(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'arsitek',
        ]);

        $response->assertSessionHasErrors('agree_to_terms');
    }
}
