<?php

namespace Tests\Feature;

use App\Models\ArsitekProfile;
use App\Models\CompanyProfile;
use App\Models\Lowongan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class LowonganActivePeriodTest extends TestCase
{
    use RefreshDatabase;

    public function test_perusahaan_can_create_lowongan_with_active_period(): void
    {
        $perusahaan = $this->verifiedPerusahaan([
            'name' => 'Studio Rekrutmen',
        ]);

        $response = $this
            ->actingAs($perusahaan)
            ->post(route('perusahaan.lowongan.store'), $this->validLowonganPayload([
                'tanggal_mulai' => today()->toDateString(),
                'batas_lamaran' => today()->addDays(14)->toDateString(),
            ]));

        $response->assertRedirect(route('perusahaan.lowongan.index'));

        $this->assertDatabaseHas('lowongan', [
            'user_id' => $perusahaan->id,
            'posisi' => 'Arsitek Proyek',
            'status' => 'aktif',
            'tanggal_mulai' => today()->toDateString(),
            'batas_lamaran' => today()->addDays(14)->toDateString(),
            'deadline' => today()->addDays(14)->toDateString(),
        ]);
    }

    public function test_perusahaan_cannot_set_deadline_before_start_date(): void
    {
        $perusahaan = $this->verifiedPerusahaan();

        $response = $this
            ->actingAs($perusahaan)
            ->from(route('perusahaan.lowongan.create'))
            ->post(route('perusahaan.lowongan.store'), $this->validLowonganPayload([
                'tanggal_mulai' => today()->addDays(10)->toDateString(),
                'batas_lamaran' => today()->addDays(2)->toDateString(),
            ]));

        $response
            ->assertRedirect(route('perusahaan.lowongan.create'))
            ->assertSessionHasErrors('batas_lamaran');

        $this->assertDatabaseCount('lowongan', 0);
    }

    public function test_expire_command_marks_past_active_lowongan_as_expired(): void
    {
        $pastLowongan = $this->createLowongan([
            'status' => 'aktif',
            'tanggal_mulai' => today()->subDays(10),
            'batas_lamaran' => today()->subDay(),
        ]);

        $futureLowongan = $this->createLowongan([
            'status' => 'aktif',
            'posisi' => 'Arsitek Masih Aktif',
            'tanggal_mulai' => today(),
            'batas_lamaran' => today()->addDays(7),
        ]);

        $this->artisan('lowongan:expire')
            ->expectsOutput('1 lowongan expired.')
            ->assertExitCode(0);

        $this->assertSame('expired', $pastLowongan->refresh()->status);
        $this->assertSame('aktif', $futureLowongan->refresh()->status);
    }

    public function test_public_lowongan_index_hides_expired_lowongan(): void
    {
        $this->createLowongan([
            'posisi' => 'Lowongan Publik Aktif',
            'status' => 'aktif',
            'tanggal_mulai' => today(),
            'batas_lamaran' => today()->addDays(7),
        ]);

        $this->createLowongan([
            'posisi' => 'Lowongan Publik Expired',
            'status' => 'expired',
            'tanggal_mulai' => today()->subDays(20),
            'batas_lamaran' => today()->subDay(),
        ]);

        $this->get(route('lowongan.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Lowongan/Index', false)
                ->has('jobs', 1)
                ->where('jobs.0.posisi', 'Lowongan Publik Aktif')
            );
    }

    public function test_public_lowongan_keyword_search_does_not_match_location(): void
    {
        $this->createLowongan([
            'posisi' => 'Drafter Interior',
            'perusahaan' => 'Studio Ruang',
            'kota' => 'Malang',
            'deskripsi' => 'Mengerjakan gambar teknis interior.',
            'status' => 'aktif',
            'tanggal_mulai' => today(),
            'batas_lamaran' => today()->addDays(7),
        ]);

        $this->get(route('lowongan.index', ['q' => 'Malang']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Lowongan/Index', false)
                ->has('jobs', 0)
            );

        $this->get(route('lowongan.index', ['l' => 'Malang']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Lowongan/Index', false)
                ->has('jobs', 1)
                ->where('jobs.0.kota', 'Malang')
            );
    }

    public function test_lowongan_starting_today_in_jakarta_is_publicly_visible(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-06-03 00:30:00', 'Asia/Jakarta'));

        try {
            $lowongan = $this->createLowongan([
                'posisi' => 'Lowongan Hari Ini WIB',
                'status' => 'aktif',
                'tanggal_mulai' => '2026-06-03',
                'batas_lamaran' => '2026-06-04',
            ]);

            $this->assertTrue(
                Lowongan::query()
                    ->publiclyAvailable()
                    ->whereKey($lowongan->id)
                    ->exists()
            );
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_perusahaan_dashboard_still_shows_expired_lowongan(): void
    {
        $perusahaan = $this->verifiedPerusahaan();

        $this->createLowongan([
            'user_id' => $perusahaan->id,
            'posisi' => 'Lowongan Expired Perusahaan',
            'status' => 'expired',
            'tanggal_mulai' => today()->subDays(20),
            'batas_lamaran' => today()->subDay(),
        ]);

        $this
            ->actingAs($perusahaan)
            ->get(route('perusahaan.lowongan.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Perusahaan/Lowongan/Index', false)
                ->has('lowongans', 1)
                ->where('lowongans.0.status', 'expired')
            );
    }

    public function test_arsitek_cannot_apply_to_expired_lowongan(): void
    {
        $arsitek = $this->verifiedArsitek();
        $expiredLowongan = $this->createLowongan([
            'status' => 'expired',
            'tanggal_mulai' => today()->subDays(20),
            'batas_lamaran' => today()->subDay(),
        ]);

        $response = $this
            ->actingAs($arsitek)
            ->post(route('arsitek.lamaran.store', $expiredLowongan), [
                'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
                'notes' => 'Saya tertarik dengan posisi ini.',
            ]);

        $response->assertSessionHas('error', 'Lowongan ini sudah tidak menerima lamaran.');
        $this->assertDatabaseCount('lamarans', 0);
    }

    public function test_unverified_perusahaan_cannot_create_lowongan(): void
    {
        $perusahaan = User::factory()->perusahaan()->create();

        $response = $this
            ->actingAs($perusahaan)
            ->post(route('perusahaan.lowongan.store'), $this->validLowonganPayload());

        $response
            ->assertRedirect(route('perusahaan.verifikasi.index'))
            ->assertSessionHas('error', 'Profil Anda harus diverifikasi admin terlebih dahulu sebelum melakukan aksi ini.');

        $this->assertDatabaseCount('lowongan', 0);
    }

    public function test_unverified_arsitek_cannot_apply_to_lowongan(): void
    {
        $arsitek = User::factory()->arsitek()->create();
        $lowongan = $this->createLowongan([
            'tanggal_mulai' => today(),
            'batas_lamaran' => today()->addDays(7),
        ]);

        $response = $this
            ->actingAs($arsitek)
            ->post(route('arsitek.lamaran.store', $lowongan), [
                'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
                'notes' => 'Saya tertarik dengan posisi ini.',
            ]);

        $response
            ->assertRedirect(route('arsitek.verifikasi.index'))
            ->assertSessionHas('error', 'Profil Anda harus diverifikasi admin terlebih dahulu sebelum melakukan aksi ini.');

        $this->assertDatabaseCount('lamarans', 0);
    }

    private function validLowonganPayload(array $overrides = []): array
    {
        return array_merge([
            'posisi' => 'Arsitek Proyek',
            'kota' => 'Malang',
            'tipe' => 'Full Time',
            'gaji' => 'Rp 8-12 jt',
            'deskripsi' => 'Mengerjakan desain dan koordinasi proyek arsitektur.',
            'syarat' => ['Menguasai AutoCAD', 'Memiliki portofolio'],
            'tanggung_jawab' => ['Membuat gambar kerja', 'Koordinasi dengan tim'],
            'tanggal_mulai' => today()->toDateString(),
            'batas_lamaran' => today()->addDays(30)->toDateString(),
        ], $overrides);
    }

    private function createLowongan(array $overrides = []): Lowongan
    {
        $userId = $overrides['user_id'] ?? User::factory()->perusahaan()->create()->id;
        unset($overrides['user_id']);

        $attributes = array_merge([
            'user_id' => $userId,
            'posisi' => 'Arsitek Proyek',
            'perusahaan' => 'Studio Rekrutmen',
            'kota' => 'Malang',
            'tipe' => 'Full Time',
            'gaji' => 'Rp 8-12 jt',
            'inisial' => 'SR',
            'rating' => 4.5,
            'deskripsi' => 'Mengerjakan desain dan koordinasi proyek arsitektur.',
            'syarat' => ['Menguasai AutoCAD'],
            'tanggung_jawab' => ['Membuat gambar kerja'],
            'status' => 'aktif',
            'deadline' => today()->addDays(30),
            'tanggal_mulai' => today(),
            'batas_lamaran' => today()->addDays(30),
        ], $overrides);

        return Lowongan::create($attributes);
    }

    private function verifiedPerusahaan(array $attributes = []): User
    {
        $user = User::factory()->perusahaan()->create($attributes);

        CompanyProfile::create([
            'user_id' => $user->id,
            'company_name' => $user->name,
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);

        return $user;
    }

    private function verifiedArsitek(array $attributes = []): User
    {
        $user = User::factory()->arsitek()->create($attributes);

        ArsitekProfile::factory()->create([
            'user_id' => $user->id,
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);

        return $user;
    }
}
