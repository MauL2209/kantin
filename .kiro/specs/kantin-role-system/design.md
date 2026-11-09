# Design Document

## Overview

Fitur ini menambahkan role ketiga `penjaga` pada aplikasi Kantin MELATIAREA secara additive — tanpa merusak sistem pemesanan, data, maupun desain visual yang sudah ada. Perubahan mencakup empat area utama:

1. **Penambahan role `penjaga`** — kolom `stand_id` pada tabel `users`, middleware `PenjagaMiddleware`, dan redirect login yang sesuai.
2. **Dashboard & manajemen Penjaga** — controller dan views khusus untuk mengelola produk, pesanan, dan laporan Stand milik sendiri.
3. **Manajemen pengguna oleh Admin** — CRUD pengguna baru (`Admin\UserController`) dengan validasi role dan stand.
4. **Profil & ubah password** — `ProfileController` dan `PasswordController` untuk semua role yang sudah login.

Semua perubahan bersifat additive: migrasi hanya menambah kolom, route admin yang sudah ada tidak disentuh, dan desain visual hijau-putih dipertahankan.

---

## Architecture

### Component 1: Database Migration (Additive)

File baru `2026_11_02_000001_add_stand_id_to_users_table.php` menambah satu kolom nullable pada tabel `users`:

```sql
ALTER TABLE users
  ADD COLUMN stand_id BIGINT UNSIGNED NULL DEFAULT NULL,
  ADD CONSTRAINT fk_users_stand_id
      FOREIGN KEY (stand_id) REFERENCES stands(id) ON DELETE SET NULL;
```

Tidak ada kolom yang dihapus atau dimodifikasi. Semua baris existing mendapat `stand_id = null` secara otomatis.

---

### Component 2: Model Updates

**User** — tambah `stand_id` ke `$fillable`, tambah method helper `isPenjaga()`, dan relasi `stand()`:

```php
// Fillable tambahan
'stand_id'

// Method baru
public function isPenjaga(): bool  { return $this->role === 'penjaga'; }
public function stand(): BelongsTo { return $this->belongsTo(Stand::class); }
```

**Stand** — tambah relasi balik ke User dengan role penjaga:

```php
public function penjaga(): HasOne
{
    return $this->hasOne(User::class)->where('role', 'penjaga');
}

public function penjagaUsers(): HasMany
{
    return $this->hasMany(User::class)->where('role', 'penjaga');
}
```

---

### Component 3: PenjagaMiddleware

File baru `app/Http/Middleware/PenjagaMiddleware.php` mengikuti pola `AdminMiddleware` yang sudah ada:

```php
public function handle(Request $request, Closure $next): Response
{
    if (!Auth::check()) {
        return redirect()->route('login')
            ->with('error', 'Silakan login terlebih dahulu.');
    }
    if (!Auth::user()->isPenjaga()) {
        abort(403, 'Akses ditolak. Anda bukan Penjaga Kantin.');
    }
    return $next($request);
}
```

Didaftarkan di `bootstrap/app.php` dengan alias `penjaga`:

```php
$middleware->alias([
    'admin'   => \App\Http\Middleware\AdminMiddleware::class,
    'penjaga' => \App\Http\Middleware\PenjagaMiddleware::class,
]);
```

---

### Component 4: AuthController Update

Method `login()` diperluas dengan pengecekan role `penjaga` setelah `Auth::attempt()` berhasil:

```php
if (Auth::user()->isAdmin()) {
    return redirect()->intended(route('admin.dashboard'))
        ->with('success', 'Selamat datang kembali, Administrator!');
}

if (Auth::user()->isPenjaga()) {
    return redirect()->intended(route('penjaga.dashboard'))
        ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
}

return redirect()->intended(route('home'))
    ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
```

---

### Diagram Alur Akses

```
Request masuk
    ├─ /admin/*    → middleware: auth → admin    → 403 jika bukan admin
    ├─ /penjaga/*  → middleware: auth → penjaga  → 403 jika bukan penjaga
    ├─ /profile    → middleware: auth            → semua role boleh
    ├─ /password/* → middleware: auth            → semua role boleh
    └─ /* (publik) → tidak butuh auth
```

