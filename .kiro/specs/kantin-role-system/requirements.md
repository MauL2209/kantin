# Requirements Document

## Introduction

Fitur ini menambahkan sistem tiga role pengguna pada aplikasi Kantin MELATIAREA SMK Negeri 20 Jakarta yang sudah berjalan. Saat ini sistem hanya memiliki dua role (`admin` dan `siswa`). Fitur ini memperkenalkan role ketiga yaitu `penjaga` (Penjaga Kantin), beserta fitur manajemen profil dan ubah password untuk semua role, tanpa merusak sistem pemesanan, data, maupun desain yang sudah ada.

---

## Glossary

- **System**: Aplikasi web Kantin MELATIAREA berbasis Laravel
- **Siswa**: Pengguna dengan role `siswa` — pembeli di kantin sekolah
- **Penjaga**: Pengguna dengan role `penjaga` — penjaga kantin yang ditugaskan ke satu stand
- **Admin**: Pengguna dengan role `admin` — pengelola seluruh sistem
- **Stand**: Unit kantin (warung/lapak) tempat produk dijual; ada 8 stand yang sudah terdaftar
- **Produk**: Menu makanan/minuman yang dijual di suatu Stand
- **Pesanan**: Transaksi pembelian yang dibuat oleh Siswa
- **AuthController**: Controller yang menangani login, register, dan logout
- **ProfileController**: Controller yang menangani tampil dan edit profil pengguna
- **PasswordController**: Controller yang menangani ubah password dan reset password
- **PenjagaMiddleware**: Middleware yang memvalidasi akses pengguna dengan role `penjaga`
- **StandAssignment**: Relasi yang menghubungkan satu Penjaga ke satu Stand
- **RoleGuard**: Mekanisme otorisasi di sisi backend (controller/policy) yang memvalidasi hak akses berdasarkan role

---

## Requirements

---

### Requirement 1: Penambahan Role Penjaga pada Sistem

**User Story:** Sebagai Admin, saya ingin menambahkan akun dengan role `penjaga` dan menugaskannya ke sebuah Stand, agar setiap stand memiliki pengelola yang bertanggung jawab.

#### Acceptance Criteria

1. THE System SHALL mendukung tiga nilai role yang valid untuk kolom `role` pada tabel `users`: `siswa`, `penjaga`, dan `admin`.
2. THE System SHALL menyimpan relasi antara satu Penjaga dengan tepat satu Stand melalui kolom `stand_id` pada tabel `users` (nullable untuk role selain `penjaga`).
3. WHEN Admin membuat atau mengedit akun pengguna dengan role `penjaga`, THE System SHALL mewajibkan pemilihan Stand yang ditugaskan.
4. WHEN Admin mengubah role akun dari `penjaga` ke role lain, THE System SHALL mengosongkan (set null) nilai `stand_id` pada akun tersebut.
5. IF sebuah Stand dihapus, THEN THE System SHALL mengosongkan `stand_id` pada akun Penjaga yang ditugaskan ke Stand tersebut (set null, akun tetap aktif dengan role `penjaga` yang tidak berubah, tidak dinonaktifkan maupun dihapus — penjaga dengan `stand_id` null merupakan kondisi sementara hingga Admin menugaskan ulang ke Stand lain).
6. THE System SHALL mempertahankan nilai `stand_id` sebagai `null` untuk semua akun dengan role selain `penjaga` sebagai invariant yang divalidasi pada setiap operasi penyimpanan atau pembaruan data pengguna.

---

### Requirement 2: Manajemen Pengguna oleh Admin

**User Story:** Sebagai Admin, saya ingin mengelola semua akun pengguna, agar saya dapat membuat, mengedit, menonaktifkan, dan mereset password akun Siswa maupun Penjaga.

#### Acceptance Criteria

1. THE Admin_Dashboard SHALL menampilkan daftar semua pengguna dengan kolom: nama, email, role, NIS (jika ada), dan stand yang ditugaskan (jika ada).
2. WHEN Admin membuat akun baru, THE System SHALL mewajibkan field: nama, email, password, dan role.
3. WHEN Admin membuat akun Penjaga, THE System SHALL mewajibkan pemilihan satu Stand dari daftar Stand yang tersedia.
4. WHEN Admin mengedit akun pengguna, THE System SHALL memperbolehkan perubahan pada field: nama, email, role, NIS, kelas, telepon, dan stand yang ditugaskan.
5. THE System SHALL menyimpan password akun menggunakan Laravel password hashing (`bcrypt`) dan tidak pernah menyimpan password dalam bentuk plain text.
6. WHEN Admin mereset password akun pengguna lain, THE System SHALL memperbolehkan Admin memasukkan password baru tanpa perlu mengetahui password lama.
7. IF Admin mencoba mengubah role, menonaktifkan, atau menghapus akun Admin terakhir (satu-satunya Admin), THEN THE System SHALL menolak aksi tersebut dan menampilkan pesan error yang menjelaskan alasannya.

