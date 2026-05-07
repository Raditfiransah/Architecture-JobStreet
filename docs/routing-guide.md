# Routing Guide — Web-Architect

> Dokumen ini adalah **sumber kebenaran tunggal** untuk semua konvensi routing di project ini.
> Update dokumen ini setiap kali menambahkan route baru.

---

## 1. Struktur File Route

| File | Isi | Middleware |
|---|---|---|
| `web.php` | Entry point, redirect 301, health check | — |
| `auth.php` | Login, register, OTP, logout | `guest` / `auth` |
| `public.php` | Lowongan, proyek, direktori, info hub | Sebagian `auth` |
| `arsitek.php` | Dashboard arsitek + aksi dari halaman publik | `auth, verified, role:arsitek` |
| `perusahaan.php` | Dashboard perusahaan | `auth, verified, role:perusahaan` |
| `client.php` | Dashboard client | `auth, verified, role:client` |
| `admin.php` | Dashboard admin | `auth, verified, role:admin` |

---

## 2. Konvensi Penamaan

### URL Prefix
```
/dashboard/{role}/{resource}
```

| Role | Prefix |
|---|---|
| arsitek | `/dashboard/arsitek` |
| perusahaan | `/dashboard/perusahaan` |
| client | `/dashboard/client` |
| admin | `/dashboard/admin` |

### Route Name
Format: **`{role}.{resource}.{action}`**

```php
arsitek.dashboard            // root
arsitek.profil.edit          // resource → action
arsitek.portofolio.index     // resource → action
arsitek.lamaran.store        // resource → action (aksi publik, tanpa prefix URL)
```

### HTTP Verb
| Aksi | Verb |
|---|---|
| Tampilkan list | `GET` |
| Tampilkan detail | `GET` |
| Form tambah | `GET` |
| Simpan baru | `POST` |
| Form edit | `GET` |
| Update | `PUT` / `PATCH` |
| Hapus | `DELETE` |
| Aksi (setujui, tolak, tutup, dll) | `POST` |

> **Penting:** Aksi state-changing (`setujui`, `tolak`, `tutup`) **wajib menggunakan POST**, bukan GET.

### Route Model Binding
Gunakan nama model sebagai parameter — **bukan** `{id}` atau singkatan.

```php
// ✅ BENAR
Route::get('/lowongan/{lowongan}', ...);
Route::get('/proyek/{proyek}/proposal/{proposal}', ...);

// ❌ SALAH
Route::get('/lowongan/{id}', ...);
Route::get('/proyek/{id}/proposal/{propId}', ...);
```

---

## 3. Peta Route per Role

### 3.1 Arsitek (`dashboard/arsitek`)