---

## Data Models

### Tabel `users` (setelah migrasi)

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, existing |
| `name` | VARCHAR(255) | existing |
| `email` | VARCHAR(255) | existing |
| `password` | VARCHAR(255) | existing, bcrypt hash |
| `role` | VARCHAR(50) | existing — nilai valid: `siswa`, `penjaga`, `admin` |
| `nis` | VARCHAR(50) NULL | existing |
| `class_room` | VARCHAR(50) NULL | existing |
| `phone` | VARCHAR(20) NULL | existing |
| `avatar` | VARCHAR(255) NULL | existing |
| **`stand_id`** | BIGINT UNSIGNED NULL | **BARU** — FK → `stands.id` ON DELETE SET NULL |
| `created_at` | TIMESTAMP | existing |
| `updated_at` | TIMESTAMP | existing |

### Model `User`

```php
protected $fillable = [
    'name', 'email', 'password', 'role',
    'nis', 'class_room', 'phone', 'avatar',
    'stand_id', // BARU
];

public function isAdmin(): bool   { return $this->role === 'admin'; }
public function isSiswa(): bool   { return $this->role === 'siswa'; }
public function isPenjaga(): bool { return $this->role === 'penjaga'; } // BARU

public function orders(): HasMany    { return $this->hasMany(Order::class); }
public function stand(): BelongsTo   { return $this->belongsTo(Stand::class); } // BARU
```

### Model `Stand`

```php
// Relasi existing
public function products(): HasMany { ... }

// Relasi BARU
public function penjaga(): HasOne
{
    return $this->hasOne(User::class)->where('role', 'penjaga');
}

public function penjagaUsers(): HasMany
{
    return $this->hasMany(User::class)->where('role', 'penjaga');
}
```

---

## API/Routes Design

### Route Profil (Semua Role Authenticated)

```php
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/password/change', [PasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/password/change', [PasswordController::class, 'change'])->name('password.update');
});
```

**Catatan (AC 8.1):** Route `/password/change` dan `/password/update` HARUS membatasi akses hanya untuk role `siswa` dan `penjaga`. Role `admin` tidak dapat mengakses halaman ubah password ini.

### Route Penjaga (Middleware `auth` + `penjaga`)

```php
Route::middleware(['auth', 'penjaga'])->prefix('penjaga')->name('penjaga.')->group(function () {
    Route::get('/', fn() => redirect()->route('penjaga.dashboard'));

    // Dashboard
    Route::get('/dashboard', [Penjaga\DashboardController::class, 'index'])->name('dashboard');

    // Produk Stand Sendiri
    Route::post('/products/{product}/stock', [Penjaga\ProductController::class, 'updateStock'])
        ->name('products.stock');
    Route::resource('products', Penjaga\ProductController::class);

    // Pesanan Stand Sendiri
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [Penjaga\OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [Penjaga\OrderController::class, 'show'])->name('show');
        Route::post('/{order}/status', [Penjaga\OrderController::class, 'updateStatus'])->name('status');
    });

    // Laporan Stand Sendiri
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [Penjaga\ReportController::class, 'index'])->name('index');
    });
});
```

### Route Admin Users (Dalam Grup Admin yang Sudah Ada)

```php
// Ditambahkan di dalam Route::middleware(['auth', 'admin'])->prefix('admin')...
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [Admin\UserController::class, 'index'])->name('index');
    Route::get('/create', [Admin\UserController::class, 'create'])->name('create');
    Route::post('/', [Admin\UserController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [Admin\UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [Admin\UserController::class, 'update'])->name('update');
    Route::post('/{user}/reset-password', [Admin\UserController::class, 'resetPassword'])
        ->name('reset-password');
});
```

---

## Components and Interfaces

### ProfileController

`app/Http/Controllers/ProfileController.php`

**`show()`**
- Ambil `Auth::user()` dengan relasi `stand` (untuk penjaga).
- Return `view('profile.show', ['user' => $user])`.

