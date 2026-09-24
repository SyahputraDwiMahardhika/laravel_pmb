# PMB Online — Pendaftaran Mahasiswa Baru

Aplikasi Laravel untuk Uji Kompetensi (LSP Informatika) — Pendaftaran Mahasiswa Baru dengan 2 role: **Admin** dan **Calon Mahasiswa**.

> **PENTING — BACA DULU:** Paket ini adalah **overlay/lapisan aplikasi PMB**, bukan project Laravel yang sudah lengkap dengan `vendor/`. Ini disengaja: instalasi Laravel murni (`composer create-project`) mengunduh ratusan paket dari Packagist dan menghasilkan folder `vendor/` ~100MB, yang tidak realistis dikirim sebagai teks/ZIP kode. Cara pakainya: buat project Laravel kosong terlebih dahulu, lalu **timpa/salin** isi folder ini ke dalamnya. Semua langkah persis ada di bawah — ikuti urut dari atas ke bawah, jangan lompat.

---

## 1. Fitur

**Admin:**
- Login, Dashboard (statistik jumlah user & pendaftar per status)
- CRUD data user (tambah/edit/hapus, atur role)
- Kelola data pendaftaran: lihat, edit status/prodi/jalur, hapus
- Filter & pencarian pendaftaran

**Calon Mahasiswa:**
- Registrasi & Login
- Dashboard pribadi
- Isi formulir pendaftaran PMB (data pribadi, alamat dengan dropdown provinsi→kabupaten/kota, pendidikan, pilihan prodi)
- Upload & preview foto sebelum submit
- Lihat detail & status pendaftaran sendiri
- Cetak bukti pendaftaran ke PDF

**Umum:**
- Halaman publik `/informasi-pmb` berisi banner gambar & video (requirement multimedia)
- Validasi server-side (Laravel Validator, pesan Bahasa Indonesia) + client-side (JavaScript: nomor HP hanya angka, konfirmasi hapus, preview foto, dependent dropdown)
- Alert menggunakan SweetAlert2
- Middleware role (`admin`, `mahasiswa`) mencegah akses silang antar-role

## 2. Teknologi

| Komponen        | Pilihan                                   |
|------------------|--------------------------------------------|
| Framework        | Laravel 11 (PHP 8.2+)                      |
| Database         | MySQL / MariaDB                            |
| Tampilan         | Blade + Bootstrap 5                        |
| JS               | Vanilla JS + SweetAlert2 (CDN)             |
| PDF              | barryvdh/laravel-dompdf                    |
| Import Excel     | maatwebsite/excel (PhpSpreadsheet)         |
| ORM              | Eloquent                                   |

## 3. Requirement

- PHP >= 8.2 dengan ekstensi: `mbstring`, `openssl`, `pdo_mysql`, `fileinfo`, `gd`
- Composer 2.x
- MySQL/MariaDB (bawaan XAMPP sudah cukup)
- Node.js (opsional, hanya jika ingin build asset; project ini memakai CDN Bootstrap/SweetAlert sehingga **Node/npm build TIDAK wajib**)

## 4. Cara Install (Windows + XAMPP)

Buka **XAMPP Control Panel**, jalankan **Apache** dan **MySQL**, lalu buka terminal (CMD/PowerShell):

```bat
cd C:\xampp\htdocs

REM 1. Buat skeleton Laravel baru bernama pmb-online-laravel
composer create-project laravel/laravel pmb-online-laravel "^11.0"

cd pmb-online-laravel

REM 2. Install paket tambahan yang dipakai project ini
composer require barryvdh/laravel-dompdf maatwebsite/excel
```

Sekarang **salin seluruh isi folder overlay ini** (`app/`, `database/`, `resources/`, `routes/web.php`, `public/js`, `public/videos`, `tests/`, `.env.example`, `README.md`) ke dalam folder `C:\xampp\htdocs\pmb-online-laravel`, **timpa file yang sama namanya** (terutama `routes/web.php` dan `database/seeders/DatabaseSeeder.php`).

> Folder `bootstrap/`, `config/`, `public/index.php`, dan file inti Laravel lainnya **jangan diganti** — biarkan hasil bawaan `composer create-project`, karena itu adalah kerangka resmi Laravel yang sudah teruji.

