<?php

namespace Tests\Feature;

use App\Models\InfoHub;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminInfoHubTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_create_info_hub_page(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.info.create'));

        $response->assertOk();
    }

    public function test_admin_can_publish_info_hub_post(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $poster = UploadedFile::fake()->image('poster.jpg', 1200, 675);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.info.store'), [
                'judul' => 'Seminar Arsitektur Tropis',
                'kategori' => 'Event',
                'deskripsi' => 'Diskusi tentang desain pasif dan ruang publik tropis.',
                'gambar_poster' => $poster,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.info.index'))
            ->assertSessionHas('success', 'Postingan mading berhasil dipublikasikan.');

        $infoHub = InfoHub::first();

        $this->assertNotNull($infoHub);
        $this->assertSame($admin->id, $infoHub->admin_id);
        $this->assertSame('Seminar Arsitektur Tropis', $infoHub->judul);
        $this->assertSame('Event', $infoHub->kategori);
        Storage::disk('public')->assertExists($infoHub->gambar_poster);
    }

    public function test_non_admin_cannot_open_create_info_hub_page(): void
    {
        $user = User::factory()->arsitek()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('admin.info.create'));

        $response->assertForbidden();
    }

    public function test_validation_fails_when_title_is_empty(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        $response = $this
            ->actingAs($admin)
            ->from(route('admin.info.create'))
            ->post(route('admin.info.store'), [
                'judul' => '',
                'kategori' => 'Event',
                'deskripsi' => 'Deskripsi lengkap kegiatan.',
                'gambar_poster' => UploadedFile::fake()->image('poster.jpg'),
            ]);

        $response
            ->assertRedirect(route('admin.info.create'))
            ->assertSessionHasErrors('judul');

        $this->assertDatabaseCount('info_hubs', 0);
    }

    public function test_validation_fails_when_uploaded_file_is_not_an_image(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        $response = $this
            ->actingAs($admin)
            ->from(route('admin.info.create'))
            ->post(route('admin.info.store'), [
                'judul' => 'Kompetisi Desain',
                'kategori' => 'Sayembara',
                'deskripsi' => 'Deskripsi lengkap kegiatan.',
                'gambar_poster' => UploadedFile::fake()->create('poster.pdf', 128, 'application/pdf'),
            ]);

        $response
            ->assertRedirect(route('admin.info.create'))
            ->assertSessionHasErrors(['gambar_poster' => 'Format file tidak didukung atau terlalu besar.']);

        $this->assertDatabaseCount('info_hubs', 0);
    }
}