**`update(Request $request)`**
- Validasi kondisional:
  - Semua role: `name` (required, string, max:255), `phone` (nullable, digits, between:10,15).
  - Hanya Siswa: `nis` (nullable, string, max:50), `class_room` (nullable, string, max:50).
  - Avatar: `avatar` (nullable, image, mimes:jpg,jpeg,png,webp, max:2048).
- Field `role`, `id`, `stand_id`, `email`, `password` **tidak boleh diambil dari request** — selalu diabaikan.
- Jika ada file avatar: simpan ke `public/avatars/{user_id}_{timestamp}.ext`, hapus avatar lama jika ada.
- Simpan dengan `$user->update($data)` dan redirect ke `profile.show` dengan flash `success`.
- **Biconditional `nis`/`class_room` (AC 7.7):** Field `nis` dan `class_room` HANYA ditampilkan dan dapat diedit untuk role `siswa`. Untuk role `penjaga` dan `admin`, field tersebut tidak ditampilkan di view dan tidak divalidasi.
- **Partial save (AC 7.9):** Jika avatar gagal validasi, field lain yang valid (nama, phone, NIS, kelas) tetap disimpan — hanya avatar yang ditolak. Implementasi: validasi field non-avatar terlebih dahulu, simpan perubahan, lalu proses avatar secara terpisah. Jika avatar gagal, redirect dengan error avatar tetapi field lain sudah tersimpan.

---

### PasswordController

`app/Http/Controllers/PasswordController.php`

**`showChangeForm()`**
- Return `view('profile.change-password')`.

**`change(Request $request)`**
- Validasi:
  - `current_password`: required.
  - `new_password`: required, string, min:8, different:current_password.
  - `new_password_confirmation`: same:new_password.
- Cek `Hash::check($request->current_password, Auth::user()->password)` → jika gagal, `withErrors(['current_password' => 'Password lama tidak sesuai.'])`.
- Simpan `Auth::user()->update(['password' => Hash::make($request->new_password)])`.
- Redirect ke `profile.show` dengan flash `success`.

---

### Penjaga\DashboardController

`app/Http/Controllers/Penjaga/DashboardController.php`

**`index()`**
- Ambil penjaga yang login: `$user = Auth::user()->load('stand')`.
- Guard: jika `$user->stand_id === null` → return view dengan pesan "Stand belum ditugaskan. Hubungi Admin." (bukan abort 403).
- Hitung stats hanya dari stand sendiri:
  - `$todayOrders`: jumlah pesanan hari ini yang mengandung produk dari stand ini.
  - `$todayRevenue`: total pendapatan hari ini dari stand ini.
  - `$activeProducts`: jumlah produk dengan `status = available` dan `stand_id = $user->stand_id`.
- Return `view('penjaga.dashboard', compact('user', 'todayOrders', 'todayRevenue', 'activeProducts'))`.

---

### Penjaga\ProductController

`app/Http/Controllers/Penjaga/ProductController.php`

**Method privat `authorizeProduct(Product $product): void`**
```php
private function authorizeProduct(Product $product): void
{
    if ($product->stand_id !== Auth::user()->stand_id) {
        abort(403, 'Akses ditolak. Produk ini bukan milik Stand Anda.');
    }
}
```

**`index()`** — tampilkan produk `where stand_id = Auth::user()->stand_id` saja.

**`store(Request $request)`**
- Validasi field produk (name, description, price, stock, image, status, category_id).
- Set `stand_id` secara otomatis dari `Auth::user()->stand_id` — jangan ambil dari request.
- Jika `stock == 0`, otomatis set `status = unavailable`.

**`update(Request $request, Product $product)`**
- Panggil `authorizeProduct($product)`.
- Validasi dan simpan perubahan.
- **Auto-unavailable-on-zero-stock berlaku pada store(), updateStock(), DAN update() sebagai default (AC 4.7).** Namun, Penjaga dapat meng-override status ke `available` secara eksplisit pada setiap edit (AC 4.6). Override ini tidak permanen — pada edit berikutnya, aturan otomatis kembali diterapkan (AC 4.7).

**`edit(Product $product)`** — selain memanggil `authorizeProduct($product)`, method ini HARUS menampilkan status `unavailable` sebagai nilai default pada field status di form edit ketika `stock == 0`, terlepas dari nilai status saat ini di database (AC 4.8). Ini memastikan bahwa override manual dari AC 4.6 memerlukan pilihan aktif dari Penjaga, bukan sekadar menyimpan nilai yang sudah ada.

