# 🌿 FaunaID — Ensiklopedia Digital Fauna Nusantara

> **FaunaID** adalah platform web edukasi fullstack berbasis arsitektur **MVC (Model-View-Controller)** murni dengan **PHP Native**, basis data **MySQL**, dan antarmuka responsif modern bertema *Modern Nature Editorial*.

Website ini dirancang untuk memudahkan pelajar, peneliti, dan masyarakat umum dalam mengenal keanekaragaman satwa Indonesia, garis biogeografi Wallace & Weber, status keterancaman punah menurut IUCN, serta artikel edukasi pelestarian alam.

---

## 📸 Fitur Utama

- 🏠 **Beranda Interaktif**: Hero banner dengan pencarian instan, counter statistik spesies, pengenalan 3 zona wilayah, dan fauna unggulan (*featured*).
- 🔍 **Katalog Fauna & Multi-Filter**: Pencarian *live search autocomplete*, filter berdasarkan kelas taksonomi (Mamalia, Aves, Reptil, dll.), tipe habitat, zona wilayah, dan status konservasi IUCN.
- 📖 **Halaman Detail Spesies**: Informasi ilmiah lengkap (nama latin, taksonomi, morfologi fisik, pola makan, populasi), galeri foto interaktif, dan rekomendasi satwa kerabat.
- 🗺️ **Peta Interaktif 3 Zona**: Visualisasi vektor SVG kepulauan Indonesia dengan penanda **Garis Wallace** dan **Garis Weber**, membagi fauna ke dalam Zona Asiatis, Peralihan (Wallacea), dan Australis.
- 📰 **Artikel & Jurnal Edukasi**: Wacana seputar konservasi, kabar lapangan, dan pelestarian satwa lengkap dengan perhitungan jumlah pembaca (*views counter*).
- 🔐 **Sistem Akun & Autentikasi**: Registrasi dan login aman menggunakan `password_hash (bcrypt)` dan proteksi sesi.
- ❤️ **Koleksi Favorit**: Fitur bookmark satwa interaktif berbasis AJAX bagi pengguna terdaftar.
- ⚙️ **Dashboard Admin (CMS Lengkap)**:
  - CRUD data Fauna + upload file foto ke server atau URL eksternal + pemetaan wilayah (*many-to-many*).
  - CRUD Master Kategori dan Tipe Habitat.
  - CRUD Artikel Edukasi (pengaturan status *Draft* / *Published*).
  - Manajemen Pengguna dan hak akses (*Role-Based Access Control*).

---

## 🛠️ Tech Stack

| Lapisan | Teknologi | Keterangan |
|---|---|---|
| **Backend** | PHP 7.4 - 8.x+ (Native MVC) | Tanpa framework, routing dinamis & modular |
| **Database** | MySQL / MariaDB via PDO | Prepared statements aman dari SQL Injection |
| **Frontend** | HTML5, CSS3, JavaScript (ES6+) | Vanilla JS (Fetch API, DOM manipulation) |
| **Peta** | SVG Interaktif | Vektor kepulauan dengan event hover & click |
| **Keamanan** | CSRF Token, XSS Sanitizer, Password Hashing | Enkripsi `PASSWORD_BCRYPT` |

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

Pilih salah satu cara di bawah ini yang paling sesuai dengan lingkungan lokal Anda:

### Opsi 1 — Menggunakan Laragon (Sangat Disarankan)

1. **Clone / Salin repository** ke folder www Laragon:
   ```bash
   cd C:\laragon\www
   git clone https://github.com/username/FaunaID.git
   ```
2. Buka aplikasi **Laragon**, lalu klik tombol **Start All** (menyalakan Apache & MySQL).
3. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`).
4. Buat database baru bernama: `fauna_id`.
5. Import file skema dan data awal yang ada di:
   ```
   database/schema.sql
   ```
   *(Catatan: Sistem kami juga memiliki fitur auto-setup yang akan membuat database secara otomatis jika MySQL aktif)*.
6. Akses aplikasi melalui peramban:
   ```
   http://localhost/FaunaID
   ```
   atau jika menggunakan *Pretty URL Laragon*: `http://faunaid.test`

---

### Opsi 2 — Menggunakan XAMPP

1. Pindahkan folder `FaunaID` ke dalam:
   ```
   C:\xampp\htdocs\FaunaID
   ```