| Method | URL | Name | Keterangan |
|---|---|---|---|
| GET | `/dashboard/arsitek` | `arsitek.dashboard` | Dashboard utama |
| GET | `/dashboard/arsitek/profil` | `arsitek.profil.edit` | Form edit profil |
| PUT | `/dashboard/arsitek/profil` | `arsitek.profil.update` | Simpan perubahan profil |
| GET | `/dashboard/arsitek/profil/preview` | `arsitek.profil.preview` | Preview profil publik |
| POST | `/dashboard/arsitek/avatar` | `arsitek.profil.avatar` | Upload foto profil |
| POST | `/dashboard/arsitek/profil/document` | `arsitek.profil.document` | Upload dokumen |
| GET | `/dashboard/arsitek/portofolio` | `arsitek.portofolio.index` | List portofolio |
| GET | `/dashboard/arsitek/portofolio/tambah` | `arsitek.portofolio.create` | Form tambah portofolio |
| POST | `/dashboard/arsitek/portofolio` | `arsitek.portofolio.store` | Simpan portofolio baru |
| GET | `/dashboard/arsitek/portofolio/{portofolio}/edit` | `arsitek.portofolio.edit` | Form edit portofolio |
| PUT | `/dashboard/arsitek/portofolio/{portofolio}` | `arsitek.portofolio.update` | Update portofolio |
| DELETE | `/dashboard/arsitek/portofolio/{portofolio}` | `arsitek.portofolio.destroy` | Hapus portofolio |
| POST | `/dashboard/arsitek/portofolio/reorder` | `arsitek.portofolio.reorder` | Urutkan ulang |
| GET | `/dashboard/arsitek/lamaran` | `arsitek.lamaran.index` | Daftar lamaran |
| GET | `/dashboard/arsitek/lamaran/{lamaran}` | `arsitek.lamaran.show` | Detail lamaran |
| DELETE | `/dashboard/arsitek/lamaran/{lamaran}` | `arsitek.lamaran.withdraw` | Batalkan lamaran |
| GET | `/dashboard/arsitek/proposal` | `arsitek.proposal.index` | Daftar proposal dikirim |
| GET | `/dashboard/arsitek/proposal/{proposal}` | `arsitek.proposal.show` | Detail proposal |
| PUT | `/dashboard/arsitek/proposal/{proposal}` | `arsitek.proposal.update` | Update proposal |
| GET | `/dashboard/arsitek/notifikasi` | `arsitek.notifikasi.index` | Notifikasi |
| POST | `/dashboard/arsitek/notifikasi/baca-semua` | `arsitek.notifikasi.readAll` | Tandai semua dibaca |
| GET | `/dashboard/arsitek/pengaturan` | `arsitek.pengaturan.index` | Pengaturan akun |
| PUT | `/dashboard/arsitek/pengaturan/password` | `arsitek.pengaturan.password` | Ganti password |
| PUT | `/dashboard/arsitek/pengaturan/notifikasi` | `arsitek.pengaturan.notifikasi` | Preferensi notif |
| DELETE | `/dashboard/arsitek/pengaturan/akun` | `arsitek.pengaturan.delete` | Hapus akun |

**Aksi dari halaman publik** (tanpa prefix `/dashboard/arsitek`):

| Method | URL | Name | Keterangan |
|---|---|---|---|
| POST | `/lowongan/{lowongan}/lamar` | `arsitek.lamaran.store` | Lamar lowongan |
| POST | `/proyek/{proyek}/proposal` | `arsitek.proposal.store` | Kirim proposal proyek |

---

### 3.2 Perusahaan (`dashboard/perusahaan`)

| Method | URL | Name | Keterangan |
|---|---|---|---|
| GET | `/dashboard/perusahaan` | `perusahaan.dashboard` | Dashboard utama |
| GET | `/dashboard/perusahaan/profil` | `perusahaan.profil.edit` | Form edit profil |
| PUT | `/dashboard/perusahaan/profil` | `perusahaan.profil.update` | Simpan perubahan |
| POST | `/dashboard/perusahaan/logo` | `perusahaan.profil.logo` | Upload logo perusahaan* |
| POST | `/dashboard/perusahaan/profil/document` | `perusahaan.profil.document` | Upload dokumen legal |
| GET | `/dashboard/perusahaan/lowongan` | `perusahaan.lowongan.index` | List lowongan |
| GET | `/dashboard/perusahaan/lowongan/buat` | `perusahaan.lowongan.create` | Form buat lowongan |
| POST | `/dashboard/perusahaan/lowongan` | `perusahaan.lowongan.store` | Simpan lowongan |
| GET | `/dashboard/perusahaan/lowongan/{lowongan}/edit` | `perusahaan.lowongan.edit` | Edit lowongan |
| PUT | `/dashboard/perusahaan/lowongan/{lowongan}` | `perusahaan.lowongan.update` | Update lowongan |
| PUT | `/dashboard/perusahaan/lowongan/{lowongan}/tutup` | `perusahaan.lowongan.tutup` | Tutup lowongan |
| PUT | `/dashboard/perusahaan/lowongan/{lowongan}/perpanjang` | `perusahaan.lowongan.perpanjang` | Perpanjang lowongan |
| DELETE | `/dashboard/perusahaan/lowongan/{lowongan}` | `perusahaan.lowongan.destroy` | Hapus lowongan |
| GET | `/dashboard/perusahaan/kandidat` | `perusahaan.pelamar.all` | Semua kandidat |
| GET | `/dashboard/perusahaan/lowongan/{lowongan}/pelamar` | `perusahaan.pelamar.index` | Pelamar per lowongan |
| GET | `/dashboard/perusahaan/lowongan/{lowongan}/pelamar/{lamaran}` | `perusahaan.pelamar.show` | Detail pelamar |
| PUT | `/dashboard/perusahaan/lamaran/{lamaran}/status` | `perusahaan.lamaran.status` | Update status lamaran |
| POST | `/dashboard/perusahaan/lamaran/{lamaran}/shortlist` | `perusahaan.lamaran.shortlist` | Shortlist kandidat |
| GET | `/dashboard/perusahaan/pengaturan` | `perusahaan.pengaturan.index` | Pengaturan |
| PUT | `/dashboard/perusahaan/pengaturan/password` | `perusahaan.pengaturan.password` | Ganti password |