**`destroy(Product $product)`** — panggil `authorizeProduct($product)`, lalu hapus.

**`updateStock(Request $request, Product $product)`**
- Panggil `authorizeProduct($product)`.
- Update stock, otomatis `status = unavailable` jika stock menjadi 0.

---

### Penjaga\OrderController

`app/Http/Controllers/Penjaga/OrderController.php`

**Method privat `authorizeOrder(Order $order): void`**
```php
private function authorizeOrder(Order $order): void
{
    $standId = Auth::user()->stand_id;
    $hasProduct = $order->orderDetails()
        ->whereHas('product', fn($q) => $q->where('stand_id', $standId))
        ->exists();

    if (!$hasProduct) {
        abort(403, 'Akses ditolak. Pesanan ini tidak mengandung produk dari Stand Anda.');
    }
}
```

**`index()`** — query pesanan yang `whereHas orderDetails → product → stand_id = Auth::user()->stand_id`. Support filter status via query param.

**`show(Order $order)`**
- Panggil `authorizeOrder($order)`.
- Ambil detail pesanan hanya untuk produk milik stand sendiri: `$standOrderDetails = $order->orderDetails()->whereHas('product', fn($q) => $q->where('stand_id', $standId))->get()`.
- **AC 5.7:** Detail pesanan hanya menampilkan item produk dari Stand milik Penjaga — item dari Stand lain dalam pesanan yang sama tidak ditampilkan.

**`updateStatus(Request $request, Order $order)`**
- Panggil `authorizeOrder($order)`.
- Definisikan transisi valid:
  ```php
  $transitions = [
      'Pesanan Dibuat'   => 'Sedang Diproses',
      'Sedang Diproses'  => 'Siap Diambil',
      'Siap Diambil'     => 'Selesai',
  ];
  ```
- Jika `$request->status === 'Dibatalkan'` → silently ignore: redirect back tanpa flash error, status pesanan tidak berubah.
- **No-op same-status (AC 5.5):** Jika `$request->status === $order->status` (sama dengan status saat ini) → redirect back tanpa error dan tanpa perubahan status. Cek ini dilakukan SEBELUM pengecekan transisi tidak valid.
- Jika transisi tidak valid → redirect back dengan error "Perubahan status tidak diizinkan."

---

### Penjaga\ReportController

`app/Http/Controllers/Penjaga/ReportController.php`

**`index(Request $request)`**
- Ambil `stand_id = Auth::user()->stand_id`.
- Filter periode: `today`, `this_week`, `this_month` (default: `today`).
- Hitung: total pendapatan, jumlah pesanan, produk terlaris — semuanya di-scope ke stand sendiri.
- Jika ada query param `stand_id` berbeda → redirect ke `penjaga.reports.index` (laporan stand sendiri) dengan flash `warning` yang menjelaskan akses ditolak, abaikan parameter asing.

---

### Admin\UserController

`app/Http/Controllers/Admin/UserController.php`

**`store(Request $request)`**
- Validasi:
  - `name`: required, string, max:255.
  - `email`: required, email, unique:users.
  - `password`: required, string, min:8.
  - `role`: required, in:siswa,penjaga,admin.
  - `stand_id`: required_if:role,penjaga, exists:stands,id.
- Simpan `Hash::make($request->password)` — tidak pernah plain text.
- Jika role bukan penjaga, set `stand_id = null`.

**`update(Request $request, User $user)`**
- Validasi mirip `store` tetapi password nullable (opsional, hanya jika diisi).
- Guard: panggil `guardLastAdmin()` — reject jika aksi ini akan menghilangkan admin terakhir (role change, deaktivasi, atau penghapusan).
- Jika role berubah dari `penjaga` ke role lain → set `stand_id = null`.
- Jika role diubah menjadi `penjaga` → wajib ada `stand_id`.
- **Invariant `stand_id` (AC 1.6):** Jika role bukan `penjaga`, `stand_id` HARUS di-set ke `null` tanpa peduli nilai yang dikirim dari request — ini adalah invariant yang divalidasi pada setiap operasi penyimpanan.

