<?php

namespace Database\Seeders;

use App\Models\InfoHub;
use App\Models\User;
use Illuminate\Database\Seeder;

class InfoHubExtraSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@admin.com')
            ->orWhere('email', 'admin@example.com')
            ->first();

        if (! $admin) {
            return;
        }

        $infoHubs = [
            // ── EVENT ───────────────────────────────────────────────────────────
            [
                'judul'         => 'Kongres Arsitek Indonesia 2026 – "Arsitektur untuk Semua"',
                'kategori'      => 'Event',
                'deskripsi'     => "Ikatan Arsitek Indonesia (IAI) menyelenggarakan Kongres Arsitek Indonesia 2026 dengan tema besar \"Arsitektur untuk Semua\". Kongres ini menghadirkan lebih dari 200 pembicara dari dalam dan luar negeri, pameran inovasi bahan bangunan, dan sesi networking dengan arsitek terkemuka ASEAN.\n\nTanggal: 10–12 Juli 2026\nLokasi: Bali International Convention Centre (BICC), Nusa Dua\nPendaftaran: https://kongresiai2026.id",
                'gambar_poster' => null,
                'created_at'    => now()->subDays(14),
            ],
            [
                'judul'         => 'Workshop BIM Level Up: Dari Drafter ke BIM Specialist',
                'kategori'      => 'Event',
                'deskripsi'     => "Tingkatkan karier Anda ke level berikutnya! Workshop intensif 2 hari ini dirancang untuk drafter dan junior arsitek yang ingin menguasai Autodesk Revit dan workflow BIM profesional.\n\nMateri:\n- Revit Architecture Fundamentals\n- Family Creation & Custom Components\n- Koordinasi MEP & Clash Detection dengan Navisworks\n- BIM 360 Collaboration\n\nFasilitator: Tim Bersertifikat Autodesk dari PT Graha Design Indonesia\nTanggal: 20-21 Juni 2026\nLokasi: Surabaya (Graha Design Training Center)\nBiaya: Rp 1.500.000 / peserta (sudah termasuk modul & sertifikat)",
                'gambar_poster' => null,
                'created_at'    => now()->subDays(8),
            ],
            [
                'judul'         => 'Open House Proyek: Kunjungan Lapangan Rusun Merak Jaya',
                'kategori'      => 'Event',
                'deskripsi'     => "Dapatkan kesempatan langka untuk mengunjungi dan mempelajari langsung konstruksi proyek Rumah Susun Merak Jaya—hunian vertikal terjangkau 20 lantai pertama di Tangerang yang menggunakan teknologi precast modular.\n\nPemandu: Tim Arsitek & Kontraktor Utama\nKapasitas: 30 peserta\nTanggal: 28 Juni 2026\nTitik kumpul: Kantor Pusat PT Aedifica Nusantara, Jakarta Barat\n\nDaftar sekarang sebelum penuh!",
                'gambar_poster' => null,
                'created_at'    => now()->subDays(4),
            ],
            // ── SAYEMBARA ────────────────────────────────────────────────────────
            [
                'judul'         => 'Sayembara Desain Terminal Bus Tipe A – Kementerian Perhubungan',
                'kategori'      => 'Sayembara',
                'deskripsi'     => "Kementerian Perhubungan RI mengundang biro arsitektur dan perencana kota untuk ikut serta dalam sayembara desain Terminal Bus Tipe A generasi baru yang manusiawi, efisien, dan ramah lingkungan.\n\nTotal Hadiah:\n- Juara 1: Rp 300.000.000 + kontrak perencanaan\n- Juara 2: Rp 150.000.000\n- Juara 3: Rp 75.000.000\n- 5 Penghargaan Harapan @ Rp 15.000.000\n\nPeserta: Biro arsitektur ber-SBU (Sertifikat Badan Usaha) aktif\nDeadline Registrasi: 15 Juli 2026\nDeadline Karya: 15 Agustus 2026\nInfo lengkap: www.kemenhub.go.id/sayembara2026",
                'gambar_poster' => null,
                'created_at'    => now()->subDays(20),
            ],
            [
                'judul'         => 'Sayembara Desain Monumen Kemerdekaan 2045 – Visi Indonesia Emas',
                'kategori'      => 'Sayembara',
                'deskripsi'     => "Dalam rangka memperingati 100 tahun kemerdekaan Indonesia pada 2045, Badan Perencanaan Nasional (Bappenas) membuka sayembara internasional untuk desain Monumen Kemerdekaan Nasional yang baru.\n\nTema: \"Indonesia Emas – Warisan untuk Generasi Berikutnya\"\n\nPersyaratan:\n- Terbuka untuk arsitek nasional dan internasional (kolaborasi diperbolehkan)\n- Lokasi: Kawasan Gambir, Jakarta Pusat\n- Luas site: 2,5 hektar\n\nHadiah:\n- Juara Utama: Rp 1.000.000.000 + kesempatan pengerjaan detail\n- Runner up: Rp 400.000.000\n\nDeadline: 31 Agustus 2026",
                'gambar_poster' => null,
                'created_at'    => now()->subDays(30),
            ],
            // ── MAGANG ──────────────────────────────────────────────────────────
            [
                'judul'         => 'Program Magang 6 Bulan – PT Aedifica Nusantara (Jakarta)',
                'kategori'      => 'Magang',
                'deskripsi'     => "PT Aedifica Nusantara membuka program magang intensif 6 bulan untuk mahasiswa arsitektur, teknik sipil, dan manajemen konstruksi. Peserta akan terlibat langsung di proyek gedung bertingkat aktif dan belajar dari PM berpengalaman.\n\nKualifikasi:\n- Mahasiswa semester 6 ke atas atau fresh graduate\n- IPK minimal 3.00\n- Bersedia berkomitmen 6 bulan penuh (Oktober 2026 – Maret 2027)\n\nFasilitas:\n- Uang saku: Rp 3.500.000/bulan\n- Sertifikat magang + surat rekomendasi\n- Peluang rekrutmen langsung\n\nLamar via: karir@aedifica.co.id",
                'gambar_poster' => null,
                'created_at'    => now()->subDays(6),
            ],
            [
                'judul'         => 'Magang Arsitek Junior – Arkindo Studio Jakarta (Agustus 2026)',
                'kategori'      => 'Magang',
                'deskripsi'     => "Arkindo Studio membuka peluang magang eksklusif untuk 2 mahasiswa arsitektur terbaik yang ingin merasakan pengalaman berkarya di biro desain premium.\n\nDurasi: 3 bulan (Agustus – Oktober 2026)\n\nYang akan Anda pelajari:\n- Proses desain residensial mewah dari brief hingga construction document\n- Teknik presentasi klien berpengalaman\n- Produksi rendering 3D berstandar komersial\n- Site visit dan koordinasi kontraktor\n\nUang saku: Rp 2.000.000/bulan + sertifikat\nKirim portofolio ke: magang@arkindo.studio",
                'gambar_poster' => null,
                'created_at'    => now()->subDays(9),
            ],
            [
                'judul'         => 'Magang Urban Planner – Dinas Tata Ruang Kota Surabaya',
                'kategori'      => 'Magang',
                'deskripsi'     => "Dinas Tata Ruang dan Bangunan (DTRB) Kota Surabaya bekerja sama dengan Universitas ITS dan UNAIR membuka program magang 3 bulan untuk mahasiswa PWK (Perencanaan Wilayah dan Kota) atau Arsitektur.\n\nTopik pekerjaan: Analisis perubahan penggunaan lahan di kawasan pinggiran kota, pembuatan peta GIS, dan penyusunan rekomendasi kebijakan.\n\nKuota: 10 mahasiswa\nPeriode: Juli – September 2026\nDaftar: magang.dtrb@surabaya.go.id",
                'gambar_poster' => null,
                'created_at'    => now()->subDays(15),
            ],
        ];

        foreach ($infoHubs as $info) {
            InfoHub::create(array_merge($info, ['admin_id' => $admin->id]));
        }
    }
}