---

### Requirement 3: Dashboard dan Akses Penjaga Kantin

**User Story:** Sebagai Penjaga Kantin, saya ingin memiliki dashboard khusus untuk mengelola Stand saya sendiri, agar saya dapat mengatur menu, stok, dan memproses pesanan yang masuk ke Stand saya.

#### Acceptance Criteria

1. WHEN Penjaga melakukan login berhasil, THE System SHALL mengarahkan Penjaga ke halaman dashboard Penjaga (`/penjaga/dashboard`), bukan ke halaman admin maupun halaman beranda Siswa.
2. THE Penjaga_Dashboard SHALL menampilkan ringkasan: jumlah pesanan masuk hari ini, total pendapatan hari ini, dan jumlah produk aktif untuk Stand milik Penjaga tersebut.
3. THE System SHALL menerapkan PenjagaMiddleware pada semua route dengan prefix `/penjaga/*` yang menolak akses jika pengguna bukan role `penjaga`.
4. WHILE Penjaga mengakses halaman manajemen produk, THE System SHALL hanya menampilkan produk yang `stand_id`-nya sesuai dengan Stand yang ditugaskan kepada Penjaga tersebut.
5. WHILE Penjaga mengakses halaman manajemen pesanan, THE System SHALL hanya menampilkan pesanan yang mengandung setidaknya satu produk dari Stand milik Penjaga tersebut.
6. IF Penjaga mencoba mengakses atau memodifikasi produk dari Stand lain melalui URL manipulation, THEN THE System SHALL menolak request dengan HTTP 403 Forbidden.
7. IF Penjaga mencoba mengakses route dengan prefix `/admin/*`, THEN THE System SHALL menolak akses dengan HTTP 403 Forbidden.

---

### Requirement 4: Manajemen Produk oleh Penjaga

**User Story:** Sebagai Penjaga Kantin, saya ingin dapat menambah, mengedit, dan mengubah ketersediaan produk di Stand saya, agar menu yang ditampilkan kepada Siswa selalu akurat.

#### Acceptance Criteria

1. WHEN Penjaga menambahkan produk baru, THE System SHALL secara otomatis menetapkan `stand_id` produk tersebut sesuai Stand yang ditugaskan kepada Penjaga yang sedang login.
2. WHEN Penjaga mengedit produk, THE System SHALL memperbolehkan perubahan pada field: nama, deskripsi, harga, stok, gambar, status (`available`/`unavailable`), dan kategori.
3. IF Penjaga mengirim request untuk mengedit produk yang `stand_id`-nya tidak sesuai dengan Stand miliknya, THEN THE System SHALL menolak request tersebut dengan HTTP 403 Forbidden.
4. WHEN Penjaga mengubah status produk menjadi `unavailable`, THE System SHALL tidak menampilkan produk tersebut di halaman belanja Siswa.
5. WHEN Penjaga mengubah stok produk menjadi 0, THE System SHALL secara otomatis mengubah status produk menjadi `unavailable`.
6. WHEN Penjaga mengubah stok produk menjadi 0 dan sistem secara otomatis menetapkan status menjadi `unavailable`, THE System SHALL tetap memperbolehkan Penjaga untuk mengubah status produk kembali menjadi `available` secara manual melalui fitur edit produk, terlepas dari nilai stok.
7. WHEN produk dengan stok 0 yang sebelumnya telah diatur secara manual ke `available` diedit kembali melalui fitur edit produk, THE System SHALL menerapkan kembali aturan otomatis dari AC 4.5 sebagai perilaku default — status kembali menjadi `unavailable` kecuali Penjaga secara eksplisit memilih `available` pada edit tersebut, sehingga pilihan manual sebelumnya tidak bersifat permanen.
8. WHEN Penjaga membuka halaman edit produk dengan stok 0, THE System SHALL menampilkan status `unavailable` sebagai nilai default pada field status di form edit, terlepas dari nilai status saat ini di database, sehingga Penjaga harus secara aktif memilih `available` untuk melakukan override manual.

---

### Requirement 5: Manajemen Pesanan oleh Penjaga

**User Story:** Sebagai Penjaga Kantin, saya ingin dapat melihat dan memperbarui status pesanan yang berkaitan dengan Stand saya, agar saya dapat memproses pesanan secara efisien.