## 5. Daftarkan Middleware Role

Laravel 11 mendaftarkan middleware alias di `bootstrap/app.php`. Buka file tersebut dan tambahkan baris berikut di dalam `->withMiddleware(function (Middleware $middleware) { ... })`:

```php
$middleware->alias([
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'mahasiswa' => \App\Http\Middleware\MahasiswaMiddleware::class,
]);
```

*(Jika Anda memakai Laravel 10 ke bawah, tambahkan alias yang sama pada properti `$middlewareAliases` di `app/Http/Kernel.php`.)*

## 6. Konfigurasi .env

```bat
copy .env.example .env
php artisan key:generate
```

Lalu buka `.env` dan sesuaikan bila perlu (default sudah cocok untuk XAMPP: `DB_USERNAME=root`, `DB_PASSWORD=` kosong).

## 7. Buat Database

Buka phpMyAdmin (`http://localhost/phpmyadmin`) → buat database baru bernama:

```
pmb_online
```

(collation `utf8mb4_unicode_ci`)

## 8. Import Data Kabupaten/Kota dari Excel

File Excel asli Anda, **"daftar-kabupaten-kota-di-indonesia-excel.xlsx"**, **sudah disertakan langsung** di `database/data/daftar-kabupaten-kota-di-indonesia-excel.xlsx` — tidak perlu menaruh file apa pun secara manual.

File ini **bukan berformat tabel kolom** (kode/nama), melainkan daftar teks 1 kolom berbentuk narasi bernomor per provinsi, contoh isinya:

```
1. Daftar Nama Kabupaten dan Kota di Provinsi NAD Aceh :

1. Kabupaten Aceh Barat
2. Kabupaten Aceh Barat Daya
...
19. Kota Banda Aceh

2. Daftar Nama Kabupaten dan Kota di Provinsi Sumatera Utara (SUMUT):
...
```

Importer `app/Imports/ProvinceRegencyImport.php` mem-parsing pola ini apa adanya (34 baris header "Daftar Nama Kabupaten dan Kota di Provinsi ...", lalu baris "**angka**. Kabupaten/Kota **nama**" di bawahnya) — sudah diverifikasi menghasilkan tepat **34 provinsi dan 514 kabupaten/kota** dari file Anda.

**Catatan soal kolom `code`:** file sumber Anda tidak menyertakan kode resmi BPS/Kemendagri, hanya nama. Karena tabel `provinces`/`regencies` butuh kolom unik sebagai kunci, importer membuat kode **internal** otomatis (`PROV-01`, `PROV-01-001`, dst) — ini bukan kode resmi pemerintah, hanya identitas teknis di database. Kalau nanti Anda punya data kode resmi, tinggal sesuaikan `ProvinceRegencyImport.php`.

Jalankan seeder (langkah 9) — akan otomatis membaca file ini dan mengisi tabel `provinces` & `regencies`.

**Jika file Excel tidak ditemukan** (mis. terhapus), seeder otomatis memakai fallback 6 baris data contoh saja — cukup untuk demo, bukan data lengkap.

## 9. Migrate & Seed

```bat
php artisan migrate --seed
```

Perintah ini akan membuat seluruh tabel dan mengisi:
- 2 akun demo (lihat bagian 11)
- Data agama, program studi
- Data provinsi/kabupaten (dari Excel atau fallback)

## 10. Jalankan Server

```bat
php artisan storage:link
php artisan serve
```

Buka browser: `http://127.0.0.1:8000`

## 11. Akun Demo

| Role              | Email               | Password |
|-------------------|----------------------|----------|
| Admin             | admin@pmb.test       | password |
| Calon Mahasiswa   | mahasiswa@pmb.test   | password |

## 12. Struktur Folder (bagian yang ditambahkan project ini)

