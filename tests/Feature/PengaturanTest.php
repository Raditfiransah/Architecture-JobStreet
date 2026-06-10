<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengaturanTest extends TestCase
{
    use RefreshDatabase;

    public function test_arsitek_settings_page_is_accessible(): void
    {
        $user = User::factory()->create(['role' => 'arsitek']);

        $response = $this
            ->actingAs($user)
            ->get('/dashboard/arsitek/pengaturan');

        $response->assertOk();
    }

    public function test_client_settings_page_is_accessible(): void
    {
        $user = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($user)
            ->get('/dashboard/client/pengaturan');

        $response->assertOk();
    }

    public function test_perusahaan_settings_page_is_accessible(): void
    {
        $user = User::factory()->create(['role' => 'perusahaan']);

        $response = $this
            ->actingAs($user)
            ->get('/dashboard/perusahaan/pengaturan');

        $response->assertOk();
    }

    public function test_arsitek_can_update_notifications(): void
    {
        $user = User::factory()->create(['role' => 'arsitek']);

        $response = $this
            ->actingAs($user)
            ->put('/dashboard/arsitek/pengaturan/notifikasi', [
                'email_review' => true,
                'email_proposal' => false,
                'in_app' => true,
            ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_arsitek_can_delete_account_with_correct_password(): void
    {
        $user = User::factory()->create(['role' => 'arsitek']);

        $response = $this
            ->actingAs($user)
            ->delete('/dashboard/arsitek/pengaturan/akun', [
                'password' => 'password',
            ]);

        $response->assertRedirect('/login');
        $this->assertGuest();
        $this->assertNull($user->fresh());
    }
}
