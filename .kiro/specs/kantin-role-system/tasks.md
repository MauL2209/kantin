# Implementation Plan

## Overview

Implementasi fitur kantin-role-system dilakukan secara bertahap dari fondasi database ke atas. Setiap task dapat diverifikasi secara mandiri. Task 1 (Database Foundation) adalah prasyarat untuk semua task lainnya karena kolom `stand_id` dan method `isPenjaga()` dibutuhkan oleh semua komponen.

Urutan implementasi: Database → Middleware/Routing → Auth Redirect → Profil/Password → Navbar → Dashboard Penjaga → Produk Penjaga → Pesanan Penjaga → Laporan Penjaga → Manajemen Pengguna Admin → Dashboard Admin Update.

## Task Dependency Graph

```
Task 1 (Database Foundation)
    ├── Task 2 (Middleware & Routing)
    │       ├── Task 3 (AuthController Redirect)
    │       ├── Task 4 (Profil & Password)
    │       ├── Task 5 (Navbar Update)
    │       ├── Task 6 (Dashboard Penjaga)
    │       │       ├── Task 7 (Penjaga: Produk)
    │       │       ├── Task 8 (Penjaga: Pesanan)
    │       │       └── Task 9 (Penjaga: Laporan)
    │       └── Task 10 (Admin: Manajemen Pengguna)
    │               └── Task 11 (Admin Dashboard Update)
```

```json
{
  "waves": [
    {
      "wave": 1,
      "tasks": ["1"]
    },
    {
      "wave": 2,
      "tasks": ["2", "3"]
    },
    {
      "wave": 3,
      "tasks": ["4", "5", "6"]
    },
    {
      "wave": 4,
      "tasks": ["7", "8", "9", "10"]
    },
    {
      "wave": 5,
      "tasks": ["11"]
    }
  ]
}
```

## Tasks

- [x] 1. Database Foundation
  - Buat migration additive untuk menambah kolom `stand_id` pada tabel `users` sebagai nullable foreign key
  - Update model `User` dan `Stand` dengan atribut dan relasi baru
  - Jalankan migrasi untuk menerapkan perubahan skema
  - **Harus selesai sebelum task lainnya dimulai**
  - _Requirements: 1, 10_

  - [x] 1.1 Buat file migrasi `database/migrations/2026_11_02_000001_add_stand_id_to_users_table.php`
    - Tambah kolom `stand_id` BIGINT UNSIGNED NULL DEFAULT NULL pada tabel `users`
    - Tambah foreign key constraint: `FOREIGN KEY (stand_id) REFERENCES stands(id) ON DELETE SET NULL`
    - Migrasi bersifat additive — tidak menghapus atau memodifikasi kolom yang sudah ada
    - _Requirements: 1.2, 10.5_

  - [x] 1.2 Update `app/Models/User.php`
    - Tambah `'stand_id'` ke array `$fillable`
    - Tambah method `isPenjaga(): bool` yang mengembalikan `$this->role === 'penjaga'`
    - Tambah relasi `stand(): BelongsTo` ke model `Stand`
    - _Requirements: 1.1, 1.2_

  - [x] 1.3 Update `app/Models/Stand.php`
    - Tambah relasi `penjaga(): HasOne` ke model `User` dengan filter `where('role', 'penjaga')`
    - Tambah relasi `penjagaUsers(): HasMany` ke model `User` dengan filter `where('role', 'penjaga')`
    - _Requirements: 1.2_

  - [x] 1.4 Jalankan migrasi dan verifikasi
    - Jalankan `php artisan migrate`
    - Pastikan kolom `stand_id` berhasil ditambahkan pada tabel `users`
    - _Requirements: 1.2, 10.5_