**`resetPassword(Request $request, User $user)`**
- Validasi: `password` required, min:8, confirmed.
- Simpan `Hash::make($request->password)`.
- Tidak pernah mengembalikan atau menampilkan plain text password.

---

### Admin\UserController — Guard Admin Terakhir

Guard ini melindungi agar sistem tidak kehilangan Admin terakhir yang tersisa. Guard mencakup tiga skenario:

1. **Perubahan role** — admin diubah ke role lain (`$newRole` diberikan, bukan `'admin'`).
2. **Deaktivasi** — admin dinonaktifkan (`$newRole = null`, user tetap ada tetapi tidak aktif).
3. **Penghapusan** — admin dihapus dari sistem (`$newRole = null`).

```php
private function guardLastAdmin(User $user, ?string $newRole = null): void
{
    // Hanya relevan jika user yang dimodifikasi adalah admin
    if ($user->role !== 'admin') {
        return;
    }

    // $newRole !== null  → perubahan role
    // $newRole === null  → deaktivasi atau penghapusan
    if ($newRole !== null && $newRole === 'admin') {
        return; // tetap admin, tidak ada risiko
    }

    $adminCount = User::where('role', 'admin')->count();
    if ($adminCount <= 1) {
        throw new \Exception('Tidak dapat menghilangkan Admin terakhir yang tersisa.');
    }
}
```

**Catatan:** Guard ini HARUS dipanggil sebelum setiap aksi yang dapat menghilangkan admin terakhir — termasuk perubahan role, deaktivasi, dan penghapusan.

---

## Views Baru

### Layout Penjaga

`resources/views/layouts/penjaga.blade.php`

Layout dua-kolom (sidebar + konten utama) dengan warna hijau-putih yang sama. Sidebar berisi:
- Logo & nama sekolah.
- Nama penjaga + nama stand yang ditugaskan.
- Link navigasi: Dashboard, Produk Saya, Pesanan, Laporan.
- Tombol logout.

### Daftar Views

| View | Extends | Keterangan |
|---|---|---|
| `profile/show.blade.php` | `layouts.app` | Form edit profil + avatar upload |
| `profile/change-password.blade.php` | `layouts.app` | Form 3 field ubah password |
| `penjaga/dashboard.blade.php` | `layouts.penjaga` | Stat cards: pesanan, pendapatan, produk aktif |
| `penjaga/products/index.blade.php` | `layouts.penjaga` | Tabel produk stand sendiri + tombol aksi |
| `penjaga/products/create.blade.php` | `layouts.penjaga` | Form tambah produk (tanpa field stand_id) |
| `penjaga/products/edit.blade.php` | `layouts.penjaga` | Form edit produk + konfirmasi |
| `penjaga/orders/index.blade.php` | `layouts.penjaga` | Tabel pesanan + filter status |
| `penjaga/orders/show.blade.php` | `layouts.penjaga` | Detail pesanan + tombol update status |
| `penjaga/reports/index.blade.php` | `layouts.penjaga` | Tabel laporan + filter periode |
| `admin/users/index.blade.php` | `layouts.admin` | Tabel semua pengguna dengan badge role |
| `admin/users/create.blade.php` | `layouts.admin` | Form buat akun baru + dropdown stand |
| `admin/users/edit.blade.php` | `layouts.admin` | Form edit akun + reset password section |

### Navbar Update (`app.blade.php`)

Dropdown user diperluas kondisional:

```blade
@if(Auth::user()->isAdmin())
    {{-- Link admin yang sudah ada --}}
    {{-- Tambah link ke /admin/users --}}
@elseif(Auth::user()->isPenjaga())
    <a href="{{ route('penjaga.dashboard') }}">Dashboard Penjaga</a>
    <a href="{{ route('profile.show') }}">Profil Saya</a>
    <a href="{{ route('password.change') }}">Ubah Password</a>
@else {{-- siswa --}}
    <a href="{{ route('orders.index') }}">Pesanan Saya</a>
    <a href="{{ route('profile.show') }}">Profil Saya</a>
    <a href="{{ route('password.change') }}">Ubah Password</a>
@endif
```