#### Acceptance Criteria

1. THE Penjaga_Order_Page SHALL menampilkan pesanan yang berisi produk dari Stand milik Penjaga, yang dapat difilter berdasarkan status: Pesanan Dibuat, Sedang Diproses, Siap Diambil, Selesai.
2. WHEN Penjaga memperbarui status pesanan, THE System SHALL hanya memperbolehkan transisi status berikut: `Pesanan Dibuat` → `Sedang Diproses` → `Siap Diambil` → `Selesai`.
3. IF Penjaga mencoba mengubah status pesanan ke `Dibatalkan`, THEN THE System SHALL mengabaikan request tersebut tanpa menampilkan error eksplisit dan status pesanan tetap tidak berubah — hanya Admin yang dapat membatalkan pesanan.
4. IF Penjaga mencoba memperbarui status pesanan yang tidak memiliki produk dari Stand miliknya, THEN THE System SHALL menolak request tersebut dengan HTTP 403 Forbidden.
5. WHEN Penjaga memperbarui status pesanan ke nilai yang sama dengan status saat ini (misalnya `Pesanan Dibuat` ke `Pesanan Dibuat`), THE System SHALL memperlakukan permintaan tersebut sebagai no-op yang diizinkan tanpa mengubah status atau menampilkan pesan error.
6. IF Penjaga mencoba melakukan transisi status yang tidak valid (melompat urutan atau mundur ke status sebelumnya, misalnya `Pesanan Dibuat` langsung ke `Siap Diambil`), THEN THE System SHALL menolak request tersebut dengan pesan error eksplisit — tidak mengabaikan secara diam-diam.
7. WHILE Penjaga melihat detail pesanan yang mengandung produk dari beberapa Stand, THE System SHALL hanya menampilkan item produk dari Stand milik Penjaga tersebut — item dari Stand lain tidak ditampilkan dalam detail pesanan.

---

### Requirement 6: Laporan Penjualan Stand milik Penjaga

**User Story:** Sebagai Penjaga Kantin, saya ingin melihat laporan penjualan Stand saya sendiri, agar saya dapat memantau performa penjualan harian dan mingguan.

#### Acceptance Criteria

1. THE Penjaga_Report_Page SHALL menampilkan total pendapatan, jumlah pesanan, dan produk terlaris untuk Stand milik Penjaga, yang dapat difilter berdasarkan periode: hari ini, minggu ini, dan bulan ini.
2. THE Penjaga_Report_Page SHALL hanya menampilkan data yang berasal dari produk Stand milik Penjaga yang sedang login.
3. IF Penjaga mencoba mengakses laporan Stand lain melalui manipulasi parameter URL, THEN THE System SHALL mengarahkan (redirect) Penjaga kembali ke halaman laporan Stand miliknya sendiri dan menampilkan pesan peringatan yang menjelaskan bahwa akses ditolak.

---

### Requirement 7: Manajemen Profil Pengguna (Siswa dan Penjaga)

**User Story:** Sebagai Siswa atau Penjaga, saya ingin dapat melihat dan memperbarui informasi profil saya, agar data akun saya selalu akurat.

#### Acceptance Criteria

1. THE System SHALL menyediakan halaman profil yang dapat diakses oleh Siswa dan Penjaga yang sudah login, menampilkan: foto profil (avatar), nama, email, NIS (jika Siswa), kelas (jika Siswa), nomor telepon, dan role.
2. WHEN Siswa mengedit profil, THE System SHALL memperbolehkan perubahan pada field: nama, NIS, kelas, dan nomor telepon.
3. WHEN Penjaga mengedit profil, THE System SHALL memperbolehkan perubahan pada field: nama dan nomor telepon.
4. THE System SHALL memperbolehkan Siswa dan Penjaga mengunggah foto profil (avatar) dengan format file JPG, JPEG, PNG, atau WEBP berukuran maksimum 2 MB.
5. IF pengguna mengirim request untuk mengubah field `role`, `id`, atau `stand_id` melalui form profil, THEN THE System SHALL mengabaikan field tersebut dan tidak mengubah nilainya di database.
6. WHEN Siswa atau Penjaga menyimpan perubahan profil, THE System SHALL memvalidasi bahwa nama tidak kosong dan nomor telepon (jika diisi) hanya mengandung digit angka dengan panjang 10–15 karakter.
7. THE System SHALL menampilkan field NIS dan kelas pada halaman profil hanya jika pengguna memiliki role `siswa`, dan SHALL menyembunyikan (tidak menampilkan) field tersebut untuk role `penjaga` dan `admin`.
8. IF avatar yang diunggah gagal validasi format dan ukuran secara bersamaan, THEN THE System SHALL menampilkan semua pesan error yang relevan sekaligus (misalnya format tidak valid dan ukuran terlalu besar), bukan hanya satu pesan berdasarkan prioritas pengecekan.
9. IF avatar yang diunggah gagal validasi, THE System SHALL tetap menyimpan perubahan pada field lain yang valid (nama, nomor telepon, NIS, kelas) dan hanya menolak avatar yang gagal validasi — tidak membatalkan seluruh penyimpanan form profil.