2. Nyalakan modul **Apache** dan **MySQL** di XAMPP Control Panel.
3. Buka `http://localhost/phpmyadmin`, buat database `fauna_id`, dan import file `database/schema.sql`.
4. Buka peramban di:
   ```
   http://localhost/FaunaID
   ```

---

### Opsi 3 — Menjalankan via Built-in Server PHP CLI

Jika Anda ingin langsung menguji tanpa web server Apache:
1. Pastikan ekstensi `pdo_mysql` atau `pdo_sqlite` aktif di file `php.ini` Anda.
2. Buka terminal di folder root `FaunaID`, lalu jalankan:
   ```bash
   php -S localhost:8000 -t public
   ```
3. Buka peramban di:
   ```
   http://localhost:8000
   ```

---

## 🔑 Akun Uji Coba (Demo Credentials)

Untuk mempermudah pengujian hak akses, database sudah dilengkapi dengan akun bawaan berikut:

| Peran (Role) | Email | Kata Sandi | Keterangan |
|---|---|---|---|
| **Administrator** | `admin@fauna.id` | `admin123` | Memiliki akses penuh ke Dashboard Admin CMS |
| **User Biasa** | `budi@gmail.com` | `user123` | Pengguna biasa (bisa simpan favorit & profil) |
| **User Biasa 2** | `siti@gmail.com` | `user123` | Akun kedua untuk pengujian multi-user |

> 💡 *Tips:* Pada halaman login (`/auth/login`), terdapat kotak akun demo yang bisa Anda klik untuk mengisi email dan password secara otomatis.

---

## 🧪 Skenario Pengujian (QA & Testing Checklist)

Berikut adalah panduan langkah demi langkah untuk menguji seluruh alur fitur aplikasi:

### 1. Pengujian Beranda & Navigasi
- [ ] Buka halaman utama (`/`). Periksa apakah judul, statistik, 3 kartu zona, dan fauna unggulan tampil dengan rapi.
- [ ] Coba ketik nama satwa (misal: "Harimau" atau "Komodo") pada kotak pencarian di navbar. Pastikan muncul rekomendasi *live search autocomplete*.
- [ ] Klik link navigasi (Beranda, Katalog Fauna, Peta Persebaran, Artikel Edukasi) dan pastikan tidak ada halaman yang 404.

### 2. Pengujian Katalog & Filter
- [ ] Buka menu **Katalog Fauna** (`/fauna`).
- [ ] Pilih filter **Zona Biogeografi** (contoh: "Zona Peralihan"). Pastikan hanya fauna endemik Wallacea yang muncul (Komodo, Anoa, Babirusa).
- [ ] Pilih filter **Kategori** (contoh: "Burung (Aves)"). Pastikan daftar menampilkan satwa jenis unggas (Cenderawasih, Jalak Bali).
- [ ] Coba fitur pengurutan (A - Z atau Z - A) dan periksa apakah paginasi berjalan normal.

### 3. Pengujian Halaman Detail Fauna
- [ ] Klik salah satu satwa (misal: "Harimau Sumatera").
- [ ] Periksa apakah foto utama, thumbnail galeri (bisa diklik untuk ganti foto), status IUCN, ringkasan spesifikasi, dan daftar pulau persebaran tampil lengkap.
- [ ] Klik tombol **Salin Tautan** untuk memastikan fitur share clipboard berjalan.

### 4. Pengujian Peta Interaktif
- [ ] Buka menu **Peta Persebaran** (`/map`).
- [ ] Klik tab **Zona Asiatis**, **Zona Peralihan**, atau **Zona Australis**. Perhatikan perubahan sorotan pulau pada peta vektor SVG.
- [ ] Klik langsung salah satu pulau pada peta (misal: Sulawesi atau Papua).
- [ ] Pastikan panel deskripsi zona di bawah peta terupdate dan daftar fauna langsung menampilkan satwa khas pulau tersebut.

### 5. Pengujian Artikel Edukasi
- [ ] Buka menu **Artikel Edukasi** (`/articles`).
- [ ] Klik salah satu artikel untuk membaca (misal: "Garis Wallace dan Weber").
- [ ] Refresh halaman dan periksa apakah jumlah *views* bertambah.

### 6. Pengujian Autentikasi & Akun
- [ ] Buka halaman **Daftar** (`/auth/register`), buat akun baru dengan email dan password Anda sendiri.
- [ ] Setelah berhasil mendaftar, pastikan akun otomatis masuk (*logged in*) dan nama Anda muncul di navbar pojok kanan atas.
- [ ] Buka **Profil Saya** (`/user/profile`), coba ubah nama Anda atau ganti foto avatar profil.
- [ ] Logout melalui menu dropdown, kemudian coba login kembali menggunakan akun yang baru dibuat.

