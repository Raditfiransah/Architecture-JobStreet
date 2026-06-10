<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use App\Models\Lowongan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            $company = $this->companyUserFor($job['perusahaan'], $job['kota']);

            Lowongan::updateOrCreate([
                'posisi' => $job['posisi'],
                'perusahaan' => $job['perusahaan'],
            ], array_merge($job, [
                'user_id' => $company->id,
                'status' => $job['status'] ?? 'aktif',
                'tanggal_mulai' => now()->subDays(7)->toDateString(),
                'batas_lamaran' => now()->addDays(30)->toDateString(),
                'deadline' => now()->addDays(30)->toDateString(),
            ]));
        }
    }

    private function companyUserFor(string $companyName, string $location): User
    {
        $email = Str::slug($companyName) . '@example.com';

        $user = User::updateOrCreate([
            'email' => $email,
        ], [
            'name' => $companyName,
            'password' => Hash::make('password'),
            'role' => 'perusahaan',
            'is_active' => true,
            'is_verified' => true,
            'email_verified_at' => now(),
            'location' => $location,
        ]);

        // Map known companies to richer profiles
        $profileData = $this->knownCompanyProfile($companyName, $location);

        CompanyProfile::updateOrCreate([
            'user_id' => $user->id,
        ], $profileData);

        return $user;
    }

    private function knownCompanyProfile(string $companyName, string $location): array
    {
        $profiles = [
            'Arkindo Studio' => [
                'company_name'       => 'Arkindo Studio',
                'company_desc'       => 'Arkindo Studio adalah biro arsitektur terkemuka yang berfokus pada desain residensial mewah, hotel bintang lima, dan kawasan komersial premium. Didirikan oleh arsitek-arsitek berpengalaman yang percaya bahwa desain harus bermakna sekaligus indah.',
                'industry'           => 'Jasa Arsitektur & Desain',
                'company_size'       => '51-200 Karyawan',
                'business_fields'    => ['Arsitektur Residensial', 'Hospitality', 'Komersial', 'Interior Design'],
                'founded_year'       => 2010,
                'company_website'    => 'https://www.arkindo.studio',
                'verification_status'=> 'verified',
                'verified_at'        => now(),
            ],
            'PT Graha Design' => [
                'company_name'       => 'PT Graha Design',
                'company_desc'       => 'PT Graha Design adalah firma arsitektur dan engineering terintegrasi dengan pengalaman lebih dari 20 tahun di proyek infrastruktur, gedung pemerintahan, dan kawasan industri skala nasional.',
                'industry'           => 'Konstruksi & Rekayasa',
                'company_size'       => '201-500 Karyawan',
                'business_fields'    => ['Engineering', 'Infrastruktur', 'BIM Management', 'Konstruksi'],
                'founded_year'       => 2004,
                'company_website'    => 'https://www.grahadesign.id',
                'verification_status'=> 'verified',
                'verified_at'        => now(),
            ],
            'Kana Interiors' => [
                'company_name'       => 'Kana Interiors',
                'company_desc'       => 'Kana Interiors adalah studio desain interior boutique yang mengkhususkan diri pada proyek residensial premium dan komersial. Pendekatan kami selalu berakar pada cerita klien, estetika lokal, dan detail material terbaik.',
                'industry'           => 'Desain Interior',
                'company_size'       => '11-50 Karyawan',
                'business_fields'    => ['Interior Residensial', 'Interior Komersial', 'FF&E'],
                'founded_year'       => 2015,
                'company_website'    => 'https://www.kanainteriors.co',
                'verification_status'=> 'verified',
                'verified_at'        => now(),
            ],
            'PT Rekayasa Kota Nusantara' => [
                'company_name'       => 'PT Rekayasa Kota Nusantara',
                'company_desc'       => 'PT Rekayasa Kota Nusantara adalah konsultan perencanaan kota dengan rekam jejak panjang dalam penyusunan RTRW, RDTR, masterplan kawasan strategis nasional termasuk kawasan IKN, dan proyek Smart City.',
                'industry'           => 'Perencanaan Wilayah & Kota',
                'company_size'       => '51-200 Karyawan',
                'business_fields'    => ['Perencanaan Kota', 'Tata Ruang', 'Smart City', 'GIS & Analisis Spasial'],
                'founded_year'       => 2001,
                'company_website'    => 'https://www.rekayasanusantara.co.id',
                'verification_status'=> 'verified',
                'verified_at'        => now(),
            ],
            'Taman Hijau Indonesia' => [
                'company_name'       => 'Taman Hijau Indonesia',
                'company_desc'       => 'Taman Hijau Indonesia adalah firma landscape architecture terkemuka yang berfokus pada perancangan taman resort mewah, ruang publik, dan lanskap kawasan perumahan premium di seluruh Indonesia.',
                'industry'           => 'Arsitektur Lanskap & Lingkungan',
                'company_size'       => '11-50 Karyawan',
                'business_fields'    => ['Landscape Architecture', 'Taman Resort', 'Ruang Publik', 'Urban Greening'],
                'founded_year'       => 2012,
                'company_website'    => null,
                'verification_status'=> 'verified',
                'verified_at'        => now(),
            ],
            'Studio Empat Sembilan' => [
                'company_name'       => 'Studio Empat Sembilan',
                'company_desc'       => 'Studio Empat Sembilan adalah studio arsitektur independen berbasis di Yogyakarta yang berfokus pada desain residensial kontemporer dengan pendekatan lokalitas dan eksplorasi material nusantara.',
                'industry'           => 'Arsitektur & Desain',
                'company_size'       => '1-10 Karyawan',
                'business_fields'    => ['Arsitektur Residensial', 'Desain Interior', 'Riset Material'],
                'founded_year'       => 2018,
                'company_website'    => 'https://www.studio49.id',
                'verification_status'=> 'verified',
                'verified_at'        => now(),
            ],
            'CV Bangun Persada' => [
                'company_name'       => 'CV Bangun Persada',
                'company_desc'       => 'CV Bangun Persada adalah kontraktor dan konsultan arsitektur yang melayani proyek residensial dan komersial di wilayah Malang Raya. Kami mengutamakan kualitas gambar kerja dan ketepatan waktu pelaksanaan.',
                'industry'           => 'Konstruksi & Konsultan',
                'company_size'       => '11-50 Karyawan',
                'business_fields'    => ['Konstruksi Residensial', 'Gambar Kerja', 'RAB & Tender'],
                'founded_year'       => 2009,
                'company_website'    => null,
                'verification_status'=> 'verified',
                'verified_at'        => now(),
            ],
            'PT Infrastruktor Muda' => [
                'company_name'       => 'PT Infrastruktor Muda',
                'company_desc'       => 'PT Infrastruktor Muda adalah konsultan MEP (Mekanikal, Elektrikal, Plumbing) yang berpengalaman menangani koordinasi sistem bangunan untuk gedung bertingkat tinggi dan infrastruktur publik di kawasan Jawa Timur.',
                'industry'           => 'Engineering MEP',
                'company_size'       => '51-200 Karyawan',
                'business_fields'    => ['MEP Engineering', 'Koordinasi Sistem Bangunan', 'Infrastruktur'],
                'founded_year'       => 2011,
                'company_website'    => null,
                'verification_status'=> 'verified',
                'verified_at'        => now(),
            ],
            'Aedifica Nusantara' => [
                'company_name'       => 'Aedifica Nusantara',
                'company_desc'       => 'Aedifica Nusantara adalah perusahaan manajemen konstruksi dengan track record lebih dari 100 proyek besar di Indonesia. Kami mengelola proyek dari tahap perencanaan hingga commissioning dengan standar internasional.',
                'industry'           => 'Manajemen Proyek & Konstruksi',
                'company_size'       => '501-1000 Karyawan',
                'business_fields'    => ['Project Management', 'Construction Management', 'Quantity Surveying'],
                'founded_year'       => 2005,
                'company_website'    => 'https://www.aedifica.co.id',
                'verification_status'=> 'verified',
                'verified_at'        => now(),
            ],
            'Render Studio ID' => [
                'company_name'       => 'Render Studio ID',
                'company_desc'       => 'Render Studio ID adalah studio visualisasi arsitektur profesional yang menghasilkan rendering fotorealistik dan animasi walkthrough berkualitas tinggi. Kami melayani biro arsitektur, developer properti, dan kontraktor di seluruh Indonesia.',
                'industry'           => 'Visualisasi & CGI',
                'company_size'       => '1-10 Karyawan',
                'business_fields'    => ['3D Rendering', 'Animasi Arsitektur', 'CGI & Visualisasi'],
                'founded_year'       => 2020,
                'company_website'    => null,
                'verification_status'=> 'verified',
                'verified_at'        => now(),
            ],
        ];

        $base = [
            'location'           => $location,
            'phone'              => null,
            'company_logo_url'   => null,
            'nib_number'         => null,
        ];

        return array_merge($base, $profiles[$companyName] ?? [
            'company_name'       => $companyName,
            'company_desc'       => "{$companyName} adalah perusahaan yang bergerak di bidang arsitektur dan desain, membuka lowongan melalui platform Architecture JobStreet.",
            'industry'           => 'Architecture & Design',
            'company_size'       => '11-50 Karyawan',
            'business_fields'    => ['Architecture', 'Design'],
            'founded_year'       => null,
            'company_website'    => null,
            'verification_status'=> 'verified',
            'verified_at'        => now(),
        ]);
    }
}