```
app/
  Http/Controllers/
    AuthController.php
    DashboardController.php
    InformationController.php
    Admin/UserController.php
    Admin/PendaftaranController.php
    Mahasiswa/PendaftaranController.php
  Http/Middleware/
    AdminMiddleware.php
    MahasiswaMiddleware.php
  Models/
    User.php, Pendaftaran.php, Province.php, Regency.php, Religion.php, ProgramStudi.php
  Imports/ProvinceRegencyImport.php
database/
  migrations/  (6 file, urut sesuai relasi FK)
  seeders/     (UserSeeder, ReligionSeeder, ProgramStudiSeeder, ProvinceRegencySeeder)
  data/        (tempat menaruh file Excel & contoh CSV)
resources/views/
  layouts/app.blade.php
  auth/ (login, register)
  admin/ (dashboard, users/*, pendaftaran/*)
  mahasiswa/ (dashboard, pendaftaran/*)
  informasi/index.blade.php
  pdf/bukti.blade.php
public/js/pmb.js
public/videos/ (taruh video Anda di sini)
routes/web.php
tests/Feature/ (AuthTest.php, PendaftaranTest.php)
```

## 13. Cara Export PDF

Buka `mahasiswa/pendaftaran` → tombol **"Cetak Bukti"**, atau langsung ke route `mahasiswa.pendaftaran.cetak`. PDF dirender via `barryvdh/laravel-dompdf` dari view `resources/views/pdf/bukti.blade.php` dan ditampilkan langsung di tab baru (`stream`).

## 14. Multimedia

Halaman `/informasi-pmb` (publik, tanpa login) menampilkan:
- Banner gambar (placeholder via `via.placeholder.com`, ganti dengan gambar asli di `public/images/`)
- Video HTML5 — taruh file `informasi-pmb.mp4` di `public/videos/` (lihat `public/videos/BACA-SAYA.txt`)

## 15. Keputusan Desain (bagian soal yang tidak eksplisit)

- **Tabel `roles`** disederhanakan menjadi kolom `enum('role', ['admin','mahasiswa'])` di tabel `users`, bukan tabel & relasi many-to-many terpisah — karena hanya ada 2 peran tetap.
- **Nomor pendaftaran** dibuat otomatis dengan format `PMB-{tahun}-{urutan 6 digit}` melalui `Pendaftaran::generateNomorPendaftaran()`.
- **Satu user hanya boleh punya satu data pendaftaran** (`hasOne`), sesuai konteks PMB (satu calon mahasiswa = satu formulir per tahun ajaran).
- Field yang tidak dirinci eksplisit pada soal (mis. `kecamatan`, `kelurahan`, `kode_pos`) ditambahkan karena lazim ada pada form alamat pendaftaran resmi.

## 16. Testing

```bat
php artisan test
```

Test yang tersedia (`tests/Feature/`):
1. `AuthTest`: login admin berhasil, login mahasiswa berhasil, user belum login tidak bisa akses dashboard, mahasiswa tidak bisa akses halaman admin, validasi email format salah gagal login.
2. `PendaftaranTest`: pendaftaran tersimpan dengan data valid, pendaftaran gagal jika format email salah.

## 17. Troubleshooting

| Masalah | Solusi |
|---|---|
| `SQLSTATE[HY000] [1049] Unknown database` | Pastikan sudah membuat database `pmb_online` di phpMyAdmin sebelum `migrate`. |
| Foto tidak muncul | Jalankan `php artisan storage:link` agar folder `storage/app/public` ter-symlink ke `public/storage`. |
| Kelas `Barryvdh\DomPDF` / `Maatwebsite\Excel` tidak ditemukan | Pastikan `composer require barryvdh/laravel-dompdf maatwebsite/excel` sudah dijalankan di root project. |
| Middleware `admin`/`mahasiswa` error "Target class does not exist" | Pastikan alias middleware sudah didaftarkan di `bootstrap/app.php` (lihat bagian 5). |
| Dropdown kabupaten/kota kosong | Cek apakah `migrate --seed` sudah berhasil mengisi tabel `provinces`/`regencies`; jika pakai Excel sendiri, cek nama kolom sesuai bagian 8. |
| Video tidak tampil | Pastikan file `public/videos/informasi-pmb.mp4` ada; jika tidak, halaman tetap tampil dengan poster placeholder. |

---
Dibuat sebagai bahan Uji Kompetensi Web Development (LSP Informatika) — silakan sesuaikan gambar/video/logo dengan identitas institusi Anda sebelum presentasi.