### 7. Pengujian Fitur Favorit (Bookmark)
- [ ] Pastikan Anda sedang dalam kondisi login.
- [ ] Buka halaman katalog atau halaman detail fauna.
- [ ] Klik tombol ikon hati (**Tambah ke Favorit**).
- [ ] Buka menu **Fauna Favorit Saya** (`/user/favorites`). Pastikan satwa yang Anda tandai muncul di daftar.
- [ ] Klik tombol hapus (ikon tempat sampah) pada kartu favorit untuk membatalkan simpanan.

### 8. Pengujian Dashboard Admin (CMS)
- [ ] Login menggunakan akun admin: `admin@fauna.id` / `admin123`.
- [ ] Buka menu **Dashboard Admin** (`/admin`).
- [ ] **Tambah Fauna Baru**: Buka menu *Data Fauna* > *Tambah Spesies Baru*. Isi form, pilih kategori, pilih habitat, centang wilayah pulau persebaran, unggah foto (atau gunakan URL), lalu simpan. Periksa apakah satwa baru muncul di katalog publik.
- [ ] **Edit Fauna**: Ubah salah satu data fauna yang ada, simpan, dan pastikan data terperbarui.
- [ ] **Kategori & Habitat**: Tambahkan kategori baru di menu *Kategori Fauna* dan pastikan dapat digunakan di form fauna.
- [ ] **Artikel**: Tulis artikel baru melalui menu *Artikel Edukasi* > *Tulis Artikel Baru*. Pastikan artikel dapat diterbitkan ke publik.
- [ ] **Kelola Pengguna**: Buka menu *Pengguna Terdaftar*, periksa fungsi ubah role antara `admin` dan `user`.

---

## 📁 Struktur Direktori

```
FaunaID/
├── app/
│   ├── config/              # Konfigurasi basis data & environment
│   │   ├── Config.php
│   │   └── Database.php
│   ├── core/                # MVC Engine (Router, Controller, Model, Helper)
│   │   ├── App.php
│   │   ├── Controller.php
│   │   ├── Helper.php
│   │   └── Model.php
│   ├── controllers/         # Pengendali logika aplikasi
│   ├── models/              # Pengelola data dan query database
│   └── views/               # Tampilan antarmuka pengguna
│       ├── layouts/         # Komponen global (Navbar, Footer, Admin layout)
│       ├── home/
│       ├── catalog/
│       ├── map/
│       ├── articles/
│       ├── auth/
│       ├── user/
│       └── admin/
├── database/
│   ├── schema.sql           # File skema database & data seeder
│   └── seed.php             # CLI seeder tool
├── docs/
│   └── ANALISIS_DAN_PERANCANGAN.md # Dokumen spesifikasi teknis & ERD
├── public/
│   ├── assets/              # Aset CSS, JavaScript, dan Gambar
│   │   ├── css/
│   │   └── js/
│   ├── uploads/             # Direktori penyimpanan unggahan berkas
│   ├── index.php            # Titik masuk publik aplikasi
│   └── .htaccess            # URL rewrite Apache
├── index.php                # Entry point redirect ke public/
├── .htaccess                # Root rewrite rule
└── README.md
```

---

## ❓ Kendala Umum (Troubleshooting)

1. **Halaman tidak ditemukan (404) saat klik link**:
   - Pastikan modul `mod_rewrite` di Apache sudah aktif. Pada Laragon atau XAMPP, modul ini biasanya sudah aktif secara default.
   - Pastikan file `.htaccess` di root dan di folder `public/` terbawa dan tidak terhapus.
2. **Koneksi Database Gagal**:
   - Periksa konfigurasi di `app/config/Config.php`. Secara default host adalah `127.0.0.1`, port `3306`, user `root`, dan password kosong (`""`).
   - Jika MySQL belum menyala, sistem akan otomatis beralih (*auto-fallback*) ke database SQLite lokal `database/fauna_id.sqlite` agar website tetap bisa dieksplorasi tanpa crash.

---

## 👨‍💻 Kontribusi & Pengembang

Proyek ini dibangun sebagai media pembelajaran dan platform edukasi keanekaragaman hayati Indonesia. Silakan buat *Pull Request* atau laporkan *Issues* untuk pengembangan fitur lebih lanjut!

