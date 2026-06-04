<?php

namespace Database\Seeders;

use App\Models\Lowongan;
use Illuminate\Database\Seeder;

class LowonganSeeder extends Seeder
{
    public function run(): void
    {
        $lowongan = [
            [
                'posisi' => 'Senior Arsitek',
                'perusahaan' => 'Arkindo Studio',
                'kota' => 'Jakarta Selatan',
                'tipe' => 'Full Time',
                'gaji' => 'Rp 12–18 jt',
                'inisial' => 'A',
                'rating' => 4.8,
                'deskripsi' => 'Kami mencari Senior Arsitek yang berbakat untuk bergabung dengan tim desain kami yang berkembang pesat. Anda akan bertanggung jawab atas proyek-proyek residensial mewah dari konsepsi hingga penyelesaian.',
                'syarat' => [
                    'Minimal 5 tahun pengalaman di firma arsitektur ternama.',
                    'Gelar Sarjana Arsitektur dari universitas terakreditasi.',
                    'Keahlian mendalam dalam Revit, AutoCAD, dan SketchUp.',
                    'Kemampuan komunikasi yang kuat dan kepemimpinan tim.',
                    'Portofolio yang menunjukkan proyek desain berkualitas tinggi.',
                ],
                'tanggung_jawab' => [
                    'Memimpin fase desain dan dokumentasi teknis.',
                    'Mengoordinasikan kolaborasi dengan konsultan eksternal.',
                    'Memastikan kepatuhan terhadap standar bangunan dan anggaran.',
                    'Mementor arsitek junior dalam pengembangan desain.',
                ],
            ],
            [
                'posisi' => 'BIM Specialist',
                'perusahaan' => 'PT Graha Design',
                'kota' => 'Surabaya',
                'tipe' => 'Full Time',
                'gaji' => 'Rp 8–12 jt',
                'inisial' => 'G',
                'rating' => 4.2,
                'deskripsi' => 'Sebagai BIM Specialist, Anda akan memandu implementasi BIM dalam proyek-proyek skala besar kami, memastikan alur kerja digital yang efisien dan akurat.',
                'syarat' => [
                    'Sertifikasi BIM Professional lebih diutamakan.',
                    'Pengalaman minimal 3 tahun dalam manajemen proyek BIM.',
                    'Sangat mahir dalam Autodesk Revit dan Navisworks.',
                    'Pemahaman mendalam tentang standar ISO 19650.',
                ],
                'tanggung_jawab' => [
                    'Mengembangkan dan mengelola Rencana Pelaksanaan BIM (BEP).',
                    'Melakukan koordinasi benturan (clash detection) secara rutin.',
                    'Mengelola perpustakaan konten dan standar desain digital.',
                ],
            ],
            [
                'posisi' => 'Interior Designer',
                'perusahaan' => 'Kana Interiors',
                'kota' => 'Bandung',
                'tipe' => 'Freelance',
                'gaji' => 'Nego per Proyek',
                'inisial' => 'K',
                'rating' => 4.5,
                'deskripsi' => 'Kami mencari desainer interior kreatif yang berspesialisasi dalam ruang komersial modern untuk proyek renovasi kantor terbaru kami di Bandung.',
                'syarat' => [
                    'Pendidikan S1 Desain Interior.',
                    'Keahlian dalam 3DS Max atau V-Ray untuk rendering.',
                    'Pengetahuan mendalam tentang material dan vendor lokal.',
                ],
                'tanggung_jawab' => [
                    'Menyusun konsep moodboard dan presentasi klien.',
                    'Membuat gambar kerja interior yang mendetail.',
                    'Melakukan supervisi artistik di lokasi proyek.',
                ],
            ],
            [
                'posisi' => 'Urban Planner',
                'perusahaan' => 'PT Rekayasa Kota Nusantara',
                'kota' => 'Jakarta Pusat',
                'tipe' => 'Full Time',
                'gaji' => 'Rp 15–22 jt',
                'inisial' => 'R',
                'rating' => 4.6,
                'deskripsi' => 'Bergabunglah dengan tim perencana kota kami untuk merancang solusi perkotaan berkelanjutan yang membentuk masa depan kota-kota di Indonesia.',
                'syarat' => [
                    'Gelar S1/S2 Perencanaan Wilayah dan Kota.',
                    'Pengalaman minimal 4 tahun di bidang perencanaan kota.',
                    'Mahir dalam GIS, AutoCAD, dan software perencanaan terkait.',
                    'Kemampuan analisis data spasial yang kuat.',
                ],
                'tanggung_jawab' => [
                    'Menyusun rencana tata ruang wilayah kota/kabupaten.',
                    'Melakukan studi kelayakan proyek pengembangan kawasan.',
                    'Berkoordinasi dengan pemerintah daerah dan stakeholder.',
                ],
            ],
            [
                'posisi' => 'Landscape Architect',
                'perusahaan' => 'Taman Hijau Indonesia',
                'kota' => 'Bali',
                'tipe' => 'Contract',
                'gaji' => 'Rp 10–16 jt',
                'inisial' => 'T',
                'rating' => 4.7,
                'deskripsi' => 'Kami membutuhkan Landscape Architect berpengalaman untuk proyek resort mewah dan ruang publik di seluruh Bali.',
                'syarat' => [
                    'S1 Arsitektur Lanskap atau bidang terkait.',
                    'Minimal 3 tahun pengalaman desain lanskap.',
                    'Keahlian dalam Lumion, SketchUp, dan AutoCAD.',
                    'Memahami flora tropis dan teknik lanskap berkelanjutan.',
                ],
                'tanggung_jawab' => [
                    'Mendesain master plan lanskap untuk proyek resort.',
                    'Memilih spesies tanaman dan material hardscape.',
                    'Supervisi implementasi desain di lapangan.',
                ],
            ],
            [
                'posisi' => 'Junior Arsitek',
                'perusahaan' => 'Studio Empat Sembilan',
                'kota' => 'Yogyakarta',
                'tipe' => 'Full Time',
                'gaji' => 'Rp 5–8 jt',
                'inisial' => 'S',
                'rating' => 4.3,
                'deskripsi' => 'Kesempatan luar biasa bagi fresh graduate untuk belajar dan berkembang di studio arsitektur yang berfokus pada desain residensial kontemporer.',
                'syarat' => [
                    'Fresh graduate S1 Arsitektur dipersilakan melamar.',
                    'Memiliki portofolio akademis yang kuat.',
                    'Familiar dengan Revit, SketchUp, dan Adobe Creative Suite.',
                    'Bersedia belajar dan bekerja di lingkungan tim.',
                ],
                'tanggung_jawab' => [
                    'Membantu proses desain dan membuat gambar kerja.',
                    'Membuat model 3D dan rendering presentasi.',
                    'Melakukan riset material dan referensi desain.',
                ],
            ],
            [
                'posisi' => 'Drafter Arsitektur',
                'perusahaan' => 'CV Bangun Persada',
                'kota' => 'Malang',
                'tipe' => 'Full Time',
                'gaji' => 'Rp 4–6 jt',
                'inisial' => 'B',
                'rating' => 3.9,
                'deskripsi' => 'Dicari drafter arsitektur yang teliti dan cepat untuk membantu produksi gambar kerja proyek residensial di wilayah Malang Raya.',
                'syarat' => [
                    'D3/S1 Teknik Arsitektur atau Teknik Sipil.',
                    'Mahir AutoCAD 2D/3D.',
                    'Mampu membaca dan menginterpretasi gambar arsitektur.',
                    'Teliti, disiplin, dan mampu bekerja di bawah deadline.',
                ],
                'tanggung_jawab' => [
                    'Membuat detail gambar kerja arsitektur.',
                    'Mengupdate revisi gambar sesuai arahan arsitek.',
                    'Menyusun dokumen tender dan RAB.',
                ],
            ],
            [
                'posisi' => 'Arsitek MEP',
                'perusahaan' => 'PT Infrastruktor Muda',
                'kota' => 'Surabaya',
                'tipe' => 'Full Time',
                'gaji' => 'Rp 10–15 jt',
                'inisial' => 'I',
                'rating' => 4.1,
                'deskripsi' => 'Kami mencari Arsitek MEP untuk mengkoordinasikan sistem mekanikal, elektrikal, dan plumbing pada proyek-proyek gedung bertingkat tinggi.',
                'syarat' => [
                    'S1 Teknik Mesin, Elektro, atau Arsitektur.',
                    'Pengalaman minimal 3 tahun di bidang MEP.',
                    'Keahlian dalam Revit MEP dan AutoCAD MEP.',
                    'Memahami standar SNI dan regulasi bangunan.',
                ],
                'tanggung_jawab' => [
                    'Mendesain sistem MEP untuk bangunan komersial.',
                    'Berkoordinasi dengan tim arsitektur dan struktur.',
                    'Melakukan review dan clash detection.',
                ],
            ],
            [
                'posisi' => 'Project Manager Arsitektur',
                'perusahaan' => 'Aedifica Nusantara',
                'kota' => 'Jakarta Barat',
                'tipe' => 'Full Time',
                'gaji' => 'Rp 20–30 jt',
                'inisial' => 'A',
                'rating' => 4.9,
                'deskripsi' => 'Bergabunglah sebagai Project Manager yang akan mengelola proyek-proyek arsitektur berskala besar dari tahap perencanaan hingga serah terima.',
                'syarat' => [
                    'S1 Arsitektur atau Teknik Sipil.',
                    'Minimal 7 tahun pengalaman, 3 tahun sebagai PM.',
                    'Sertifikasi PMP atau AAPM lebih diutamakan.',
                    'Kemampuan manajemen anggaran dan timeline proyek.',
                    'Kepemimpinan yang kuat dan komunikasi yang excellent.',
                ],
                'tanggung_jawab' => [
                    'Mengelola seluruh siklus hidup proyek arsitektur.',
                    'Memimpin komunikasi dengan klien dan stakeholder.',
                    'Memastikan proyek selesai tepat waktu dan sesuai anggaran.',
                    'Menyusun laporan proyek bulanan untuk manajemen.',
                ],
            ],
            [
                'posisi' => '3D Visualizer',
                'perusahaan' => 'Render Studio ID',
                'kota' => 'Bandung',
                'tipe' => 'Freelance',
                'gaji' => 'Rp 3–8 jt per Proyek',
                'inisial' => 'R',
                'rating' => 4.4,
                'deskripsi' => 'Kami mencari 3D Visualizer freelance yang mampu menghasilkan rendering fotorealistik berkualitas tinggi untuk proyek arsitektur dan interior.',
                'syarat' => [
                    'Mahir dalam 3DS Max, V-Ray / Corona Renderer.',
                    'Portofolio rendering fotorealistik yang kuat.',
                    'Memahami pencahayaan, material, dan komposisi.',
                    'Terbiasa bekerja dengan deadline ketat.',
                ],
                'tanggung_jawab' => [
                    'Membuat visualisasi 3D eksterior dan interior.',
                    'Mengolah post-production menggunakan Photoshop.',
                    'Berkolaborasi dengan tim arsitek untuk akurasi desain.',
                ],
            ],
        ];

        foreach ($lowongan as $job) {
            Lowongan::create(array_merge($job, [
                'status' => $job['status'] ?? 'aktif',
                'tanggal_mulai' => now()->subDays(7)->toDateString(),
                'batas_lamaran' => now()->addDays(30)->toDateString(),
                'deadline' => now()->addDays(30)->toDateString(),
            ]));
        }
    }
}