- [x] 2. Middleware & Routing
  - Buat `PenjagaMiddleware` dan daftarkan alias-nya
  - Tambah semua route baru ke `routes/web.php`
  - **Bergantung pada Task 1**
  - _Requirements: 3.3, 9_

  - [x] 2.1 Buat `app/Http/Middleware/PenjagaMiddleware.php`
    - Cek `Auth::check()` → jika tidak login, redirect ke route `login` dengan flash `error`
    - Cek `Auth::user()->isPenjaga()` → jika bukan penjaga, `abort(403, 'Akses ditolak. Anda bukan Penjaga Kantin.')`
    - Mengikuti pola `AdminMiddleware` yang sudah ada
    - _Requirements: 3.3, 9.3_

  - [x] 2.2 Daftarkan alias middleware `penjaga` di `bootstrap/app.php`
    - Tambah `'penjaga' => \App\Http\Middleware\PenjagaMiddleware::class` pada array alias middleware
    - Pertahankan alias `admin` yang sudah ada
    - _Requirements: 3.3, 9.2_

  - [x] 2.3 Tambahkan route profil & ubah password ke `routes/web.php`
    - Buat grup `Route::middleware('auth')` untuk route profil dan password
    - Tambah `GET /profile` → `ProfileController@show` dengan name `profile.show`
    - Tambah `POST /profile` → `ProfileController@update` dengan name `profile.update`
    - Tambah `GET /password/change` → `PasswordController@showChangeForm` dengan name `password.change`
    - Tambah `POST /password/change` → `PasswordController@change` dengan name `password.update`
    - Route `/password/change` dan `/password/update` harus membatasi akses hanya untuk role `siswa` dan `penjaga` — role `admin` tidak dapat mengakses (AC 8.1). Tambahkan pengecekan role di controller atau gunakan middleware tambahan.
    - _Requirements: 7, 8, 8.1_

  - [x] 2.4 Tambahkan route grup `/penjaga/*` ke `routes/web.php`
    - Buat grup dengan middleware `['auth', 'penjaga']`, prefix `penjaga`, name prefix `penjaga.`
    - Tambah redirect `GET /penjaga/` → `penjaga.dashboard`
    - Tambah `GET /penjaga/dashboard` → `Penjaga\DashboardController@index` dengan name `penjaga.dashboard`
    - Tambah `POST /penjaga/products/{product}/stock` → `Penjaga\ProductController@updateStock`
    - Tambah `resource /penjaga/products` → `Penjaga\ProductController`
    - Tambah route pesanan: `GET /penjaga/orders`, `GET /penjaga/orders/{order}`, `POST /penjaga/orders/{order}/status`
    - Tambah route laporan: `GET /penjaga/reports`
    - _Requirements: 3, 4, 5, 6_

  - [x] 2.5 Tambahkan route `/admin/users/*` ke grup admin di `routes/web.php`
    - Tambah di dalam grup `Route::middleware(['auth', 'admin'])->prefix('admin')` yang sudah ada
    - Tambah `GET /admin/users` → `Admin\UserController@index` dengan name `admin.users.index`
    - Tambah `GET /admin/users/create` → `Admin\UserController@create` dengan name `admin.users.create`
    - Tambah `POST /admin/users` → `Admin\UserController@store` dengan name `admin.users.store`
    - Tambah `GET /admin/users/{user}/edit` → `Admin\UserController@edit` dengan name `admin.users.edit`
    - Tambah `PUT /admin/users/{user}` → `Admin\UserController@update` dengan name `admin.users.update`
    - Tambah `POST /admin/users/{user}/reset-password` → `Admin\UserController@resetPassword` dengan name `admin.users.reset-password`
    - _Requirements: 2_

  - [x] 2.6 Verifikasi semua route terdaftar
    - Jalankan `php artisan route:list` dan pastikan semua route baru muncul
    - Pastikan tidak ada route existing yang hilang atau berubah nama
    - _Requirements: 10.3_

- [x] 3. AuthController Login Redirect
  - Update method `login()` di `AuthController` untuk menangani redirect role `penjaga`
  - **Bergantung pada Task 1 (method `isPenjaga()` harus tersedia)**
  - _Requirements: 3.1_

  - [x] 3.1 Update method `login()` di `app/Http/Controllers/AuthController.php`
    - Tambah blok kondisi `isPenjaga()` setelah pengecekan `isAdmin()`
    - Urutan pengecekan: `isAdmin()` → `isPenjaga()` → default (siswa, redirect ke `home`)
    - Pesan welcome untuk penjaga: `'Selamat datang, ' . Auth::user()->name . '!'`
    - Redirect menggunakan `redirect()->intended(route('penjaga.dashboard'))`
    - _Requirements: 3.1_