> *`/logo` berbeda dari `/avatar` karena perusahaan mengupload logo badan usaha (bukan foto profil personal). Ini adalah perbedaan semantik yang disengaja.

---

### 3.3 Client (`dashboard/client`)

| Method | URL | Name | Keterangan |
|---|---|---|---|
| GET | `/dashboard/client` | `client.dashboard` | Dashboard utama |
| GET | `/dashboard/client/profil` | `client.profil.edit` | Form edit profil |
| PUT | `/dashboard/client/profil` | `client.profil.update` | Simpan profil |
| POST | `/dashboard/client/avatar` | `client.profil.avatar` | Upload foto profil |
| GET | `/dashboard/client/proyek` | `client.proyek.index` | List proyek |
| GET | `/dashboard/client/proyek/buat` | `client.proyek.create` | Form buat proyek |
| POST | `/dashboard/client/proyek` | `client.proyek.store` | Simpan proyek baru |
| GET | `/dashboard/client/proyek/{proyek}` | `client.proyek.show` | Detail proyek |
| GET | `/dashboard/client/proyek/{proyek}/edit` | `client.proyek.edit` | Edit proyek |
| PUT | `/dashboard/client/proyek/{proyek}` | `client.proyek.update` | Update proyek |
| PUT | `/dashboard/client/proyek/{proyek}/tutup` | `client.proyek.tutup` | Tutup proyek |
| DELETE | `/dashboard/client/proyek/{proyek}` | `client.proyek.destroy` | Hapus proyek |
| GET | `/dashboard/client/proyek/{proyek}/proposal` | `client.proposal-masuk.index` | Proposal masuk ke proyek |
| GET | `/dashboard/client/proyek/{proyek}/proposal/{proposal}` | `client.proposal-masuk.show` | Detail proposal masuk |
| POST | `/dashboard/client/proposal/{proposal}/terima` | `client.proposal-masuk.terima` | Terima proposal |
| POST | `/dashboard/client/proposal/{proposal}/tolak` | `client.proposal-masuk.tolak` | Tolak proposal |
| GET | `/dashboard/client/pengaturan` | `client.pengaturan.index` | Pengaturan |
| PUT | `/dashboard/client/pengaturan/password` | `client.pengaturan.password` | Ganti password |

> **Kenapa `proposal-masuk`?** Untuk membedakan dari `arsitek.proposal` (proposal yang *dikirim* arsitek). Route `client.proposal-masuk.*` adalah proposal yang *diterima* dari arsitek ke proyek client.

---

### 3.4 Admin (`dashboard/admin`)