---

## Error Handling

| Skenario | Respons |
|---|---|
| Pengguna tidak login mengakses route protected | Redirect ke `/login` dengan flash `error` |
| Siswa mengakses `/admin/*` atau `/penjaga/*` | `abort(403)` |
| Penjaga mengakses `/admin/*` | `abort(403)` |
| Penjaga akses/edit produk stand lain | `abort(403)` |
| Penjaga akses/update pesanan tanpa produk dari standnya | `abort(403)` |
| Penjaga coba set status `Dibatalkan` | Redirect back tanpa error — status pesanan tidak berubah |
| Penjaga update status ke nilai yang sama | No-op: redirect back tanpa perubahan status |
| Penjaga coba transisi status tidak valid | Redirect back dengan flash `error` |
| Penjaga coba akses laporan Stand lain via URL | Redirect ke laporan Stand milik Penjaga sendiri dengan flash `warning` |
| Penjaga belum memiliki `stand_id` | View dengan pesan informatif (bukan 403) |
| Password lama salah | `withErrors(['current_password' => 'Password lama tidak sesuai.'])` |
| Password baru < 8 karakter | Validation error bawaan Laravel |
| Admin coba ubah role, deaktivasi, atau hapus Admin terakhir | Redirect back dengan flash `error` |
| Role `penjaga` tanpa `stand_id` | Validation error: stand_id required |
| Upload avatar > 2MB atau format salah | Validation error bawaan Laravel |

---

## Correctness Properties

### Property 1: Cross-Role Blocking
Untuk setiap request ke `/admin/*` dari pengguna dengan role `siswa` atau `penjaga`, dan untuk setiap request ke `/penjaga/*` dari pengguna dengan role `siswa` atau `admin`, sistem SHALL mengembalikan HTTP 403 tanpa menampilkan konten yang dilindungi.

**Cara uji:** Gunakan `actingAs()` Laravel dengan berbagai kombinasi role dan route. Verifikasi status response HTTP.

**Validates: Requirements 3.3, 3.7, 9.4, 9.5**

### Property 2: Cross-Stand Blocking
Untuk setiap operasi baca/tulis pada produk atau pesanan oleh penjaga yang `stand_id`-nya **berbeda** dengan `stand_id` resource target, sistem SHALL mengembalikan HTTP 403.

**Cara uji:** Buat dua penjaga dengan stand berbeda. Coba penjaga A mengakses endpoint produk/pesanan milik stand penjaga B. Verifikasi response 403.

**Validates: Requirements 3.6, 4.3, 5.4**

### Property 3: Preservation of Existing Functionality
Untuk setiap fitur yang sudah ada sebelum implementasi fitur ini (alur cart → checkout → QRIS, dashboard admin, data 8 stand), perilaku sistem setelah implementasi SHALL identik — tidak ada route yang hilang, tidak ada data yang terhapus.

**Cara uji:** Jalankan seluruh test suite yang ada sebelum implementasi. Verifikasi semua test tetap hijau setelah perubahan.

**Validates: Requirements 10**

---

## Testing Strategy

### Correctness Properties

#### Property 1 — Cross-Role Blocking (Requirement 9)

Untuk **setiap** request ke `/admin/*` dari pengguna dengan role `siswa` atau `penjaga`, dan untuk setiap request ke `/penjaga/*` dari pengguna dengan role `siswa` atau `admin`, sistem **HARUS** mengembalikan HTTP 403.

Pendekatan uji: gunakan Laravel's `actingAs()` dengan user berbeda-beda (siswa, penjaga, admin) terhadap route-route yang dilindungi middleware, verifikasi status response.

#### Property 2 — Cross-Stand Blocking (Requirement 3.6, 4.3, 5.4)

Untuk **setiap** operasi update/delete produk atau update status pesanan oleh penjaga yang `stand_id`-nya berbeda dengan `stand_id` resource target, sistem **HARUS** mengembalikan HTTP 403.

Pendekatan uji: buat dua penjaga dengan stand berbeda, coba penjaga A mengakses produk/pesanan milik penjaga B, verifikasi 403.

