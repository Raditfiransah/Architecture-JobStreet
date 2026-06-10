<?php

namespace Database\Seeders;

use App\Models\Proposal;
use App\Models\Proyek;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProposalSeeder extends Seeder
{
    public function run(): void
    {
        // Map proyek by title -> get user (arsitek) IDs
        // We'll match by email of arsitek and title of proyek seeded above

        $arsitekEmails = [
            'rizky.aditya@example.com',
            'sari.dewi@example.com',
            'bimo.wahyu@example.com',
            'cahya.puspita@example.com',
            'dika.firmansyah@example.com',
            'fajar.eka@example.com',
            'gita.nirmala@example.com',
            // also the default ones from DatabaseSeeder
            'arsitek@arsitek.com',
            'arsitek@example.com',
        ];

        $arsiteks = User::whereIn('email', $arsitekEmails)->get()->keyBy('email');

        // Helper to get arsitek user by email, skip if not found
        $getArsitek = fn($email) => $arsiteks[$email] ?? null;

        $proposals = [
            // ── Proyek: Desain Rumah Tinggal Mewah Pondok Indah ─────────────────
            [
                'proyek_title' => 'Desain Rumah Tinggal Mewah Pondok Indah',
                'arsitek_email' => 'rizky.aditya@example.com',
                'bid_amount'   => 80000000,
                'estimated_time' => 45,
                'status'       => 'diterima',
                'description'  => "Selamat siang Pak Budi,\n\nSaya Rizky Aditya, arsitek dengan 8 tahun pengalaman di proyek residensial premium Jakarta. Saya sangat tertarik dengan proyek Bapak di Pondok Indah.\n\nTawaran saya:\n- Konsep Modern Japandi yang terinspirasi ketenangan dan fungsi maksimal.\n- 10 view render fotorealistik eksterior & interior menggunakan Lumion 12.\n- Animasi walkthrough 3D durasi 2 menit.\n- DED Arsitektur lengkap + koordinasi struktur & MEP.\n- RAB detail per item pekerjaan.\n\nWaktu pengerjaan: 45 hari kerja, dengan milestone revisi setiap fase.\n\nSilahkan lihat portofolio saya di website. Saya siap konsultasi kapan saja.",
            ],
            [
                'proyek_title' => 'Desain Rumah Tinggal Mewah Pondok Indah',
                'arsitek_email' => 'fajar.eka@example.com',
                'bid_amount'   => 95000000,
                'estimated_time' => 60,
                'status'       => 'ditolak',
                'description'  => "Halo Pak Budi,\n\nSaya Fajar, arsitek senior spesialisasi hospitality dan residensial mewah. Pengalaman saya di proyek setara nilainya di atas Rp 5 miliar.\n\nSaya menawarkan paket premium dengan 15 view render, detail arsitektur komplit, dan konsultasi interior FF&E gratis senilai Rp 10 juta. Biaya ini sudah termasuk 2 putaran revisi desain.\n\nKarena saya saat ini berbasis di Medan, pertemuan bisa dilakukan via Zoom atau saya siap terbang ke Jakarta untuk presentasi pertama.",
            ],
            [
                'proyek_title' => 'Desain Rumah Tinggal Mewah Pondok Indah',
                'arsitek_email' => 'gita.nirmala@example.com',
                'bid_amount'   => 70000000,
                'estimated_time' => 40,
                'status'       => 'pending',
                'description'  => "Salam kenal Pak Budi,\n\nSaya Gita Nirmala, arsitek dari Malang dengan spesialisasi desain inklusif dan fungsional. Saya berpengalaman mengoptimalkan ruang besar agar terasa nyaman dan efisien.\n\nPenawaran:\n- Desain Modern Minimalis + kajian biophilic untuk pencahayaan alami optimal.\n- 8 render 3D + denah lengkap semua lantai.\n- RAB + milestone pembayaran progres.\n- Gratis konsultasi landscape taman depan dan kolam.",
            ],

            // ── Proyek: Renovasi Total Interior Apartemen Senopati ──────────────
            [
                'proyek_title' => 'Renovasi Total Interior Apartemen Senopati Suites',
                'arsitek_email' => 'sari.dewi@example.com',
                'bid_amount'   => 20000000,
                'estimated_time' => 21,
                'status'       => 'diterima',
                'description'  => "Halo Pak Budi,\n\nSaya Sari Dewi, desainer interior dengan portofolio 30+ proyek kafe dan apartemen mewah di Surabaya dan Jakarta. Saya sangat tertarik dengan proyek renovasi apartemen Bapak.\n\nKonsep yang saya usulkan: Contemporary Luxury dengan palet earth tone—greige, terracotta, dan warm white. Material utama: microcement floor, textured wall panel, dan custom joinery.\n\nTimeline 21 hari kerja termasuk 3 hari material procurement.\nBiaya sudah termasuk gambar kerja detail, RAB, dan 2 revisi desain.",
            ],
            [
                'proyek_title' => 'Renovasi Total Interior Apartemen Senopati Suites',
                'arsitek_email' => 'rizky.aditya@example.com',
                'bid_amount'   => 18500000,
                'estimated_time' => 25,
                'status'       => 'pending',
                'description'  => "Pak Budi yang terhormat,\n\nSaya Rizky Aditya dari Jakarta Selatan. Meskipun keahlian utama saya adalah desain bangunan baru, saya juga aktif mengerjakan proyek renovasi interior apartemen premium.\n\nSaya menawarkan konsep Japandi yang lebih minimalis dengan furniture custom built-in untuk memaksimalkan ruang 110m². Estimasi 25 hari dengan buffer revisi.",
            ],

            // ── Proyek: Desain Vila Tropis Trawas ───────────────────────────────
            [
                'proyek_title' => 'Desain Vila Tropis di Kawasan Trawas Mojokerto',
                'arsitek_email' => 'rizky.aditya@example.com',
                'bid_amount'   => 55000000,
                'estimated_time' => 35,
                'status'       => 'pending',
                'description'  => "Selamat siang Bu Indira,\n\nProyek Vila Trawas ini sangat menggugah semangat saya! Konsep Tropical Modern di pegunungan adalah favorit saya karena tantangan integrasi alam dan arsitekturnya.\n\nPenawaran saya:\n- Site analysis lengkap (angin, kontur, orientasi matahari).\n- Desain yang memaksimalkan view alam Trawas dari setiap ruang.\n- Infinity pool terintegrasi dengan teras utama.\n- Render 3D eksterior siang & malam + bird eye view.\n- Koordinasi kontraktor lokal bila dibutuhkan.",
            ],
            [
                'proyek_title' => 'Desain Vila Tropis di Kawasan Trawas Mojokerto',
                'arsitek_email' => 'cahya.puspita@example.com',
                'bid_amount'   => 58000000,
                'estimated_time' => 40,
                'status'       => 'pending',
                'description'  => "Salam, Bu Indira.\n\nSaya Cahya Puspita, Landscape Architect sekaligus arsitek yang sangat berpengalaman di proyek vila alam. Keahlian ganda saya (arsitektur + lanskap) sangat cocok untuk proyek ini karena taman dan bangunan perlu dirancang secara terintegrasi.\n\nSaya akan menawarkan masterplan terpadu dari bangunan, kolam renang, taman tropis, dan sistem drainase bioretention yang ramah lingkungan.",
            ],

            // ── Proyek: Masterplan Perumahan Green Valley Lembang ───────────────
            [
                'proyek_title' => 'Masterplan Perumahan Green Valley Lembang',
                'arsitek_email' => 'bimo.wahyu@example.com',
                'bid_amount'   => 280000000,
                'estimated_time' => 90,
                'status'       => 'pending',
                'description'  => "Kepada Tim PT Bandung Property,\n\nSaya Bimo Wahyu Nugroho, Urban Planner lulusan UGM dengan spesialisasi perancangan kawasan hunian berkelanjutan. Proyek masterplan perumahan eco-green ini sangat sesuai dengan keahlian dan passion saya.\n\nPendekatan saya:\n- Analisis tapak komprehensif: topografi, hidrologi, vegetasi eksisting.\n- Masterplan kawasan dengan konsep cluster yang memaksimalkan area hijau (min. 40% dari total lahan).\n- Sistem drainase bioretention terintegrasi.\n- Desain tipikal rumah 3 tipe.\n- Dokumen DED + AMDAL awal.",
            ],
            [
                'proyek_title' => 'Masterplan Perumahan Green Valley Lembang',
                'arsitek_email' => 'cahya.puspita@example.com',
                'bid_amount'   => 310000000,
                'estimated_time' => 120,
                'status'       => 'pending',
                'description'  => "Halo Pak Hendra,\n\nSaya Cahya Puspita, Landscape Architect berpengalaman di masterplan perumahan. Penawaran saya mencakup pendekatan holistik: arsitektur kawasan, desain lansekap seluruh fasilitas, dan panduan material untuk semua unit.\n\nKeunggulan: Kolaborasi langsung dengan tim sipil untuk sistem drainase terpadu sehingga tidak ada double-cost di kemudian hari.",
            ],

            // ── Proyek: Redesain Kafe Buku "Halaman" Yogyakarta ─────────────────
            [
                'proyek_title' => 'Redesain Kafe Buku "Halaman" Yogyakarta',
                'arsitek_email' => 'sari.dewi@example.com',
                'bid_amount'   => 16500000,
                'estimated_time' => 18,
                'status'       => 'diterima',
                'description'  => "Halo Mbak Putri,\n\nKafe buku adalah proyek yang paling saya sukai! Saya Sari Dewi, desainer interior dengan banyak portofolio kafe di Surabaya dan Jakarta. Saya mengerti betul apa yang membuat pengunjung betah berlama-lama di sebuah third place.\n\nKonsep saya: Warm Industrial Bookshop Vibes—exposed brick, warm Edison lighting, rak buku floor-to-ceiling, dan hidden nook. Area rooftop akan saya desain dengan konsep open-air reading garden.",
            ],

            // ── Proyek: Desain Sekolah Alam Inklusif ─────────────────────────────
            [
                'proyek_title' => 'Desain Sekolah Alam Inklusif – Bekasi',
                'arsitek_email' => 'gita.nirmala@example.com',
                'bid_amount'   => 88000000,
                'estimated_time' => 60,
                'status'       => 'diterima',
                'description'  => "Kepada Yayasan Mekar Nusantara,\n\nSaya Gita Nirmala, arsitek spesialis fasilitas publik dan universal design. Proyek sekolah alam inklusif ini adalah panggilan jiwa saya—perpaduan arsitektur ramah lingkungan, inklusivitas, dan pendidikan berbasis alam.\n\nPenawaran saya:\n- Masterplan sekolah alam 3 hektar yang terintegrasi dengan ekosistem alami.\n- Desain 8 kelas outdoor/semi-outdoor yang adaptif terhadap cuaca.\n- Sistem off-grid lengkap: solar panel + rainwater harvesting.\n- Semua fasilitas memenuhi standar ramah disabilitas (WCAG & SNIB).\n- Perpustakaan terbuka dan kebun edukasi.\n\nBiaya sudah termasuk DED lengkap, gambar MEP, RAB detail, dan pendampingan dokumen hibah CSR.",
            ],
            [
                'proyek_title' => 'Desain Sekolah Alam Inklusif – Bekasi',
                'arsitek_email' => 'bimo.wahyu@example.com',
                'bid_amount'   => 92000000,
                'estimated_time' => 75,
                'status'       => 'ditolak',
                'description'  => "Salam kenal Yayasan Mekar Nusantara,\n\nSaya Bimo Wahyu, Urban Planner yang juga berpengalaman dalam perancangan kawasan pendidikan. Pendekatan saya lebih holistik dari sisi tata kawasan—memastikan aksesibilitas dan hubungan ruang luar sekolah dengan komunitas sekitar.",
            ],

            // ── Proyek: Konversi Rumah Tua Kolonial ───────────────────────────────
            [
                'proyek_title' => 'Konversi Rumah Tua Kolonial Menjadi Guesthouse',
                'arsitek_email' => 'rizky.aditya@example.com',
                'bid_amount'   => 70000000,
                'estimated_time' => 50,
                'status'       => 'pending',
                'description'  => "Halo Pak Reza,\n\nProyek heritage conversion sangat jarang saya temui dan ini justru yang paling menantang sekaligus memuaskan! Saya Rizky Aditya, arsitek yang pernah menangani konservasi rumah kolonial di Menteng dan Cikini, Jakarta.\n\nPendekatan: Adaptive reuse—mempertahankan elemen asli bernilai tinggi (tegel terazzo, plafon PVC, jendela jalusi kayu) sambil menambahkan fasilitas modern secara reversibel.\n\nOutput: Dokumen heritage assessment, gambar existing & proposed, detail renovasi, RAB, dan panduan bahan pengganti yang autentik.",
            ],
            [
                'proyek_title' => 'Konversi Rumah Tua Kolonial Menjadi Guesthouse',
                'arsitek_email' => 'fajar.eka@example.com',
                'bid_amount'   => 68000000,
                'estimated_time' => 45,
                'status'       => 'pending',
                'description'  => "Pak Reza yang terhormat,\n\nSaya Fajar Eka, arsitek senior dengan pengalaman konversi bangunan lama menjadi hospitality boutique. Pengalaman saya di Boutique Hotel di Danau Toba juga melibatkan bangunan heritage.\n\nSaya akan memastikan konsep guesthouse Anda menjadi destinasi wisata tersendiri berkat daya tarik arsitektur kolonialnya yang terawat.",
            ],
        ];

        foreach ($proposals as $data) {
            $proyek = Proyek::where('title', $data['proyek_title'])->first();
            $arsitek = $getArsitek($data['arsitek_email']);

            if (! $proyek || ! $arsitek) {
                continue;
            }

            // Avoid duplicate proposals from same arsitek to same proyek
            Proposal::updateOrCreate(
                [
                    'user_id'   => $arsitek->id,
                    'proyek_id' => $proyek->id,
                ],
                [
                    'bid_amount'      => $data['bid_amount'],
                    'estimated_time'  => $data['estimated_time'],
                    'description'     => $data['description'],
                    'status'          => $data['status'],
                    'attachment_path' => null,
                ]
            );
        }
    }
}
