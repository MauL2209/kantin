# Kantin Sekolah [MELATIAREA] 🍱🥤

Aplikasi Web Sistem Pemesanan Makanan dan Minuman Kantin Sekolah Modern Berbasis Pre-Order Online, dikembangkan menggunakan **Laravel 12**, **MySQL**, dan **Tailwind CSS**.

Sistem ini mewadahi **8 Stand Bisnis/Kewirausahaan** dari 8 jurusan SMK:
1. **Bisnis Digital**
2. **Manajemen Logistik**
3. **Layanan Perbankan Syariah**
4. **Akuntansi 1**
5. **Bisnis Retail**
6. **Manajemen Perkantoran**
7. **Rekayasa Perangkat Lunak**
8. **Akuntansi 2**

---

## 🌟 Fitur Utama

### 🎓 Siswa / Pembeli:
- **Katalog 8 Stand Jurusan:** Menampilkan status operasional (Buka/Tutup), lokasi, pengelola, dan jumlah varian menu.
- **Menu Lengkap & Filter:** Pencarian real-time, filter 8 stand, filter 5 kategori (*Makanan, Minuman, Snack, Mie, Dessert*), sorting harga dan tanggal.
- **Indikator Stok Otomatis:** Label **"Habis"** dan tombol nonaktif otomatis jika stok bernilai 0.
- **Keranjang Belanja:** Kontrol kuantitas porsi (+/-) dengan validasi batas stok, hapus item, dan subtotal dinamis.
- **Checkout Pre-Order:** Formulir data pemesan (Nama, NIS, Kelas, Ruang Pengambilan, No. WhatsApp, Catatan).
- **3 Metode Pembayaran:** *Bayar di Kasir (Tunai)*, *QRIS Melatiarea (E-Wallet)*, dan *Transfer Bank*.
- **Pesanan Saya & Stepper Real-Time:** 4 tahap proses visual:
  `Pesanan Dibuat` ➔ `Sedang Diproses` ➔ `Siap Diambil` ➔ `Selesai`

### 🛡️ Administrator Kantin:
- **Dashboard Statistik Modern:** Ringkasan pendapatan hari ini, jumlah pesanan, total produk, dan total stand.
- **Grafik Interaktif Chart.js:** Penjualan harian 7 hari terakhir dan 5 menu paling laris.
- **CRUD 8 Stand Kantin:** Tambah, edit, hapus, dan tombol switch status buka/tutup cepat.
- **CRUD Produk & Quick Stock:** Tambah menu baru, upload foto, edit harga, dan ubah stok langsung dari tabel.
- **Kelola Kategori Menu:** Manajemen klasifikasi hidangan.
- **Manajemen Pesanan:** Ubah status pesanan real-time, batalkan pesanan dengan auto-restorasi stok, dan cetak struk kasir/dapur (thermal print).
- **Laporan Penjualan & Keuangan:** Filter periode (Hari ini, Minggu ini, Bulan ini, Kustom tanggal, Filter stand), serta fitur **Export CSV/Excel** dan **Cetak PDF Resmi**.

---

## 🔑 Akun Demo Pengujian

| Role | Email | Password | Keterangan |
|---|---|---|---|
| **Admin** | `admin@melatiarea.sch.id` | `password123` | Akses penuh dashboard `/admin` |
| **Siswa 1** | `siswa@melatiarea.sch.id` | `password123` | Muhammad Rizky Pratama (XII RPL 1) |
| **Siswa 2** | `siti@melatiarea.sch.id` | `password123` | Siti Nur Aisyah (XI Bisnis Digital 2) |
| **Siswa 3** | `dimas@melatiarea.sch.id` | `password123` | Dimas Anggara (X Akuntansi 1) |

*(Tersedia tombol 1-klik Quick Fill di halaman login untuk kemudahan login saat presentasi).*

---

## 🛠️ Panduan Instalasi & Menjalankan di Laragon

### Langkah 1: Buka Laragon
1. Buka aplikasi **Laragon** di komputer Anda.
2. Klik tombol **"Start All"** untuk menjalankan server Apache dan MySQL.

### Langkah 2: Buat Database MySQL
1. Klik tombol **"Database"** di Laragon (membuka HeidiSQL atau phpMyAdmin).
2. Buat database baru bernama:
   ```sql
   CREATE DATABASE kantin_melatiarea;
   ```

### Langkah 3: Konfigurasi File `.env`
Pastikan file `.env` memiliki konfigurasi MySQL sebagai berikut:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kantin_melatiarea
DB_USERNAME=root
DB_PASSWORD=
```

### Langkah 4: Jalankan Migrasi & Database Seeder
Buka **Terminal** di Laragon (klik kanan di Laragon ➔ *Laragon* ➔ *Terminal* atau buka PowerShell di folder project):
```bash
# Jalankan migrasi tabel dan seeder data awal
php artisan migrate --seed

# Hubungkan folder upload gambar ke public storage
php artisan storage:link
```

*(Alternatif: Jika ingin langsung tanpa artisan, file dump data SQL siap diimpor sudah tersedia di `database/kantin_melatiarea_data.sql`).*

### Langkah 5: Jalankan Aplikasi
```bash
php artisan serve
```
Buka browser dan akses:
👉 **`http://127.0.0.1:8000`** atau **`http://localhost:8000`**

*(Jika menggunakan virtual host otomatis Laragon, Anda juga bisa mengakses melalui `http://portofolioproject.test`).*

---

## 📁 Struktur Folder & File Utama

```
portofolioproject/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AdminDashboardController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── ReportController.php
│   │   │   │   └── StandController.php
│   │   │   ├── AuthController.php
│   │   │   ├── CartController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── HomeController.php
│   │   │   ├── OrderController.php
│   │   │   ├── ProductController.php
│   │   │   └── StandController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       ├── Category.php
│       ├── Order.php
│       ├── OrderDetail.php
│       ├── Payment.php
│       ├── Product.php
│       ├── Stand.php
│       └── User.php
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2026_11_01_000001_create_stands_table.php
│   │   ├── 2026_11_01_000002_create_categories_table.php
│   │   ├── 2026_11_01_000003_create_products_table.php
│   │   ├── 2026_11_01_000004_create_orders_table.php
│   │   ├── 2026_11_01_000005_create_order_details_table.php
│   │   └── 2026_11_01_000006_create_payments_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── categories/
│       │   ├── orders/
│       │   ├── products/
│       │   ├── reports/
│       │   ├── stands/
│       │   └── dashboard.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── cart/
│       │   └── index.blade.php
│       ├── checkout/
│       │   ├── index.blade.php
│       │   └── success.blade.php
│       ├── layouts/
│       │   ├── admin.blade.php
│       │   └── app.blade.php
│       ├── menu/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       ├── orders/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       ├── partials/
│       │   ├── footer.blade.php
│       │   └── navbar.blade.php
│       └── home.blade.php
└── routes/
    └── web.php
```

---

## 🔒 Hak Akses & Keamanan
1. **Middleware Admin:** Route grup `/admin/*` dilindungi middleware `['auth', 'admin']`. Pengguna non-admin atau siswa yang mencoba mengakses dashboard admin akan otomatis ditolak dengan pesan error `403 Forbidden`.
2. **CSRF Protection:** Seluruh form pemesanan, keranjang, dan modul admin dilindungi token `@csrf`.
3. **Database Transaction:** Proses checkout menggunakan `DB::beginTransaction()` dan `DB::commit()` untuk memastikan konsistensi pesanan, stok produk, dan pembayaran.
4. **Password Hashing:** Menggunakan algoritma `bcrypt` bawaan Laravel.