- [x] 4. Fitur Profil & Ubah Password
  - Buat `ProfileController` dan `PasswordController` beserta view-nya
  - **Bergantung pada Task 2.3 (route sudah terdaftar)**
  - _Requirements: 7, 8_

  - [x] 4.1 Buat `app/Http/Controllers/ProfileController.php`
    - Method `show()`: ambil `Auth::user()->load('stand')`, return `view('profile.show')`
    - Method `update(Request $request)`: validasi kondisional berdasarkan role
      - Semua role: `name` (required), `phone` (nullable, digits, between:10,15)
      - **Hanya Siswa**: `nis` (nullable), `class_room` (nullable) — field NIS dan `class_room` HANYA ditampilkan dan divalidasi untuk role `siswa`. Untuk role `penjaga` dan `admin`, kedua field ini harus disembunyikan/tidak ada di form (biconditional: tampil jika dan hanya jika role = siswa).
      - Avatar: nullable, image, mimes:jpg,jpeg,png,webp, max:2048 kB
    - Jika ada avatar: simpan ke `public/avatars/`, hapus avatar lama
    - **Jangan ambil** field `role`, `id`, `stand_id`, `email`, `password` dari request
    - Jika avatar gagal validasi, tetap simpan perubahan field lain yang valid (nama, phone, NIS, kelas) dan hanya tolak avatar — tidak membatalkan seluruh penyimpanan (AC 7.9). Implementasi: validasi field non-avatar terlebih dahulu dan simpan, lalu proses avatar secara terpisah.
    - Redirect ke `profile.show` dengan flash `success`
    - _Requirements: 7.1, 7.2, 7.3, 7.4, 7.5, 7.6, 7.7, 7.9_

  - [x] 4.2 Buat `app/Http/Controllers/PasswordController.php`
    - Method `showChangeForm()`: return `view('profile.change-password')`
    - Method `change(Request $request)`:
      - Validasi: `current_password` (required), `new_password` (required, min:8, different:current_password), `new_password_confirmation` (same:new_password)
      - Cek `Hash::check($request->current_password, Auth::user()->password)` → jika gagal, `withErrors(['current_password' => 'Password lama tidak sesuai.'])`
      - Simpan `Auth::user()->update(['password' => Hash::make($request->new_password)])`
      - Redirect ke `profile.show` dengan flash `success`
    - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5, 8.6_

  - [x] 4.3 Buat view `resources/views/profile/show.blade.php`
    - Extends `layouts.app`
    - Tampilkan: foto profil (avatar), nama, email, NIS (jika siswa), kelas (jika siswa), nomor telepon, role
    - Form edit dengan field yang diizinkan sesuai role
    - Input file untuk upload avatar (accept jpg,jpeg,png,webp)
    - Link ke halaman ubah password
    - _Requirements: 7.1, 7.2, 7.3, 7.4_

  - [x] 4.4 Buat view `resources/views/profile/change-password.blade.php`
    - Extends `layouts.app`
    - Form tiga field: password lama, password baru, konfirmasi password baru
    - Tampilkan pesan error validasi di bawah field yang relevan
    - _Requirements: 8.1, 8.2_

- [x] 5. Perbaikan Navbar
  - Update dropdown pengguna di layout utama untuk mendukung semua role
  - **Bergantung pada Task 2 (route profil & penjaga sudah terdaftar)**
  - _Requirements: 3.1, 7, 9.4_

  - [x] 5.1 Update `resources/views/layouts/app.blade.php`
    - Tambah kondisi `@if(Auth::user()->isAdmin())` untuk menampilkan link admin existing + link baru ke `/admin/users`
    - Tambah kondisi `@elseif(Auth::user()->isPenjaga())` untuk menampilkan: "Dashboard Penjaga", "Profil Saya", "Ubah Password", "Keluar"
    - Pertahankan kondisi `@else` (siswa) dengan menambahkan: "Profil Saya" dan "Ubah Password" di samping item existing
    - _Requirements: 3.1, 7, 9.4_

- [x] 6. Dashboard Penjaga
  - Buat controller dashboard penjaga, layout sidebar, dan view utama
  - **Bergantung pada Task 1, 2.4**
  - _Requirements: 3.1, 3.2, 3.3_

  - [x] 6.1 Buat `app/Http/Controllers/Penjaga/DashboardController.php`
    - Method `index()`:
      - Ambil `$user = Auth::user()->load('stand')`
      - Guard: jika `$user->stand_id === null`, return view dengan pesan informatif "Stand belum ditugaskan. Hubungi Admin." (bukan abort 403)
      - Hitung `$todayOrders`: jumlah pesanan hari ini yang mengandung produk dari stand milik penjaga
      - Hitung `$todayRevenue`: total pendapatan hari ini dari stand milik penjaga
      - Hitung `$activeProducts`: jumlah produk dengan `status = available` dan `stand_id = $user->stand_id`
      - Return `view('penjaga.dashboard', compact('user', 'todayOrders', 'todayRevenue', 'activeProducts'))`
    - _Requirements: 3.2, 3.3_

  - [x] 6.2 Buat `resources/views/layouts/penjaga.blade.php`
    - Layout dua-kolom: sidebar kiri + area konten utama
    - Warna hijau-putih konsisten dengan desain aplikasi yang sudah ada
    - Sidebar berisi: logo & nama sekolah, nama penjaga + nama stand yang ditugaskan
    - Navigasi sidebar: Dashboard, Produk Saya, Pesanan, Laporan
    - Tombol logout di bagian bawah sidebar
    - _Requirements: 3.2, 10.4_

  - [x] 6.3 Buat `resources/views/penjaga/dashboard.blade.php`
    - Extends `layouts.penjaga`
    - Tampilkan pesan informatif jika `stand_id` null
    - Tampilkan stat cards: jumlah pesanan masuk hari ini, total pendapatan hari ini, jumlah produk aktif
    - _Requirements: 3.2_

