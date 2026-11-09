# Product Requirements Document (PRD)
# Sistem Informasi Kantin Melati Area - SMKN 20 Jakarta

**Versi:** 1.0.0  
**Tanggal:** November 2026  
**Framework:** Laravel 12  
**Database:** MySQL

---

## Daftar Isi

1. [Project Overview](#1-project-overview)
2. [Target Pengguna](#2-target-pengguna)
3. [User Roles dan Hak Akses](#3-user-roles-dan-hak-akses)
4. [Kebutuhan Fungsional](#4-kebutuhan-fungsional)
5. [Kebutuhan Non-Fungsional](#5-kebutuhan-non-fungsional)
6. [Aturan Bisnis](#6-aturan-bisnis)
7. [Alur Sistem](#7-alur-sistem)
8. [Desain Database](#8-desain-database)
9. [Batasan Sistem](#9-batasan-sistem)
10. [Kriteria Keberhasilan](#10-kriteria-keberhasilan)

---

## 1. Project Overview

### 1.1 Nama Proyek

**Kantin Melati Area** - Sistem Informasi dan Pemesanan Makanan/Minuman Kantin SMKN 20 Jakarta.

### 1.2 Latar Belakang

SMKN 20 Jakarta memiliki 8 stand kantin yang masing-masing dikelola oleh jurusan berbeda. Pemesanan selama ini dilakukan langsung di kantin, menimbulkan antrean panjang pada jam istirahat, potensi kehabisan produk tanpa informasi stok yang jelas, dan sulitnya pencatatan transaksi penjualan per stand.

### 1.3 Permasalahan

- Siswa tidak dapat mengetahui ketersediaan menu sebelum datang ke kantin.
- Tidak ada pencatatan penjualan terpusat per stand.
- Pengelolaan stok dilakukan secara manual.
- Penjaga dari masing-masing jurusan tidak memiliki akses sistem terpisah.
- Admin tidak memiliki visibilitas atas performa seluruh stand secara agregat.

### 1.4 Tujuan Pengembangan

- Menyediakan platform pemesanan makanan/minuman berbasis website.
- Memberikan penjaga akses untuk mengelola produk dan pesanan stand masing-masing.
- Menyediakan laporan penjualan real-time untuk admin dan penjaga.
- Mengurangi antrean dengan sistem pre-order berbasis kelas/ruangan.

### 1.5 Solusi

Aplikasi web Laravel 12 multi-role dengan tiga level akses (siswa, penjaga, admin), keranjang belanja berbasis session, manajemen stok real-time, dan laporan penjualan dengan filter periode dan stand.

---

## 2. Target Pengguna

| Pengguna | Deskripsi |
|---|---|
| **Siswa SMKN 20 Jakarta** | Pengguna utama yang memesan makanan dan minuman dari kantin sekolah. |
| **Penjaga Kantin** | Guru atau siswa yang ditugaskan mengelola satu stand kantin dari jurusan tertentu. |
| **Administrator** | Staf sekolah yang memiliki akses penuh atas seluruh data sistem. |
---

## 3. User Roles dan Hak Akses

Sistem menggunakan kolom `role` pada tabel `users` dengan nilai: `siswa`, `penjaga`, `admin`.

### 3.1 Siswa

| Fitur | Akses |
|---|---|
| Registrasi dan login | Ya |
| Melihat daftar 8 stand (publik) | Ya |
| Melihat menu produk (publik) | Ya |
| Keranjang belanja | Ya |
| Checkout dan pemesanan | Ya |
| Riwayat pesanan | Ya |
| Edit profil dan ubah password | Ya |
| Membatalkan pesanan | Tidak |
| Akses panel admin atau penjaga | Tidak |

### 3.2 Penjaga Kantin

| Fitur | Akses |
|---|---|
| Dashboard penjaga | Ya |
| CRUD produk stand sendiri | Ya |
| Update stok produk | Ya |
| Lihat dan kelola pesanan stand sendiri | Ya |
| Ubah status pesanan (kecuali batal) | Ya |
| Laporan penjualan stand sendiri | Ya |
| Kelola produk stand lain | Tidak |
| Membatalkan pesanan | Tidak |
| Akses panel admin | Tidak |

### 3.3 Admin

| Fitur | Akses |
|---|---|
| Dashboard admin dengan statistik | Ya |
| CRUD semua stand | Ya |
| CRUD semua produk dan kategori | Ya |
| Kelola semua pesanan termasuk pembatalan | Ya |
| Laporan penjualan + export CSV + cetak | Ya |
| Manajemen pengguna (CRUD + reset password) | Ya |

### 3.4 Middleware Autentikasi

- **AdminMiddleware** (`app/Http/Middleware/AdminMiddleware.php`): Verifikasi `Auth::check()` dan `role === admin`. Redirect ke login jika belum autentikasi, `abort(403)` jika bukan admin.
- **PenjagaMiddleware** (`app/Http/Middleware/PenjagaMiddleware.php`): Verifikasi `Auth::check()` dan `isPenjaga()`. Redirect ke login jika belum autentikasi, `abort(403)` jika bukan penjaga.

---

## 4. Kebutuhan Fungsional

### FR-001 -- Registrasi
- Form: nama, email, password min 6 karakter, NIS, kelas, telepon (semua opsional kecuali nama/email/password).
- Role default: `siswa`. Email harus unik.
- Setelah berhasil, langsung login dan diarahkan ke beranda.

### FR-002 -- Login
- Autentikasi berbasis email dan password.
- Admin -> `/admin/dashboard`, Penjaga -> `/penjaga/dashboard`, Siswa -> `/`.
- Tersedia fitur Ingat saya.

### FR-003 -- Logout
- Session di-invalidate dan CSRF token di-regenerate.

### FR-004 -- Manajemen Profil
- Field yang dapat diubah: nama, telepon. Khusus siswa: NIS dan kelas.
- Avatar: JPG/JPEG/PNG/WEBP, maks 2 MB. Jika gagal, field lain tetap tersimpan (partial save).
- Field `role`, `id`, `stand_id`, `email`, `password` tidak dapat diubah via form profil.

### FR-005 -- Ubah Password
- Tersedia untuk siswa dan penjaga (admin dikecualikan dari route ini).
- Memerlukan verifikasi password lama. Password baru minimal 8 karakter.

### FR-006 -- Stand Kantin
- Halaman publik: 8 stand dengan status buka/tutup, foto, lokasi, jurusan.
- Halaman detail: daftar produk tersedia per stand.

### FR-007 -- Menu Produk
- Filter berdasarkan kategori dan stand.
- Detail: nama, deskripsi, harga, stok, status ketersediaan.

### FR-008 -- Keranjang Belanja (Session-based)
- Tambah, ubah jumlah, hapus item, kosongkan keranjang.
- Validasi stok saat penambahan. Jumlah item tampil di navbar.

### FR-009 -- Checkout dan Pemesanan
- Data: nama, NIS (opsional), kelas, ruangan, telepon, metode bayar, catatan (opsional).
- Metode: `cash`, `transfer`, `qris`.
- Stok diverifikasi atomik dalam `DB::beginTransaction()` dengan `lockForUpdate()`.
- Status awal: `Pesanan Dibuat`. Nomor: `ORD-YYYYMMDD-XXXX`.
- Payment cash -> `pending`; transfer/QRIS -> `paid` otomatis.

### FR-010 -- Riwayat Pesanan
- Tersedia untuk pengguna login.
- Pesanan terakhir dapat dilacak tanpa login via session `last_order_id`.

### FR-011 -- Produk oleh Penjaga
- CRUD terbatas pada stand sendiri. Akses stand lain -> `abort(403)`.
- `stand_id` diisi otomatis dari akun penjaga.
- Stok 0 -> status otomatis `unavailable`.

### FR-012 -- Pesanan oleh Penjaga
- Transisi valid: Pesanan Dibuat -> Sedang Diproses -> Siap Diambil -> Selesai.
- Tidak dapat membatalkan pesanan.
- Detail hanya menampilkan item dari stand sendiri.

### FR-013 -- Laporan Penjaga
- Filter: Hari Ini, Minggu Ini, Bulan Ini.
- Metrik: pendapatan, jumlah pesanan, produk terlaris -- di-scope ke stand sendiri.

### FR-014 -- Manajemen Stand oleh Admin
- CRUD stand: nama, jurusan, deskripsi, gambar (URL atau upload file), status, lokasi, kontak.
- Toggle status buka/tutup per stand.

### FR-015 -- Pesanan oleh Admin
- Lihat dan ubah status semua pesanan termasuk pembatalan.
- Cetak detail pesanan.

### FR-016 -- Laporan Admin
- Filter: Hari Ini, Minggu Ini, Bulan Ini, Kustom (rentang tanggal), per stand.
- Metrik: total pendapatan, jumlah pesanan, rata-rata nilai, top 5 produk, performa per stand.
- Export CSV (BOM UTF-8 untuk kompatibilitas Excel), tampilan cetak.

### FR-017 -- Manajemen Pengguna oleh Admin
- CRUD akun: buat, lihat, edit, reset password.
- `stand_id` wajib jika role penjaga; null untuk siswa dan admin.
- Guard admin terakhir: sistem mencegah penghapusan/perubahan role admin terakhir.

### FR-018 -- Dashboard Admin
- Metrik: pendapatan hari ini, pesanan hari ini, total produk, total stand, total siswa, total penjaga, pesanan diproses, siap diambil.
- Grafik penjualan 7 hari (Chart.js) dan top 5 produk terlaris (Chart.js).
- Tabel 10 pesanan terbaru.

### FR-019 -- Dashboard Penjaga
- Metrik: pesanan hari ini, pendapatan hari ini, produk aktif.
- Jika `stand_id` null: pesan informatif (bukan error 403).

---

## 5. Kebutuhan Non-Fungsional

| Kode | Aspek | Implementasi |
|---|---|---|
| NFR-001 | Keamanan | CSRF token di semua form, bcrypt pada password, role-based middleware, field sensitif tidak dapat diubah via profil, transaksi DB atomik. |
| NFR-002 | Responsivitas | Tailwind CSS, desain mobile-first. |
| NFR-003 | Kemudahan penggunaan | Navigasi berbasis role, pesan flash setiap aksi, indikator stok visual. |
| NFR-004 | Performa | Eager loading with(), session-based cart, lockForUpdate() pada verifikasi stok. |
| NFR-005 | Integritas data | Foreign key constraint, ON DELETE CASCADE dan SET NULL, DB::transaction pada checkout. |

---

## 6. Aturan Bisnis

| Kode | Aturan |
|---|---|
| BR-001 | Penjaga hanya mengelola produk dengan stand_id sama dengan akun mereka. |
| BR-002 | stand_id produk diisi otomatis dari akun penjaga, tidak dapat diubah via form. |
| BR-003 | Role siswa dan admin selalu memiliki stand_id = null. |
| BR-004 | Produk dengan stok 0 otomatis berstatus unavailable. |
| BR-005 | Produk unavailable atau stok 0 tidak dapat ditambahkan ke keranjang. |
| BR-006 | Stok berkurang atomik saat checkout berhasil. |
| BR-007 | Stok tidak mencukupi satu item -- seluruh transaksi dibatalkan (rollback). |
| BR-008 | Transisi status pesanan hanya valid berurutan: Dibuat -> Diproses -> Siap -> Selesai. |
| BR-009 | Hanya admin yang dapat mengubah status pesanan ke Dibatalkan. |
| BR-010 | Pembayaran cash -> pending; transfer dan QRIS -> paid otomatis saat checkout. |
| BR-011 | Penjaga tanpa stand_id menerima pesan informatif di dashboard (bukan error HTTP). |
| BR-012 | Admin terakhir tidak dapat dihapus, diubah rolenya, atau dinonaktifkan. |
| BR-013 | Nomor pesanan unik, format: ORD-YYYYMMDD-XXXX. |
| BR-014 | Laporan penjaga hanya mencakup data stand milik penjaga yang sedang login. |

---

## 7. Alur Sistem

### 7.1 Registrasi dan Login

```text
Pengunjung -> Form registrasi -> Validasi -> Akun dibuat (role: siswa) -> Login -> Beranda

Pengguna -> Form login -> Auth::attempt()
  Admin   -> /admin/dashboard
  Penjaga -> /penjaga/dashboard
  Siswa   -> / (beranda)
  Gagal   -> Form login + pesan error
```text

### 7.2 Alur Pembelian

```text
Siswa -> Browsing menu -> Pilih produk -> Tambah ke keranjang
       -> Validasi stok -> Update session -> Pesan konfirmasi
```text

### 7.3 Alur Checkout

```text
Keranjang tidak kosong -> Isi form checkout
  -> DB::beginTransaction()
  -> Verifikasi stok semua item (lockForUpdate)
  -> Buat Order -> Buat OrderDetails -> Kurangi stok -> Buat Payment
  -> DB::commit() -> Kosongkan keranjang -> Halaman sukses
  [Error] -> DB::rollBack() -> Form checkout + pesan error
```text

### 7.4 Pengelolaan Pesanan oleh Penjaga

```text
Penjaga -> Daftar Pesanan -> Pilih pesanan -> Verifikasi kepemilikan stand
  -> Update status: Dibuat -> Diproses -> Siap Diambil -> Selesai
  [Mencoba batalkan] -> Request diabaikan, redirect back tanpa error
```text

### 7.5 Laporan Penjualan

```text
Admin   -> Filter periode + stand -> Metrik agregat -> Tampilkan / Export CSV / Cetak
Penjaga -> Filter periode -> Data di-scope ke stand sendiri -> Tampilkan metrik
```text

---

## 8. Desain Database

### 8.1 Tabel `users`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | Auto-increment |
| name | VARCHAR(255) | Nama lengkap |
| email | VARCHAR(255) UNIQUE | Credential login |
| password | VARCHAR(255) | Bcrypt hash |
| role | VARCHAR(255) | Default: siswa. Nilai: siswa, penjaga, admin |
| nis | VARCHAR(255) NULL | Nomor Induk Siswa |
| class_room | VARCHAR(255) NULL | Kelas (contoh: XII RPL 1) |
| phone | VARCHAR(255) NULL | Nomor telepon |
| avatar | VARCHAR(255) NULL | Nama file di public/avatars/ |
| stand_id | BIGINT UNSIGNED NULL FK | Referensi stands.id, ON DELETE SET NULL |

### 8.2 Tabel `stands`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | Auto-increment |
| name | VARCHAR(255) | Nama stand |
| department | VARCHAR(255) | Jurusan pengelola |
| description | TEXT NULL | Deskripsi stand |
| image | VARCHAR(255) NULL | URL atau path gambar |
| status | VARCHAR(255) | Default: open. Nilai: open, closed |
| contact_person | VARCHAR(255) NULL | Nama dan nomor kontak |
| location | VARCHAR(255) NULL | Lokasi fisik stand |

### 8.3 Tabel `categories`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | Auto-increment |
| name | VARCHAR(255) | Nama kategori |
| slug | VARCHAR(255) | Slug URL |
| icon | VARCHAR(255) NULL | Ikon Font Awesome |
| description | TEXT NULL | Deskripsi kategori |

### 8.4 Tabel `products`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | Auto-increment |
| stand_id | BIGINT UNSIGNED FK | Referensi stands.id, CASCADE |
| category_id | BIGINT UNSIGNED FK | Referensi categories.id, CASCADE |
| name | VARCHAR(255) | Nama produk |
| description | TEXT NULL | Deskripsi |
| price | UNSIGNED INT | Harga dalam Rupiah |
| stock | INT | Stok saat ini, default: 0 |
| image | VARCHAR(255) NULL | URL gambar |
| status | VARCHAR(255) | Default: available. Nilai: available, unavailable |
| is_featured | BOOLEAN | Menu unggulan, default: false |

### 8.5 Tabel `orders`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | Auto-increment |
| user_id | BIGINT UNSIGNED NULL FK | Referensi users.id; nullable untuk guest |
| order_number | VARCHAR(255) UNIQUE | Format: ORD-YYYYMMDD-XXXX |
| customer_name | VARCHAR(255) | Nama pemesan |
| customer_nis | VARCHAR(255) NULL | NIS pemesan |
| customer_class | VARCHAR(255) NULL | Kelas pemesan |
| customer_room | VARCHAR(255) NULL | Lokasi pengambilan |
| customer_phone | VARCHAR(255) NULL | Nomor telepon |
| total_price | UNSIGNED INT | Total harga dalam Rupiah |
| payment_method | VARCHAR(255) | Nilai: cash, transfer, qris |
| status | VARCHAR(255) | Nilai: Pesanan Dibuat, Sedang Diproses, Siap Diambil, Selesai, Dibatalkan |
| customer_note | TEXT NULL | Catatan dari pemesan |

### 8.6 Tabel `order_details`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | Auto-increment |
| order_id | BIGINT UNSIGNED FK | Referensi orders.id, CASCADE |
| product_id | BIGINT UNSIGNED FK | Referensi products.id, CASCADE |
| quantity | INT | Jumlah unit dipesan |
| price | INT | Harga satuan saat pemesanan (snapshot) |
| subtotal | INT | quantity x price |
| note | TEXT NULL | Catatan item (kolom tersedia, belum digunakan di UI) |

### 8.7 Tabel `payments`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT UNSIGNED PK | Auto-increment |
| order_id | BIGINT UNSIGNED FK | Referensi orders.id, CASCADE |
| payment_method | VARCHAR(255) | Nilai: cash, transfer, qris |
| amount | UNSIGNED INT | Jumlah dibayarkan dalam Rupiah |
| status | VARCHAR(255) | Nilai: pending, paid, cancelled |
| paid_at | TIMESTAMP NULL | Waktu konfirmasi pembayaran |
| proof_image | VARCHAR(255) NULL | Bukti bayar (kolom tersedia, belum diimplementasikan di UI) |

### 8.8 Diagram Relasi

```text
users          -> stands         (many-to-one, via stand_id)
stands         -> products       (one-to-many)
categories     -> products       (one-to-many)
users          -> orders         (one-to-many)
orders         -> order_details  (one-to-many)
products       -> order_details  (one-to-many)
orders         -> payments       (one-to-one)
```text

---

## 9. Batasan Sistem

| Fitur | Status | Keterangan |
|---|---|---|
| Upload bukti pembayaran | Belum diimplementasikan | Kolom proof_image ada di tabel payments, form upload di UI belum ada. |
| Verifikasi pembayaran cash | Belum diimplementasikan | Status cash tetap pending; tidak ada mekanisme konfirmasi kasir. |
| Notifikasi real-time | Belum diimplementasikan | Tidak ada WebSocket atau push notification. |
| Reset password via email | Belum diimplementasikan | Tabel password_reset_tokens tersedia, alur lupa password di UI belum ada. |
| Catatan per item pesanan | Belum diimplementasikan | Kolom note di order_details tersedia, input di form belum ada. |
| Rating dan ulasan produk | Belum diimplementasikan | Tidak ada tabel atau UI untuk rating. |
| Integrasi payment gateway | Belum diimplementasikan | QRIS dan transfer hanya pilihan metode, tanpa integrasi gateway eksternal. |

---

## 10. Kriteria Keberhasilan

| Kode | Fitur | Kriteria |
|---|---|---|
| AC-001 | Autentikasi | Pengguna tidak dapat mengakses halaman admin/penjaga tanpa role yang sesuai. Middleware mengembalikan HTTP 403. |
| AC-002 | Pemesanan | Berhasil hanya jika semua stok mencukupi. Stok berkurang tepat. Satu item gagal, seluruh transaksi rollback. |
| AC-003 | Otorisasi Penjaga | Penjaga tidak dapat akses produk/pesanan stand lain. Tidak dapat membatalkan pesanan. |
| AC-004 | Stok | Produk stok 0 tidak dapat masuk keranjang. Otomatis unavailable saat stok 0. |
| AC-005 | Laporan | Admin: filter dan export CSV berfungsi. Penjaga: data hanya dari stand sendiri. |
| AC-006 | Profil | Non-avatar tersimpan meski avatar gagal. Field sensitif tidak dapat diubah via form. |