| Method | URL | Name | Keterangan |
|---|---|---|---|
| GET | `/dashboard/admin` | `admin.dashboard` | Dashboard utama |
| GET | `/dashboard/admin/antrian` | `admin.antrian` | Antrian moderasi |
| GET | `/dashboard/admin/profil` | `admin.profil.edit` | Profil admin |
| PUT | `/dashboard/admin/profil` | `admin.profil.update` | Update profil |
| POST | `/dashboard/admin/avatar` | `admin.profil.avatar` | Upload avatar |
| GET | `/dashboard/admin/lowongan` | `admin.lowongan.index` | List semua lowongan |
| GET | `/dashboard/admin/lowongan/{lowongan}` | `admin.lowongan.show` | Detail lowongan |
| POST | `/dashboard/admin/lowongan/{lowongan}/setujui` | `admin.lowongan.setujui` | Setujui lowongan |
| POST | `/dashboard/admin/lowongan/{lowongan}/tolak` | `admin.lowongan.tolak` | Tolak lowongan |
| POST | `/dashboard/admin/lowongan/{lowongan}/tutup` | `admin.lowongan.tutup` | Tutup paksa lowongan |
| GET | `/dashboard/admin/proyek` | `admin.proyek.index` | List semua proyek |
| GET | `/dashboard/admin/proyek/{proyek}` | `admin.proyek.show` | Detail proyek |
| POST | `/dashboard/admin/proyek/{proyek}/setujui` | `admin.proyek.setujui` | Setujui proyek |
| POST | `/dashboard/admin/proyek/{proyek}/tolak` | `admin.proyek.tolak` | Tolak proyek |
| GET | `/dashboard/admin/users` | `admin.users.index` | List user |
| GET | `/dashboard/admin/users/{user}` | `admin.users.show` | Detail user |
| POST | `/dashboard/admin/users/{user}/suspend` | `admin.users.suspend` | Suspend user |
| POST | `/dashboard/admin/users/{user}/aktifkan` | `admin.users.aktifkan` | Aktifkan user |
| DELETE | `/dashboard/admin/users/{user}` | `admin.users.destroy` | Hapus user |
| GET | `/dashboard/admin/info` | `admin.info.index` | Info Hub — moderasi |
| POST | `/dashboard/admin/info/{infoHub}/setujui` | `admin.info.setujui` | Setujui artikel |
| POST | `/dashboard/admin/info/{infoHub}/tolak` | `admin.info.tolak` | Tolak artikel |
| GET | `/dashboard/admin/laporan` | `admin.laporan.index` | Laporan konten |
| POST | `/dashboard/admin/laporan/{laporan}/tindak` | `admin.laporan.tindak` | Tindak laporan |
| GET | `/dashboard/admin/profiles` | `admin.profiles.index` | Moderasi profil |
| POST | `/dashboard/admin/profiles/{type}/{profile}/verify` | `admin.profiles.verify` | Verifikasi profil |
| POST | `/dashboard/admin/profiles/{type}/{profile}/reject` | `admin.profiles.reject` | Tolak verifikasi |
| GET | `/dashboard/admin/portofolio` | `admin.portofolio.index` | Kelola portofolio |
| GET | `/dashboard/admin/portofolio/user/{user}` | `admin.portofolio.show` | Portofolio per user |
| GET | `/dashboard/admin/portofolio/{portofolio}/edit` | `admin.portofolio.edit` | Edit portofolio |
| PUT | `/dashboard/admin/portofolio/{portofolio}` | `admin.portofolio.update` | Update portofolio |
| DELETE | `/dashboard/admin/portofolio/{portofolio}` | `admin.portofolio.destroy` | Hapus portofolio |
| DELETE | `/dashboard/admin/portofolio/{portofolio}/image` | `admin.portofolio.destroy-image` | Hapus gambar |
| GET | `/dashboard/admin/system` | `admin.system.index` | System monitoring |
| POST | `/dashboard/admin/system/clear-failed` | `admin.system.clear-failed` | Bersihkan failed jobs |
| GET | `/dashboard/admin/security` | `admin.security.index` | Security log |

---

## 4. Route Publik (`public.php`)

