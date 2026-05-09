<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InfoHub;
use App\Models\User;

class InfoHubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@admin.com')->first();

        if (!$admin) {
            $admin = User::factory()->admin()->create([
                'name' => 'Admin System',
                'email' => 'admin@admin.com',
            ]);
        }

        $infoHubs = [
            [
                'admin_id' => $admin->id,
                'judul' => 'Pameran Arsitektur Nasional: Masa Depan Tata Kota',
                'kategori' => 'Event',
                'deskripsi' => "Hadirilah pameran arsitektur terbesar tahun ini yang akan membahas konsep tata kota masa depan. Akan ada banyak praktisi terkemuka, diskusi panel, dan pameran maket inovatif dari berbagai biro arsitektur.\n\nTanggal: 20-25 Mei 2026\nLokasi: Jakarta Convention Center",
                'gambar_poster' => 'infohub/event_poster_1778087536714.png',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'admin_id' => $admin->id,
                'judul' => 'Sayembara Desain Ruang Terbuka Hijau IKN',
                'kategori' => 'Sayembara',
                'deskripsi' => "Otorita IKN mengundang seluruh arsitek profesional dan mahasiswa arsitektur untuk mengikuti sayembara perancangan Ruang Terbuka Hijau (RTH) di zona inti IKN.\n\nTotal Hadiah: Rp 500.000.000\nDeadline Pengumpulan Karya: 30 Juni 2026\nSyarat & Ketentuan dapat diunduh pada portal resmi.",
                'gambar_poster' => 'infohub/sayembara_poster_1778087552436.png',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'admin_id' => $admin->id,
                'judul' => 'Program Magang Arsitek - PT Pembangunan Perumahan (PP)',
                'kategori' => 'Magang',
                'deskripsi' => "Kesempatan emas untuk mahasiswa tingkat akhir atau fresh graduate arsitektur! Bergabunglah dalam program magang selama 6 bulan di PT PP dan terlibat langsung dalam proyek-proyek infrastruktur berskala nasional.\n\nFasilitas: Uang saku, sertifikat magang, kesempatan direkrut menjadi pegawai tetap.",
                'gambar_poster' => 'infohub/magang_poster_1778087571278.png',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'admin_id' => $admin->id,
                'judul' => 'Webinar: Integrasi AI dalam Desain Arsitektur',
                'kategori' => 'Event',
                'deskripsi' => "Bagaimana Artificial Intelligence mengubah lanskap dunia arsitektur? Temukan jawabannya di webinar interaktif kami akhir pekan ini.\n\nPembicara: Dr. Budi Setiawan (AIA)\nTanggal: 15 Mei 2026 (Online via Zoom)",
                'gambar_poster' => null,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
        ];

        foreach ($infoHubs as $info) {
            InfoHub::create($info);
        }
    }
}
