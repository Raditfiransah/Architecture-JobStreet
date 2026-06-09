<?php

namespace Database\Seeders;

use App\Models\ClientProfile;
use App\Models\Proyek;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'user' => [
                    'name'     => 'Budi Santoso',
                    'email'    => 'budi.santoso@example.com',
                    'phone'    => '08112345601',
                    'location' => 'Jakarta Selatan, DKI Jakarta',
                ],
                'profile' => [
                    'client_type'       => 'Individu',
                    'address'           => 'Jl. Pondok Indah Blok E3 No. 7, Jakarta Selatan',
                    'project_interests' => ['Residensial', 'Desain Interior', 'Renovasi'],
                    'budget_range'      => 'Rp 500jt – Rp 2M',
                    'verification_status' => 'verified',
                    'verified_at'       => now()->subMonths(4),
                ],
                'proyeks' => [
                    [
                        'title'       => 'Desain Rumah Tinggal Mewah Pondok Indah',
                        'description' => "Dibutuhkan arsitek profesional untuk mendesain rumah tinggal mewah 3 lantai di kawasan Pondok Indah, Jakarta Selatan.\n\nSpesifikasi:\n- Luas Tanah: 600 m²\n- Luas Bangunan: ~450 m²\n- 5 Kamar Tidur Suite\n- Home Theater & Gym\n- Kolam Renang 4x10m\n- Konsep: Modern Minimalis dengan sentuhan Japandi.\n\nOutput yang diharapkan: DED lengkap, render 3D fotorealistik 10 view, animasi walkthrough, dan RAB detail.",
                        'budget'      => 85000000,
                        'category'    => 'Residensial (Rumah, Villa, Apartemen)',
                        'location'    => 'Pondok Indah, Jakarta Selatan',
                        'status'      => 'aktif',
                    ],
                    [
                        'title'       => 'Renovasi Total Interior Apartemen Senopati Suites',
                        'description' => "Renovasi total unit apartemen type 2BR (110 m²) di Senopati Suites lantai 22.\n\nScope pekerjaan:\n- Living room, dining, dan dapur terbuka (open kitchen)\n- 2 kamar tidur dengan custom wardrobe\n- 2 kamar mandi lengkap\n- Ganti seluruh lantai, dinding feature, dan plafon\n- Konsep: Contemporary Luxury dengan palet warna earth tone.\n\nKirimkan proposal desain beserta timeline pengerjaan.",
                        'budget'      => 22000000,
                        'category'    => 'Desain Interior',
                        'location'    => 'Senopati, Jakarta Selatan',
                        'status'      => 'aktif',
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Indira Paramitha',
                    'email'    => 'indira.paramitha@example.com',
                    'phone'    => '08112345602',
                    'location' => 'Surabaya, Jawa Timur',
                ],
                'profile' => [
                    'client_type'       => 'Individu',
                    'address'           => 'Jl. Citraland Regal No. 12, Surabaya Barat',
                    'project_interests' => ['Residensial', 'Lansekap & Taman'],
                    'budget_range'      => 'Rp 200jt – Rp 500jt',
                    'verification_status' => 'verified',
                    'verified_at'       => now()->subMonths(2),
                ],
                'proyeks' => [
                    [
                        'title'       => 'Desain Vila Tropis di Kawasan Trawas Mojokerto',
                        'description' => "Kami mencari arsitek berpengalaman untuk mendesain vila liburan keluarga di kawasan pegunungan Trawas, Mojokerto.\n\nKonsep: Tropical Modern dengan material alam seperti batu andesit, kayu jati, dan ijuk.\n\nProgram ruang:\n- 2 kamar tidur master\n- Living area luar (outdoor living)\n- Dapur semi-terbuka\n- Kolam renang infinity edge\n- Gazebo dan taman\n\nLuas lahan: 1.200 m².",
                        'budget'      => 60000000,
                        'category'    => 'Residensial (Rumah, Villa, Apartemen)',
                        'location'    => 'Trawas, Mojokerto',
                        'status'      => 'aktif',
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Hendra Gunawan',
                    'email'    => 'hendra.gunawan@example.com',
                    'phone'    => '08112345603',
                    'location' => 'Bandung, Jawa Barat',
                ],
                'profile' => [
                    'client_type'       => 'Pengembang',
                    'address'           => 'Jl. Sukajadi No. 45, Bandung',
                    'project_interests' => ['Komersial', 'Residensial', 'Urban Planning'],
                    'budget_range'      => 'Di atas Rp 2M',
                    'verification_status' => 'verified',
                    'verified_at'       => now()->subMonths(7),
                ],
                'proyeks' => [
                    [
                        'title'       => 'Masterplan Perumahan Green Valley Lembang',
                        'description' => "PT Bandung Property Development mencari konsultan arsitektur untuk menyusun masterplan perumahan premium di kawasan Lembang, Bandung.\n\nScope pekerjaan:\n- Masterplan kawasan seluas 10 Hektar\n- 120 unit rumah tipe 60, 90, dan 120\n- Fasilitas: Clubhouse, taman bermain, jogging track, danau buatan\n- Gerbang masuk dan area komersial kecil\n- Konsep: Eco-Green Residential dengan sistem drainase bioretention.\n\nDeadline masterplan: 3 bulan dari kontrak.",
                        'budget'      => 300000000,
                        'category'    => 'Urban Planning & Kawasan',
                        'location'    => 'Lembang, Bandung Barat',
                        'status'      => 'aktif',
                    ],
                    [
                        'title'       => 'Desain Ruko Mixed-Use Cicadas Bandung',
                        'description' => "Dibutuhkan arsitek untuk mendesain kompleks ruko 3 lantai mixed-use (ground floor komersial, lantai atas residensial) di Cicadas, Bandung.\n\nTotal unit: 16 ruko\nLebar muka: 5 m per unit\nFasade: Modern Tropis yang konsisten dan atraktif\n\nOutput: Gambar arsitektur lengkap untuk IMB dan RAB struktur.",
                        'budget'      => 45000000,
                        'category'    => 'Komersial (Ruko, Kantor, Hotel, Kafe)',
                        'location'    => 'Cicadas, Bandung',
                        'status'      => 'ditutup',
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Putri Ayu Rahayu',
                    'email'    => 'putri.ayu@example.com',
                    'phone'    => '08112345604',
                    'location' => 'Yogyakarta, DIY',
                ],
                'profile' => [
                    'client_type'       => 'Individu',
                    'address'           => 'Jl. Palagan Tentara Pelajar No. 30, Sleman',
                    'project_interests' => ['Desain Interior', 'Renovasi'],
                    'budget_range'      => 'Rp 50jt – Rp 200jt',
                    'verification_status' => 'pending',
                    'verified_at'       => null,
                ],
                'proyeks' => [
                    [
                        'title'       => 'Redesain Kafe Buku "Halaman" Yogyakarta',
                        'description' => "Membuka kafe buku di sebuah ruko 2 lantai, Jl. Malioboro area. Mencari desainer interior yang paham konsep third place dan dapat menciptakan atmosfer nyaman untuk membaca dan bekerja.\n\nVisual referensi: Nuansa warm-industrial dengan warna earth tone, rak buku terbuka, dan area rooftop kecil.\n\nLantai 1: ~80m² (area utama, barista, kasir)\nLantai 2: ~60m² (area lesehan, mini gallery)",
                        'budget'      => 18000000,
                        'category'    => 'Desain Interior',
                        'location'    => 'Kraton, Yogyakarta',
                        'status'      => 'aktif',
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Yayasan Mekar Nusantara',
                    'email'    => 'projek@mekarnusantara.org',
                    'phone'    => '02198765432',
                    'location' => 'Jakarta Timur, DKI Jakarta',
                ],
                'profile' => [
                    'client_type'       => 'Instansi',
                    'address'           => 'Jl. Ahmad Yani No. 55, Jakarta Timur',
                    'project_interests' => ['Fasilitas Publik', 'Pendidikan', 'Sosial'],
                    'budget_range'      => 'Rp 200jt – Rp 500jt',
                    'verification_status' => 'verified',
                    'verified_at'       => now()->subMonths(5),
                ],
                'proyeks' => [
                    [
                        'title'       => 'Desain Sekolah Alam Inklusif – Bekasi',
                        'description' => "Yayasan Mekar Nusantara membutuhkan arsitek/konsultan untuk mendesain sekolah alam inklusif di lahan seluas 3 hektar di Bekasi Timur.\n\nKonsep: Sekolah berbasis alam (forest school) yang ramah disabilitas, menggunakan material daur ulang dan sistem off-grid (panel surya, penampungan air hujan).\n\nFasilitas:\n- 8 ruang kelas outdoor/semi-outdoor\n- Perpustakaan terbuka\n- Dapur bersama dan kebun edukasi\n- Toilet inklusif\n\nDana dari CSR perusahaan mitra.",
                        'budget'      => 95000000,
                        'category'    => 'Fasilitas Publik & Sosial',
                        'location'    => 'Bekasi Timur, Jawa Barat',
                        'status'      => 'aktif',
                    ],
                ],
            ],
            [
                'user' => [
                    'name'     => 'Reza Firmanda',
                    'email'    => 'reza.firmanda@example.com',
                    'phone'    => '08112345606',
                    'location' => 'Malang, Jawa Timur',
                ],
                'profile' => [
                    'client_type'       => 'Individu',
                    'address'           => 'Jl. Veteran No. 18, Lowokwaru, Malang',
                    'project_interests' => ['Renovasi', 'Desain Interior'],
                    'budget_range'      => 'Rp 50jt – Rp 200jt',
                    'verification_status' => 'unverified',
                    'verified_at'       => null,
                ],
                'proyeks' => [
                    [
                        'title'       => 'Konversi Rumah Tua Kolonial Menjadi Guesthouse',
                        'description' => "Saya memiliki rumah tua bergaya kolonial Belanda (circa 1940) di Malang yang ingin dikonversi menjadi boutique guesthouse berkapasitas 8 kamar.\n\nTantangan: Mempertahankan karakter kolonial asli (plafon tinggi, jendela jalusi, tegel terazzo) sambil menambahkan fasilitas modern (AC, kamar mandi dalam, WiFi).\n\nDibutuhkan arsitek yang memahami heritage conservation dan desain adaptif.",
                        'budget'      => 75000000,
                        'category'    => 'Renovasi & Sipil',
                        'location'    => 'Klojen, Malang',
                        'status'      => 'aktif',
                    ],
                ],
            ],
        ];

        foreach ($clients as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['user']['email']],
                array_merge($data['user'], [
                    'password'          => Hash::make('password'),
                    'role'              => 'client',
                    'is_active'         => true,
                    'is_verified'       => true,
                    'email_verified_at' => now(),
                ])
            );

            ClientProfile::updateOrCreate(
                ['user_id' => $user->id],
                array_merge($data['profile'], ['user_id' => $user->id])
            );

            foreach ($data['proyeks'] as $proyek) {
                Proyek::updateOrCreate(
                    ['user_id' => $user->id, 'title' => $proyek['title']],
                    array_merge($proyek, ['user_id' => $user->id])
                );
            }
        }
    }
}