| Method | URL | Name | Auth? |
|---|---|---|---|
| GET | `/lowongan` | `lowongan.index` | Tidak |
| GET | `/lowongan/{lowongan}` | `lowongan.show` | Tidak |
| GET | `/proyek` | `proyek.index` | Ya (`auth`) |
| GET | `/proyek/{proyek}` | `proyek.show` | Ya (`auth`) |
| GET | `/arsitek` | `arsitek.direktori` | Ya (`auth`) |
| GET | `/arsitek/{username}` | `arsitek.profil` | Ya (`auth`) |
| GET | `/info` | `info.index` | Ya (`auth`) |
| GET | `/info/{slug}` | `info.show` | Ya (`auth`) |

---

## 4a. Halaman Profil Publik (`web.php`)

> Route ini berada di `web.php` karena dapat diakses oleh siapapun (guest maupun login).
> Parameter `{user}` menggunakan **Route Model Binding** ke model `User`;
> controller memvalidasi role dengan `abort_if()` sehingga tetap mengembalikan 404 jika ID tidak sesuai role.

| Method | URL | Name | Auth? | Keterangan |
|---|---|---|---|---|
| GET | `/hire-arsitek` | `arsitek.index` | Tidak | Direktori arsitek untuk hiring |
| GET | `/arsitek/{user}` | `public.arsitek.show` | Tidak | Profil publik arsitek (by User ID) |
| GET | `/perusahaan/{user}` | `public.perusahaan.show` | Tidak | Profil publik perusahaan (by User ID) |
| GET | `/health` | `health` | Tidak | Health check endpoint |


## 5. Redirect Backward-Compatibility

Simpan redirect di `web.php` sampai setidaknya **Agustus 2026**, kemudian hapus:

```php
Route::redirect('/profile/arsitek',    '/dashboard/arsitek',    301);
Route::redirect('/profile/perusahaan', '/dashboard/perusahaan', 301);
Route::redirect('/profile/client',     '/dashboard/client',     301);
```

---

## 6. Cara Generate Route URL

### Di PHP (Controller / Blade)
```php
route('arsitek.dashboard')                        // tanpa parameter
route('arsitek.portofolio.edit', $portofolio)     // dengan model
route('arsitek.lamaran.store')                     // aksi
```

### Di Vue/Inertia (Ziggy)
```js
route('arsitek.dashboard')
route('client.proyek.show', { proyek: proyek.id })
route('perusahaan.pelamar.show', { lowongan: lowongan.id, lamaran: lamaran.id })
```

---

## 7. Login Redirect Map

`LoginController::redirectAfterLogin()` dan `User::dashboardRoute()`:

| Role | Route Name | URL |
|---|---|---|
| `arsitek` | `arsitek.dashboard` | `/dashboard/arsitek` |
| `perusahaan` | `perusahaan.dashboard` | `/dashboard/perusahaan` |
| `client` | `client.dashboard` | `/dashboard/client` |
| `admin` | `admin.dashboard` | `/dashboard/admin` |

---

## 8. Checklist Menambah Route Baru

- [ ] Tentukan file yang tepat (role/domain)
- [ ] Gunakan HTTP verb yang sesuai
- [ ] Gunakan Route Model Binding (`{model}`) bukan `{id}`
- [ ] Beri nama dengan format `{role}.{resource}.{action}`
- [ ] Tambahkan ke tabel dokumentasi di file ini
- [ ] Jalankan `php artisan route:list` untuk verifikasi
- [ ] Update referensi di frontend Vue jika perlu

---

## 9. Perintah Verifikasi

```bash
# Lihat semua route
php artisan route:list

# Filter per role
php artisan route:list --path=dashboard/arsitek
php artisan route:list --path=dashboard/perusahaan
php artisan route:list --path=dashboard/client
php artisan route:list --path=dashboard/admin

# Cari route bermasalah (nama lama yang mungkin masih tersisa)
grep -rn "route('arsitek.profile')" resources/
grep -rn "route('client.profile')" resources/
grep -rn "route('perusahaan.profile')" resources/
```