- [x] 7. Penjaga — Kelola Produk Stand Sendiri
  - Buat controller produk penjaga dengan otorisasi ketat dan view CRUD
  - **Bergantung pada Task 6 (layout penjaga sudah ada)**
  - _Requirements: 4_

  - [x] 7.1 Buat `app/Http/Controllers/Penjaga/ProductController.php`
    - Method privat `authorizeProduct(Product $product): void`:
      - `abort(403, 'Akses ditolak. Produk ini bukan milik Stand Anda.')` jika `$product->stand_id !== Auth::user()->stand_id`
    - Method `index()`: query produk `where('stand_id', Auth::user()->stand_id)` saja
    - Method `store(Request $request)`:
      - Validasi field produk: name, description, price, stock, image, status, category_id
      - Set `stand_id` otomatis dari `Auth::user()->stand_id` — **jangan ambil dari request**
      - Jika `stock == 0`, otomatis set `status = unavailable`
    - Method `update(Request $request, Product $product)`:
      - Panggil `authorizeProduct($product)` terlebih dahulu
      - Validasi dan simpan perubahan
      - Auto-set `status = unavailable` ketika `stock == 0` berlaku juga pada `update()`, bukan hanya `store()` dan `updateStock()`
      - Namun, jika Penjaga secara eksplisit memilih `status = available` di form, hormati pilihan tersebut untuk save ini
      - Override tidak permanen — pada edit berikutnya, aturan otomatis kembali diterapkan (AC 4.7)
    - Method `destroy(Product $product)`: panggil `authorizeProduct($product)`, lalu hapus
    - Method `updateStock(Request $request, Product $product)`:
      - Panggil `authorizeProduct($product)`
      - Update stok, otomatis `status = unavailable` jika stok menjadi 0
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5, 4.6, 4.7_

  - [x] 7.2 Buat view `resources/views/penjaga/products/index.blade.php`
    - Extends `layouts.penjaga`
    - Tabel daftar produk stand sendiri dengan kolom: nama, kategori, harga, stok, status
    - Tombol aksi: edit, hapus, dan update stok
    - Tombol "Tambah Produk Baru"
    - _Requirements: 4.1, 4.2_

  - [x] 7.3 Buat view `resources/views/penjaga/products/create.blade.php`
    - Extends `layouts.penjaga`
    - Form tambah produk: nama, deskripsi, harga, stok, gambar, status, kategori
    - **Tanpa field `stand_id`** (diisi otomatis di controller)
    - _Requirements: 4.1_

  - [x] 7.4 Buat view `resources/views/penjaga/products/edit.blade.php`
    - Extends `layouts.penjaga`
    - Form edit produk dengan semua field yang diizinkan
    - Tampilkan gambar produk saat ini jika ada
    - Field status pada form edit harus menampilkan `unavailable` sebagai default ketika `stock == 0`, terlepas dari nilai status saat ini di database (AC 4.8) — gunakan `old('status', $product->stock == 0 ? 'unavailable' : $product->status)` untuk menentukan selected option
    - _Requirements: 4.2, 4.8_

