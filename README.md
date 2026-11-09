# Kantin Melati Area

> Sistem Informasi dan Pemesanan Makanan/Minuman Kantin SMKN 20 Jakarta

---

## Daftar Isi

1. [Project Overview](#1-project-overview)
2. [Fitur Utama](#2-fitur-utama)
3. [Roles dan Hak Akses](#3-roles-dan-hak-akses)
4. [Teknologi yang Digunakan](#4-teknologi-yang-digunakan)
5. [Persyaratan Sistem](#5-persyaratan-sistem)
6. [Instalasi dan Konfigurasi](#6-instalasi-dan-konfigurasi)
7. [Struktur Folder Proyek](#7-struktur-folder-proyek)
8. [Akun Demo](#8-akun-demo)
9. [Metode Pembayaran](#9-metode-pembayaran)
10. [Informasi Pengembang](#10-informasi-pengembang)

---

## 1. Project Overview

Kantin Melati Area adalah aplikasi web berbasis Laravel 12 untuk sistem informasi dan pemesanan makanan/minuman kantin SMKN 20 Jakarta. Sistem ini melayani 8 stand kantin yang masing-masing dikelola oleh jurusan berbeda.

Aplikasi ini memungkinkan siswa memesan makanan secara daring, penjaga kantin mengelola produk dan pesanan stand mereka, serta admin memantau seluruh operasional kantin dari satu panel terpusat.

---

## 2. Fitur Utama

**Untuk Siswa:**
- Melihat 8 stand kantin beserta status operasional
- Menjelajahi menu produk dengan filter kategori dan stand
- Keranjang belanja berbasis session
- Checkout dengan metode pembayaran: cash, transfer bank, QRIS
- Pelacakan status pesanan
- Manajemen profil dengan upload foto avatar

**Untuk Penjaga Kantin:**
- Dashboard statistik harian stand sendiri
- CRUD produk terbatas pada stand yang ditugaskan
- Manajemen stok dengan pembaruan otomatis status ketersediaan
- Pengelolaan pesanan dengan alur status terstruktur
- Laporan penjualan stand dengan filter periode

**Untuk Admin:**
- Dashboard agregat dengan grafik penjualan (Chart.js)
- Manajemen penuh seluruh stand, produk, dan kategori
- Pengelolaan semua pesanan termasuk pembatalan
- Laporan penjualan dengan filter periode, stand, dan tanggal kustom
- Export laporan ke CSV (kompatibel Microsoft Excel)
- Manajemen akun pengguna (CRUD + reset password)

---

## 3. Roles dan Hak Akses

| Role | Prefix URL | Middleware | Deskripsi |
|---|---|---|---|
| **Siswa** | / | auth | Pembeli; akses fitur belanja dan profil |
| **Penjaga** | /penjaga | auth, penjaga | Pengelola stand; akses produk dan pesanan stand sendiri |
| **Admin** | /admin | auth, admin | Pengelola sistem; akses penuh ke seluruh fitur |

---

## 4. Teknologi yang Digunakan

| Komponen | Teknologi |
|---|---|
| Backend Framework | Laravel 12 (PHP 8.2+) |
| Database | MySQL |
| Frontend CSS | Tailwind CSS (CDN) |
| Ikon | Font Awesome 6.5 |
| Grafik | Chart.js |
| Interaktivitas | Alpine.js 3.x |
| Font | Plus Jakarta Sans (Google Fonts) |
| Package Manager | Composer, NPM |
| Build Tool | Vite |

---

## 5. Persyaratan Sistem

- PHP 8.2 atau lebih baru
- Composer 2.x
- MySQL 5.7 atau lebih baru
- Node.js 18+ dan NPM
- Web server: Apache (Laragon/XAMPP) atau built-in PHP server
- Ekstensi PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

---

## 6. Instalasi dan Konfigurasi

### Langkah 1: Clone atau Ekstrak Proyek

```bash
git clone <url-repository> kantin-melatiarea
cd kantin-melatiarea
```bash

### Langkah 2: Install Dependensi PHP

```bash
composer install
```bash

### Langkah 3: Install Dependensi Node.js

```bash
npm install
```bash

### Langkah 4: Salin File Konfigurasi

```bash
cp .env.example .env
```bash

### Langkah 5: Generate Application Key

```bash
php artisan key:generate
```bash

### Langkah 6: Konfigurasi File `.env`

Buka file `.env` dan sesuaikan konfigurasi berikut:

```env
APP_NAME="Kantin Sekolah MELATIAREA"
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kantin_melatiarea
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
SESSION_LIFETIME=120
```env

### Langkah 7: Buat Database MySQL

Buat database baru di MySQL:

```sql
CREATE DATABASE kantin_melatiarea CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```sql

### Langkah 8: Jalankan Migrasi Database

```bash
php artisan migrate
```bash

### Langkah 9: Jalankan Seeder

Seeder akan membuat 8 stand kantin, 5 kategori produk, 32+ produk, akun admin, akun siswa demo, dan akun penjaga per stand.

```bash
php artisan db:seed
```bash

Atau jalankan keduanya sekaligus:

```bash
php artisan migrate:fresh --seed
```bash

### Langkah 10: Build Asset Frontend

```bash
npm run build
```bash

### Langkah 11: Jalankan Aplikasi

```bash
php artisan serve
```bash

Aplikasi dapat diakses di: `http://localhost:8000`

---

## 7. Struktur Folder Proyek

```text
kantin-melatiarea/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Controller panel admin
│   │   │   ├── Penjaga/        # Controller panel penjaga
│   │   │   ├── AuthController.php
│   │   │   ├── CartController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── HomeController.php
│   │   │   ├── OrderController.php
│   │   │   ├── PasswordController.php
│   │   │   ├── ProductController.php
│   │   │   ├── ProfileController.php
│   │   │   └── StandController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── PenjagaMiddleware.php
│   └── Models/
│       ├── Category.php
│       ├── Order.php
│       ├── OrderDetail.php
│       ├── Payment.php
│       ├── Product.php
│       ├── Stand.php
│       └── User.php
├── database/
│   ├── migrations/             # Skema database
│   └── seeders/
│       └── DatabaseSeeder.php  # Data awal 8 stand + produk + akun demo
├── public/
│   ├── images/                 # Gambar stand kantin
│   └── avatars/                # Foto profil pengguna (dibuat otomatis)
├── resources/
│   └── views/
│       ├── admin/              # View panel admin
│       ├── auth/               # Halaman login dan registrasi
│       ├── cart/               # Halaman keranjang
│       ├── checkout/           # Halaman checkout dan sukses
│       ├── layouts/            # Layout utama (app, admin, penjaga)
│       ├── menu/               # Halaman daftar dan detail produk
│       ├── orders/             # Halaman riwayat pesanan
│       ├── penjaga/            # View panel penjaga
│       ├── profile/            # Halaman profil dan ubah password
│       └── stands/             # Halaman daftar dan detail stand
├── routes/
│   └── web.php                 # Seluruh definisi route aplikasi
├── .env.example                # Template konfigurasi environment
├── composer.json
└── PRD.md                      # Product Requirements Document
```text

---

## 8. Akun Demo

Akun-akun berikut dibuat secara otomatis saat menjalankan `php artisan db:seed`:

### Admin

| Field | Nilai |
|---|---|
| Email | admin@melatiarea.sch.id |
| Password | password123 |
| Role | admin |

### Siswa

| Nama | Email | Password | NIS |
|---|---|---|---|
| Muhammad Rizky Pratama | siswa@melatiarea.sch.id | password123 | 20241001 |
| Siti Nur Aisyah | siti@melatiarea.sch.id | password123 | 20241002 |
| Dimas Anggara | dimas@melatiarea.sch.id | password123 | 20241003 |

### Penjaga Kantin (satu per stand)

| Stand | Email | Password |
|---|---|---|
| Bisnis Digital | penjaga.bisdig@melatiarea.sch.id | password123 |
| Manajemen Logistik | penjaga.logistik@melatiarea.sch.id | password123 |
| Layanan Perbankan Syariah | penjaga.syariah@melatiarea.sch.id | password123 |
| Akuntansi 1 | penjaga.akl1@melatiarea.sch.id | password123 |
| Bisnis Retail | penjaga.retail@melatiarea.sch.id | password123 |
| Manajemen Perkantoran | penjaga.mp@melatiarea.sch.id | password123 |
| Rekayasa Perangkat Lunak | penjaga.rpl@melatiarea.sch.id | password123 |
| Akuntansi 2 | penjaga.akl2@melatiarea.sch.id | password123 |

> **Perhatian:** Ubah seluruh password di atas sebelum menggunakan di lingkungan produksi.

---

## 9. Metode Pembayaran

Aplikasi mendukung tiga metode pembayaran. Semua metode hanya berupa pilihan di form checkout; tidak ada integrasi payment gateway eksternal.

| Metode | Kode | Status Awal | Keterangan |
|---|---|---|---|
| Bayar di Kasir (Tunai) | cash | pending | Pembayaran dilakukan saat pengambilan pesanan |
| Transfer Bank | transfer | paid | Pembayaran dianggap selesai saat checkout |
| QRIS (E-Wallet / Bank) | qris | paid | Pembayaran dianggap selesai saat checkout |

> Upload bukti pembayaran belum diimplementasikan. Kolom `proof_image` tersedia di tabel `payments` untuk pengembangan selanjutnya.

---

## 10. Informasi Pengembang

| Item | Keterangan |
|---|---|
| Instansi | SMKN 20 Jakarta |
| Proyek | Sistem Informasi Kantin Melati Area |
| Framework | Laravel 12 |
| Database | MySQL |
| Bahasa Pemrograman | PHP 8.2+ |

---

*Dokumentasi ini dibuat berdasarkan source code aktual proyek. Untuk informasi teknis lebih lengkap, lihat [PRD.md](PRD.md).*
