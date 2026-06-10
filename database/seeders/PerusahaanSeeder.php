<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use App\Models\Lowongan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PerusahaanSeeder extends Seeder
{
    public function run(): void
    {
        $perusahaan = [
            [
                'user' => [
                    'name'     => 'PT Arkindo Studio',
                    'email'    => 'hr@arkindo.studio',
                    'phone'    => '02112345601',
                    'location' => 'Jakarta Selatan, DKI Jakarta',
                ],
                'profile' => [
                    'company_name'       => 'PT Arkindo Studio',
                    'industry'           => 'Jasa Arsitektur & Desain',
                    'company_size'       => '51-200 Karyawan',
                    'location'           => 'Jl. Kemang Raya No. 21, Jakarta Selatan',
                    'phone'              => '02112345601',
                    'company_website'    => 'https://www.arkindo.studio',
                    'company_desc'       => 'Arkindo Studio adalah biro arsitektur terkemuka di Indonesia yang berdiri sejak 2010. Kami mengerjakan proyek residensial mewah, hotel bintang lima, dan kawasan komersial premium dari Sabang sampai Merauke.',
                    'business_fields'    => ['Arsitektur Residensial', 'Hospitality', 'Komersial', 'Interior Design'],
                    'founded_year'       => 2010,
                    'nib_number'         => '1234567890123456',
                    'verification_status'=> 'verified',
                ],
                'lowongan' => [
                    [
                        'posisi'          => 'Senior Arsitek – Proyek Residensial',
                        'kota'            => 'Jakarta Selatan',
                        'tipe'            => 'Full Time',
                        'gaji'            => 'Rp 15–22 jt',
                        'inisial'         => 'A',
                        'rating'          => 4.8,
                        'deskripsi'       => 'Arkindo Studio membuka posisi Senior Arsitek untuk memimpin desain proyek-proyek residensial premium. Kandidat terbaik akan bekerja langsung bersama principal arsitek dan terlibat dari fase konseptual hingga serah terima.',
                        'syarat'          => [
                            'Minimal 5 tahun pengalaman, diutamakan di firma arsitektur ternama.',
                            'Menguasai Revit, AutoCAD, SketchUp, dan rendering tools.',
                            'Portofolio desain residensial premium yang kuat.',
                            'Kemampuan presentasi dan komunikasi dengan klien.',
                            'Terdaftar di IAI (Ikatan Arsitek Indonesia) lebih diutamakan.',
                        ],
                        'tanggung_jawab'  => [
                            'Memimpin fase desain skematik, pengembangan, dan DED.',
                            'Mengelola klien dan hubungan konsultan.',
                            'Mentoring tim arsitek junior.',
                            'Mereview gambar kerja dan kualitas output.',
                        ],
                        'status'          => 'aktif',
                        'tanggal_mulai'   => now()->subDays(5)->toDateString(),
                        'batas_lamaran'   => now()->addDays(35)->toDateString(),
                        'deadline'        => now()->addDays(35)->toDateString(),
                    ],
                    [
                        'posisi'          => '3D Visualizer – Fotorealistik',
                        'kota'            => 'Jakarta Selatan',
                        'tipe'            => 'Full Time',
                        'gaji'            => 'Rp 7–11 jt',
                        'inisial'         => 'A',
                        'rating'          => 4.6,
                        'deskripsi'       => 'Bergabunglah sebagai 3D Visualizer di Arkindo Studio dan ciptakan render fotorealistik memukau yang memenangkan klien-klien besar kami.',
                        'syarat'          => [
                            'Sangat mahir dalam 3DS Max + V-Ray atau Corona Renderer.',
                            'Portofolio rendering eksterior & interior fotorealistik.',
                            'Pemahaman komposisi, material, dan pencahayaan buatan maupun alami.',
                            'Terbiasa bekerja dengan deadline ketat.',
                        ],
                        'tanggung_jawab'  => [
                            'Membuat visualisasi 3D eksterior dan interior berkualitas tinggi.',
                            'Post-production menggunakan Photoshop dan After Effects.',
                            'Koordinasi dengan tim arsitek untuk akurasi desain.',
                            'Menyiapkan animasi walkthrough bila dibutuhkan.',
                        ],
                        'status'          => 'aktif',
                        'tanggal_mulai'   => now()->subDays(3)->toDateString(),
                        'batas_lamaran'   => now()->addDays(21)->toDateString(),
                        'deadline'        => now()->addDays(21)->toDateString(),
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'PT Graha Design Indonesia',
                    'email'    => 'rekrutmen@grahadesign.id',
                    'phone'    => '03187654321',
                    'location' => 'Surabaya, Jawa Timur',
                ],
                'profile' => [
                    'company_name'       => 'PT Graha Design Indonesia',
                    'industry'           => 'Konstruksi & Rekayasa',
                    'company_size'       => '201-500 Karyawan',
                    'location'           => 'Jl. Raya Darmo No. 88, Surabaya',
                    'phone'              => '03187654321',
                    'company_website'    => 'https://www.grahadesign.id',
                    'company_desc'       => 'PT Graha Design Indonesia adalah firma arsitektur dan engineering terintegrasi dengan pengalaman lebih dari 25 tahun. Kami mengelola proyek infrastruktur skala besar termasuk bandara, terminal, dan gedung pemerintahan.',
                    'business_fields'    => ['Engineering', 'Infrastruktur', 'Pemerintahan', 'BIM Management'],
                    'founded_year'       => 1999,
                    'nib_number'         => '9876543210987654',
                    'verification_status'=> 'verified',
                ],
                'lowongan' => [
                    [
                        'posisi'          => 'BIM Manager',
                        'kota'            => 'Surabaya',
                        'tipe'            => 'Full Time',
                        'gaji'            => 'Rp 18–25 jt',
                        'inisial'         => 'G',
                        'rating'          => 4.4,
                        'deskripsi'       => 'PT Graha Design Indonesia mencari BIM Manager berpengalaman untuk memimpin strategi implementasi BIM di seluruh proyek infrastruktur dan gedung tinggi.',
                        'syarat'          => [
                            'Sertifikasi BIM Professional (Autodesk atau setara).',
                            'Minimal 5 tahun pengalaman BIM, 2 tahun sebagai manajer/lead.',
                            'Menguasai Revit, Navisworks, BIM 360, dan Dynamo.',
                            'Memahami standar ISO 19650 secara mendalam.',
                            'Pengalaman di proyek infrastruktur berskala nasional.',
                        ],
                        'tanggung_jawab'  => [
                            'Menyusun dan mengimplementasikan BIM Execution Plan (BEP).',
                            'Memimpin tim BIM Coordinator di seluruh proyek.',
                            'Melakukan clash detection dan koordinasi antar disiplin.',
                            'Training dan onboarding staf baru dalam penggunaan BIM.',
                        ],
                        'status'          => 'aktif',
                        'tanggal_mulai'   => now()->subDays(10)->toDateString(),
                        'batas_lamaran'   => now()->addDays(25)->toDateString(),
                        'deadline'        => now()->addDays(25)->toDateString(),
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Kana Interiors & Co',
                    'email'    => 'jobs@kanainteriors.co',
                    'phone'    => '02289012345',
                    'location' => 'Bandung, Jawa Barat',
                ],
                'profile' => [
                    'company_name'       => 'Kana Interiors & Co',
                    'industry'           => 'Desain Interior',
                    'company_size'       => '11-50 Karyawan',
                    'location'           => 'Jl. Setiabudi No. 7, Bandung',
                    'phone'              => '02289012345',
                    'company_website'    => 'https://www.kanainteriors.co',
                    'company_desc'       => 'Kana Interiors & Co adalah studio desain interior boutique yang berfokus pada proyek-proyek premium residensial dan komersial di kawasan Jabar dan Jakarta. Pendekatan kami selalu berakar pada cerita klien dan estetika lokal.',
                    'business_fields'    => ['Interior Residensial', 'Interior Komersial', 'FF&E'],
                    'founded_year'       => 2015,
                    'nib_number'         => '1122334455667788',
                    'verification_status'=> 'verified',
                ],
                'lowongan' => [
                    [
                        'posisi'          => 'Interior Designer – Spesialis Residensial',
                        'kota'            => 'Bandung',
                        'tipe'            => 'Full Time',
                        'gaji'            => 'Rp 8–13 jt',
                        'inisial'         => 'K',
                        'rating'          => 4.7,
                        'deskripsi'       => 'Kana Interiors mencari desainer interior penuh kreativitas untuk bergabung dalam tim kami yang dinamis dan mengerjakan proyek-proyek residensial premium di Bandung dan sekitarnya.',
                        'syarat'          => [
                            'S1 Desain Interior atau Arsitektur.',
                            'Pengalaman minimal 3 tahun di interior residensial.',
                            'Mahir 3DS Max/V-Ray atau SketchUp/Lumion.',
                            'Kepekaan estetika yang tinggi dan pemahaman material lokal.',
                            'Memiliki jaringan vendor furnitur dan material.',
                        ],
                        'tanggung_jawab'  => [
                            'Membuat konsep desain, moodboard, dan presentasi klien.',
                            'Menggambar detail interior dan layout furnitur.',
                            'Koordinasi pengadaan FF&E (furnitur, fixture, dan peralatan).',
                            'Supervisi artistik di lokasi proyek.',
                        ],
                        'status'          => 'aktif',
                        'tanggal_mulai'   => now()->subDays(2)->toDateString(),
                        'batas_lamaran'   => now()->addDays(28)->toDateString(),
                        'deadline'        => now()->addDays(28)->toDateString(),
                    ],
                    [
                        'posisi'          => 'Magang Desain Interior (3 Bulan)',
                        'kota'            => 'Bandung',
                        'tipe'            => 'Internship',
                        'gaji'            => 'Rp 1.5–2 jt (uang saku)',
                        'inisial'         => 'K',
                        'rating'          => 4.5,
                        'deskripsi'       => 'Kesempatan magang intensif 3 bulan untuk mahasiswa aktif tingkat akhir atau fresh graduate Desain Interior. Ikuti proyek nyata dari awal hingga akhir dan bangun portofolio profesional Anda.',
                        'syarat'          => [
                            'Mahasiswa tingkat akhir / fresh graduate D3 atau S1 Desain Interior.',
                            'Memiliki kemampuan dasar SketchUp atau 3DS Max.',
                            'Antusias belajar dan berkolaborasi dalam tim.',
                            'Bersedia bekerja on-site di Bandung selama 3 bulan.',
                        ],
                        'tanggung_jawab'  => [
                            'Membantu pembuatan moodboard dan konsep desain.',
                            'Belajar menggambar detail interior dasar.',
                            'Mendampingi senior designer dalam presentasi klien.',
                        ],
                        'status'          => 'aktif',
                        'tanggal_mulai'   => now()->subDay()->toDateString(),
                        'batas_lamaran'   => now()->addDays(14)->toDateString(),
                        'deadline'        => now()->addDays(14)->toDateString(),
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'PT Aedifica Nusantara',
                    'email'    => 'karir@aedifica.co.id',
                    'phone'    => '02167890123',
                    'location' => 'Jakarta Barat, DKI Jakarta',
                ],
                'profile' => [
                    'company_name'       => 'PT Aedifica Nusantara',
                    'industry'           => 'Manajemen Proyek & Konstruksi',
                    'company_size'       => '501-1000 Karyawan',
                    'location'           => 'Jl. Mangga Dua Raya No. 5, Jakarta Barat',
                    'phone'              => '02167890123',
                    'company_website'    => 'https://www.aedifica.co.id',
                    'company_desc'       => 'PT Aedifica Nusantara adalah perusahaan manajemen konstruksi dengan track record lebih dari 100 proyek besar di Indonesia. Kami mengelola proyek dari tahap perencanaan hingga commissioning dengan standar internasional.',
                    'business_fields'    => ['Project Management', 'Construction Management', 'Quantity Surveying', 'Engineering Procurement Construction'],
                    'founded_year'       => 2005,
                    'nib_number'         => '5566778899001122',
                    'verification_status'=> 'verified',
                ],
                'lowongan' => [
                    [
                        'posisi'          => 'Project Manager – Konstruksi Gedung',
                        'kota'            => 'Jakarta Barat',
                        'tipe'            => 'Full Time',
                        'gaji'            => 'Rp 22–35 jt',
                        'inisial'         => 'A',
                        'rating'          => 4.9,
                        'deskripsi'       => 'PT Aedifica Nusantara mencari Project Manager senior untuk memimpin proyek konstruksi gedung bertingkat di Jakarta. Kandidat terpilih akan mengelola tim hingga 50 orang dan bertanggung jawab atas kualitas, waktu, dan anggaran proyek.',
                        'syarat'          => [
                            'S1 Arsitektur atau Teknik Sipil, diutamakan S2.',
                            'Minimal 8 tahun pengalaman, 4 tahun sebagai PM konstruksi.',
                            'Sertifikasi PMP atau AAPM lebih diutamakan.',
                            'Berpengalaman mengelola proyek > Rp 50 miliar.',
                            'Kemampuan negosiasi, kepemimpinan, dan komunikasi tingkat tinggi.',
                        ],
                        'tanggung_jawab'  => [
                            'Mengelola siklus hidup proyek dari desain hingga serah terima.',
                            'Mengontrol anggaran, jadwal, dan kualitas konstruksi.',
                            'Memimpin rapat koordinasi mingguan dengan klien dan subkontraktor.',
                            'Menyusun laporan proyek bulanan untuk direktur.',
                        ],
                        'status'          => 'aktif',
                        'tanggal_mulai'   => now()->subDays(7)->toDateString(),
                        'batas_lamaran'   => now()->addDays(45)->toDateString(),
                        'deadline'        => now()->addDays(45)->toDateString(),
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'CV Studio Empat Sembilan',
                    'email'    => 'open@studio49.id',
                    'phone'    => '027456789012',
                    'location' => 'Yogyakarta, DIY',
                ],
                'profile' => [
                    'company_name'       => 'CV Studio Empat Sembilan',
                    'industry'           => 'Arsitektur & Desain',
                    'company_size'       => '1-10 Karyawan',
                    'location'           => 'Jl. Kaliurang KM 7, Yogyakarta',
                    'phone'              => '027456789012',
                    'company_website'    => 'https://www.studio49.id',
                    'company_desc'       => 'Studio Empat Sembilan adalah studio arsitektur independen berbasis di Yogyakarta yang berfokus pada desain residensial kontemporer dengan pendekatan lokalitas. Kami adalah tim kecil yang kolaboratif dan selalu bereksperimen dengan material nusantara.',
                    'business_fields'    => ['Arsitektur Residensial', 'Desain Interior', 'Riset & Eksperimen'],
                    'founded_year'       => 2018,
                    'nib_number'         => '3344556677889900',
                    'verification_status'=> 'pending',
                ],
                'lowongan' => [
                    [
                        'posisi'          => 'Junior Arsitek',
                        'kota'            => 'Yogyakarta',
                        'tipe'            => 'Full Time',
                        'gaji'            => 'Rp 5–8 jt',
                        'inisial'         => 'S',
                        'rating'          => 4.3,
                        'deskripsi'       => 'Studio Empat Sembilan membuka kesempatan bagi fresh graduate arsitektur yang tertarik eksplorasi desain berbasis material lokal. Bergabunglah dengan tim kecil yang penuh ide segar!',
                        'syarat'          => [
                            'Fresh graduate atau pengalaman ≤ 2 tahun.',
                            'Portofolio desain yang menunjukkan kepekaan estetika.',
                            'Familiar SketchUp, AutoCAD, dan Adobe Creative Suite.',
                            'Menyukai eksplorasi material dan pengerjaan detail.',
                        ],
                        'tanggung_jawab'  => [
                            'Membantu proses desain dari konsep hingga gambar kerja.',
                            'Membuat model 3D dan material study.',
                            'Riset referensi desain dan material lokal.',
                            'Mendampingi senior di site visit.',
                        ],
                        'status'          => 'aktif',
                        'tanggal_mulai'   => now()->subDays(1)->toDateString(),
                        'batas_lamaran'   => now()->addDays(30)->toDateString(),
                        'deadline'        => now()->addDays(30)->toDateString(),
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'PT Rekayasa Kota Nusantara',
                    'email'    => 'sdm@rekayasanusantara.co.id',
                    'phone'    => '02145678901',
                    'location' => 'Jakarta Pusat, DKI Jakarta',
                ],
                'profile' => [
                    'company_name'       => 'PT Rekayasa Kota Nusantara',
                    'industry'           => 'Perencanaan Wilayah & Kota',
                    'company_size'       => '51-200 Karyawan',
                    'location'           => 'Jl. Thamrin No. 15, Jakarta Pusat',
                    'phone'              => '02145678901',
                    'company_website'    => 'https://www.rekayasanusantara.co.id',
                    'company_desc'       => 'PT Rekayasa Kota Nusantara adalah konsultan perencanaan kota terkemuka yang memiliki rekam jejak panjang dalam penyusunan RTRW, RDTR, dan masterplan kawasan strategis nasional termasuk kawasan IKN.',
                    'business_fields'    => ['Perencanaan Kota', 'Tata Ruang', 'Smart City', 'GIS & Analisis Spasial'],
                    'founded_year'       => 2001,
                    'nib_number'         => '7788990011223344',
                    'verification_status'=> 'verified',
                ],
                'lowongan' => [
                    [
                        'posisi'          => 'Urban Planner – Smart City Specialist',
                        'kota'            => 'Jakarta Pusat',
                        'tipe'            => 'Full Time',
                        'gaji'            => 'Rp 16–24 jt',
                        'inisial'         => 'R',
                        'rating'          => 4.6,
                        'deskripsi'       => 'PT Rekayasa Kota Nusantara mencari Urban Planner spesialisasi Smart City untuk berkontribusi dalam proyek-proyek pembangunan kota cerdas pemerintah dan swasta.',
                        'syarat'          => [
                            'S1/S2 Perencanaan Wilayah dan Kota atau Teknik Arsitektur.',
                            'Minimal 4 tahun pengalaman perencanaan kota.',
                            'Keahlian GIS, AutoCAD, dan alat analisis spasial.',
                            'Pemahaman regulasi RTRW dan RDTR.',
                            'Pengalaman proyek Smart City lebih diutamakan.',
                        ],
                        'tanggung_jawab'  => [
                            'Menyusun rencana tata ruang kota/kawasan.',
                            'Analisis spasial dan studi kelayakan pengembangan kawasan.',
                            'Koordinasi dengan pemda dan Bappenas.',
                            'Penyusunan dokumen KLHS (Kajian Lingkungan Hidup Strategis).',
                        ],
                        'status'          => 'aktif',
                        'tanggal_mulai'   => now()->subDays(4)->toDateString(),
                        'batas_lamaran'   => now()->addDays(40)->toDateString(),
                        'deadline'        => now()->addDays(40)->toDateString(),
                    ],
                ],
            ],
        ];

        foreach ($perusahaan as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['user']['email']],
                array_merge($data['user'], [
                    'password'          => Hash::make('password'),
                    'role'              => 'perusahaan',
                    'is_active'         => true,
                    'is_verified'       => true,
                    'email_verified_at' => now(),
                ])
            );

            CompanyProfile::updateOrCreate(
                ['user_id' => $user->id],
                array_merge($data['profile'], ['user_id' => $user->id])
            );

            foreach ($data['lowongan'] as $job) {
                Lowongan::create(array_merge($job, ['user_id' => $user->id]));
            }
        }
    }
}