- [x] 8. Penjaga — Kelola Pesanan Stand Sendiri
  - Buat controller pesanan penjaga dengan otorisasi dan transisi status yang valid
  - **Bergantung pada Task 6 (layout penjaga sudah ada)**
  - _Requirements: 5_

  - [x] 8.1 Buat `app/Http/Controllers/Penjaga/OrderController.php`
    - Method privat `authorizeOrder(Order $order): void`:
      - Query `$order->orderDetails()->whereHas('product', fn($q) => $q->where('stand_id', Auth::user()->stand_id))->exists()`
      - `abort(403, 'Akses ditolak. Pesanan ini tidak mengandung produk dari Stand Anda.')` jika false
    - Method `index()`:
      - Query pesanan yang `whereHas('orderDetails', fn($q) => $q->whereHas('product', fn($p) => $p->where('stand_id', Auth::user()->stand_id)))`
      - Dukung filter status via query param
    - Method `show(Order $order)`: panggil `authorizeOrder($order)`, return view detail
    - Method `updateStatus(Request $request, Order $order)`:
      - Panggil `authorizeOrder($order)`
      - Jika `$request->status === 'Dibatalkan'`, abaikan request tersebut tanpa menampilkan error eksplisit — redirect back tanpa flash error dan status pesanan tetap tidak berubah. Hanya Admin yang dapat membatalkan pesanan.
      - Definisikan tabel transisi valid: `Pesanan Dibuat` → `Sedang Diproses` → `Siap Diambil` → `Selesai`
      - Jika `$request->status === $order->status` (sama dengan status saat ini), perlakukan sebagai no-op: redirect back tanpa error atau perubahan status (AC 5.5)
      - Jika transisi tidak valid → redirect back dengan flash `error`
    - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5_

  - [x] 8.2 Buat view `resources/views/penjaga/orders/index.blade.php`
    - Extends `layouts.penjaga`
    - Tabel daftar pesanan dengan kolom: nomor pesanan, nama pembeli, jumlah item, total, status, waktu
    - Tab atau dropdown filter berdasarkan status: Pesanan Dibuat, Sedang Diproses, Siap Diambil, Selesai
    - _Requirements: 5.1_

  - [x] 8.3 Buat view `resources/views/penjaga/orders/show.blade.php`
    - Extends `layouts.penjaga`
    - Detail pesanan: info pembeli, daftar produk dari stand sendiri, total, status saat ini
    - Tombol update status dengan pilihan transisi yang valid saja
    - Sembunyikan tombol "Batalkan" (hanya Admin yang bisa membatalkan)
    - Hanya tampilkan item produk dari Stand milik Penjaga dalam detail pesanan — item dari Stand lain tidak ditampilkan (AC 5.7)
    - _Requirements: 5.2, 5.3, 5.7_

- [x] 9. Penjaga — Laporan Penjualan Stand Sendiri
  - Buat controller laporan dan view dengan filter periode dan scope ke stand sendiri
  - **Bergantung pada Task 6 (layout penjaga sudah ada)**
  - _Requirements: 6_

  - [x] 9.1 Buat `app/Http/Controllers/Penjaga/ReportController.php`
    - Method `index(Request $request)`:
      - Ambil `$standId = Auth::user()->stand_id`
      - Jika ada query param `stand_id` yang berbeda dari `$standId` → redirect ke `penjaga.reports.index` (laporan Stand milik Penjaga sendiri) dengan flash `warning` yang menjelaskan akses ditolak, bukan abort(403) (AC 6.3)
      - Filter periode: `today`, `this_week`, `this_month` (default: `today`)
      - Hitung: total pendapatan, jumlah pesanan, dan produk terlaris — semua di-scope ke stand sendiri
      - Return `view('penjaga.reports.index', compact(...))`
    - _Requirements: 6.1, 6.2, 6.3_

  - [x] 9.2 Buat view `resources/views/penjaga/reports/index.blade.php`
    - Extends `layouts.penjaga`
    - Dropdown atau tab filter periode: Hari Ini, Minggu Ini, Bulan Ini
    - Tampilkan: total pendapatan, jumlah pesanan, tabel produk terlaris
    - Data hanya dari stand milik penjaga yang sedang login
    - _Requirements: 6.1, 6.2_