#### Property 3 — Preservation (Requirement 10)

Untuk **setiap** fitur existing (alur cart → checkout, admin dashboard, manajemen 8 stand), perilaku sistem setelah implementasi **HARUS** identik dengan sebelumnya — tidak ada route yang hilang atau berubah nama.

Pendekatan uji: jalankan semua test existing yang sudah ada, pastikan tidak ada yang merah.

### Unit Tests yang Direkomendasikan

- `User::isPenjaga()` — return true hanya jika role = penjaga.
- `User::stand()` — relasi BelongsTo ke Stand.
- `PenjagaMiddleware` — cek redirect dan abort pada berbagai kondisi.
- `Penjaga\ProductController@authorizeProduct` — abort(403) jika stand_id berbeda.
- `Penjaga\OrderController@authorizeOrder` — abort(403) jika tidak ada produk dari stand.
- `Penjaga\OrderController@updateStatus` — transisi valid pass, transisi tidak valid ditolak, Dibatalkan diabaikan (silently ignored), same-status no-op (AC 5.5).
- `Admin\UserController@update` — guard admin terakhir, null-kan stand_id saat ganti role.
- `PasswordController@change` — tolak jika password lama salah, simpan hash jika benar.

---

## Daftar File yang Dibuat/Diubah

### File Baru

| Path | Keterangan |
|---|---|
| `database/migrations/2026_11_02_000001_add_stand_id_to_users_table.php` | Tambah kolom `stand_id` nullable FK |
| `app/Http/Middleware/PenjagaMiddleware.php` | Guard route `/penjaga/*` |
| `app/Http/Controllers/ProfileController.php` | Edit profil semua role |
| `app/Http/Controllers/PasswordController.php` | Ubah password semua role |
| `app/Http/Controllers/Penjaga/DashboardController.php` | Dashboard penjaga |
| `app/Http/Controllers/Penjaga/ProductController.php` | CRUD produk stand sendiri |
| `app/Http/Controllers/Penjaga/OrderController.php` | Lihat & update status pesanan |
| `app/Http/Controllers/Penjaga/ReportController.php` | Laporan penjualan stand sendiri |
| `app/Http/Controllers/Admin/UserController.php` | CRUD + reset password pengguna |
| `resources/views/layouts/penjaga.blade.php` | Layout sidebar penjaga |
| `resources/views/profile/show.blade.php` | Halaman profil |
| `resources/views/profile/change-password.blade.php` | Halaman ubah password |
| `resources/views/penjaga/dashboard.blade.php` | Dashboard penjaga |
| `resources/views/penjaga/products/index.blade.php` | Daftar produk |
| `resources/views/penjaga/products/create.blade.php` | Form tambah produk |
| `resources/views/penjaga/products/edit.blade.php` | Form edit produk |
| `resources/views/penjaga/orders/index.blade.php` | Daftar pesanan |
| `resources/views/penjaga/orders/show.blade.php` | Detail pesanan |
| `resources/views/penjaga/reports/index.blade.php` | Laporan penjualan |
| `resources/views/admin/users/index.blade.php` | Daftar pengguna |
| `resources/views/admin/users/create.blade.php` | Form buat pengguna |
| `resources/views/admin/users/edit.blade.php` | Form edit pengguna |

### File Diubah

| Path | Perubahan |
|---|---|
| `app/Models/User.php` | Tambah `stand_id` ke `$fillable`, method `isPenjaga()`, relasi `stand()` |
| `app/Models/Stand.php` | Tambah relasi `penjaga()` dan `penjagaUsers()` |
| `bootstrap/app.php` | Daftarkan alias middleware `penjaga` |
| `routes/web.php` | Tambah route grup `/penjaga`, route profil/password, route admin users |
| `app/Http/Controllers/AuthController.php` | Tambah redirect ke `penjaga.dashboard` di method `login()` |
| `app/Http/Controllers/Admin/AdminDashboardController.php` | Tambah link ke halaman manajemen pengguna di dashboard |
| `resources/views/layouts/app.blade.php` | Perbarui dropdown user untuk Siswa dan Penjaga |