---

### Requirement 8: Ubah Password (Siswa dan Penjaga)

**User Story:** Sebagai Siswa atau Penjaga, saya ingin dapat mengubah password saya sendiri, agar keamanan akun saya terjaga.

#### Acceptance Criteria

1. THE System SHALL menyediakan halaman ubah password yang terpisah dari halaman profil, yang HANYA dapat diakses oleh pengguna dengan role `siswa` atau `penjaga` yang sedang login — role `admin` tidak dapat mengakses halaman ini.
2. WHEN pengguna mengirimkan form ubah password, THE System SHALL mewajibkan tiga field: password lama, password baru, dan konfirmasi password baru.
3. IF semua field yang diwajibkan pada AC 8.2 telah terisi dan password lama yang dimasukkan tidak cocok dengan password yang tersimpan di database, THEN THE System SHALL menolak perubahan dan menampilkan pesan error: "Password lama tidak sesuai."
4. IF password baru kurang dari 8 karakter, THEN THE System SHALL menolak perubahan dan menampilkan pesan validasi.
5. IF konfirmasi password baru tidak cocok dengan password baru, THEN THE System SHALL menolak perubahan dan menampilkan pesan validasi.
6. WHEN ubah password berhasil, THE System SHALL menyimpan password baru dalam format hash menggunakan Laravel password hashing dan tidak pernah menyimpan plain text.

---

### Requirement 9: Otorisasi Berbasis Role di Backend

**User Story:** Sebagai Admin, saya ingin sistem mengamankan semua endpoint di sisi server berdasarkan role pengguna, agar tidak ada pengguna yang dapat mengakses data di luar haknya meskipun memanipulasi URL atau request.

#### Acceptance Criteria

1. THE System SHALL menerapkan RoleGuard pada setiap controller yang menangani data sensitif (pengguna, produk, pesanan, laporan), memvalidasi role pengguna yang sedang login sebelum mengeksekusi aksi apapun.
2. THE System SHALL menggunakan middleware (bukan hanya menyembunyikan elemen UI) sebagai mekanisme utama pembatasan akses ke route-route yang dilindungi.
3. IF pengguna yang tidak terautentikasi mengakses route yang dilindungi, THEN THE System SHALL mengarahkan pengguna ke halaman login.
4. IF pengguna dengan role `siswa` mencoba mengakses route `/admin/*` atau `/penjaga/*`, THEN THE System SHALL merespons dengan HTTP 403 Forbidden.
5. IF pengguna dengan role `penjaga` mencoba mengakses route `/admin/*`, THEN THE System SHALL merespons dengan HTTP 403 Forbidden.
6. THE System SHALL tidak mengandalkan field `role` dari request body (form/POST data) untuk menentukan hak akses — validasi role harus selalu berasal dari data sesi autentikasi.

---

### Requirement 10: Preservasi Sistem yang Sudah Ada

**User Story:** Sebagai Admin, saya ingin memastikan semua fitur yang sudah berjalan tetap berfungsi setelah penambahan role baru, agar operasional kantin tidak terganggu.

#### Acceptance Criteria

1. THE System SHALL mempertahankan semua 8 Stand yang sudah ada di database tanpa menghapus atau mengubah data Stand tersebut.
2. THE System SHALL mempertahankan alur pemesanan yang sudah ada: Siswa dapat menelusuri produk, menambahkan ke keranjang, checkout dengan metode pembayaran QRIS/cash/transfer, dan melacak status pesanan.
3. THE System SHALL mempertahankan fungsionalitas dashboard Admin yang sudah ada, termasuk statistik, grafik, daftar pesanan terkini, manajemen produk, stand, kategori, dan laporan.
4. THE System SHALL mempertahankan desain visual hijau dan putih yang sudah diterapkan pada seluruh halaman aplikasi.
5. IF migrasi database baru ditambahkan (contoh: kolom `stand_id` pada tabel `users`), THEN migrasi tersebut HARUS bersifat additive (menambah kolom, termasuk constraint seperti NOT NULL, unique, atau foreign key pada kolom baru tersebut) dan tidak menghapus atau memodifikasi kolom yang sudah ada.