- [x] 10. Admin — Manajemen Pengguna
  - Buat `Admin\UserController` untuk CRUD pengguna dan reset password, beserta view-nya
  - **Bergantung pada Task 2.5 (route admin/users sudah terdaftar)**
  - _Requirements: 2_

  - [x] 10.1 Buat `app/Http/Controllers/Admin/UserController.php`
    - Method privat `guardLastAdmin(User $user, string $action): void`:
      - Jika `$user->role === 'admin'` dan `$action` adalah role change/deactivation/deletion
      - Hitung `User::where('role', 'admin')->count()`
      - Jika `<= 1`, throw Exception "Tidak dapat mengubah role, menonaktifkan, atau menghapus Admin terakhir yang tersisa."
    - Method `index()`: ambil semua pengguna dengan relasi `stand`, tampilkan daftar
    - Method `create()`: return view form buat akun baru dengan daftar stand
    - Method `store(Request $request)`:
      - Validasi: name (required), email (required, email, unique), password (required, min:8), role (required, in:siswa,penjaga,admin), stand_id (required_if:role,penjaga, exists:stands,id)
      - Simpan `Hash::make($request->password)` — tidak pernah plain text
      - Jika role bukan penjaga, set `stand_id = null`
    - Method `edit(User $user)`: return view form edit dengan data user dan daftar stand
    - Method `update(Request $request, User $user)`:
      - Panggil `guardLastAdmin($user, $request->role)`
      - Validasi mirip `store` tetapi password nullable
      - **Invariant `stand_id`**: setiap kali role bukan `penjaga`, `stand_id` wajib `null` — ini berlaku pada **setiap save/update**, bukan hanya saat role berubah. Jika role diubah menjadi `penjaga` → wajib ada `stand_id`. Dengan demikian, role `siswa` dan `admin` selalu memiliki `stand_id = null` secara invariant, terlepas dari apakah role berubah atau tetap.
    - Method `resetPassword(Request $request, User $user)`:
      - Validasi: password (required, min:8, confirmed)
      - Simpan `Hash::make($request->password)`
      - Admin tidak perlu tahu password lama
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 2.7, 1.3, 1.4, 1.6_

  - [x] 10.2 Buat view `resources/views/admin/users/index.blade.php`
    - Extends layout admin yang sudah ada
    - Tabel semua pengguna dengan kolom: nama, email, role (badge berwarna), NIS, stand yang ditugaskan
    - Tombol aksi: edit dan reset password per baris
    - Tombol "Tambah Pengguna Baru"
    - _Requirements: 2.1_

  - [x] 10.3 Buat view `resources/views/admin/users/create.blade.php`
    - Extends layout admin yang sudah ada
    - Form field: nama, email, password, konfirmasi password, role (dropdown), stand (dropdown, muncul hanya jika role = penjaga via JavaScript)
    - _Requirements: 2.2, 2.3_

  - [x] 10.4 Buat view `resources/views/admin/users/edit.blade.php`
    - Extends layout admin yang sudah ada
    - Form edit: nama, email, role, NIS, kelas, telepon, stand (jika penjaga)
    - Seksi terpisah "Reset Password": field password baru dan konfirmasi (form POST terpisah ke route reset-password)
    - _Requirements: 2.4, 2.6_

- [x] 11. Admin Dashboard — Statistik Pengguna Baru
  - Update dashboard admin untuk menampilkan jumlah penjaga & siswa dan link ke manajemen pengguna
  - **Bergantung pada Task 10 (route admin/users sudah terdaftar)**
  - _Requirements: 2.1, 10.3_

  - [x] 11.1 Update `app/Http/Controllers/Admin/AdminDashboardController.php`
    - Tambah kalkulasi `$totalSiswa = User::where('role', 'siswa')->count()`
    - Tambah kalkulasi `$totalPenjaga = User::where('role', 'penjaga')->count()`
    - Sertakan kedua variabel ke dalam data yang dikirim ke view
    - _Requirements: 2.1, 10.3_

  - [x] 11.2 Update `resources/views/admin/dashboard.blade.php`
    - Tambah stat card "Total Siswa" dengan link ke `admin.users.index` (filter role=siswa)
    - Tambah stat card "Total Penjaga" dengan link ke `admin.users.index` (filter role=penjaga)
    - Tambah link atau tombol menuju halaman manajemen pengguna di bagian yang relevan
    - Pertahankan semua stat card dan elemen visual yang sudah ada
    - _Requirements: 2.1, 10.3, 10.4_

## Notes

- Semua migration bersifat additive — tidak ada kolom atau tabel yang dihapus.
- Field `role`, `id`, `stand_id`, `email`, dan `password` tidak boleh diambil dari request body di `ProfileController`.
- Password selalu disimpan sebagai bcrypt hash menggunakan `Hash::make()`, tidak pernah plain text.
- Otorisasi cross-stand dilakukan di controller (bukan middleware) agar pesan error bisa lebih spesifik.
- Penjaga tanpa `stand_id` mendapat pesan informatif, bukan HTTP 403.
- Guard admin terakhir mencegah sistem tidak memiliki admin sama sekali.
- Jalankan `php artisan storage:link` jika belum dilakukan, agar avatar bisa diakses secara publik.
