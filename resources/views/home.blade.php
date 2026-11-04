@extends('layouts.app')

@section('title', 'Kantin Sekolah MELATIAREA - Pesan Makanan Favoritmu dengan Mudah')

@section('content')

    <!-- HERO SECTION -->
    <section class="relative overflow-hidden bg-gradient-to-b from-emerald-900 via-teal-950 to-slate-900 text-white pt-12 pb-24 md:pt-16 md:pb-32">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:20px_20px] pointer-events-none"></div>
        <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-emerald-500/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-teal-500/20 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <!-- Left Column: Copywriting & CTAs -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-emerald-300 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        Platform Kantin Sekolah Digital Resmi
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-center lg:justify-start gap-3">
                            <!-- Logo Resmi SMK Negeri 20 Jakarta -->
                            <div class="w-16 h-16 flex items-center justify-center flex-shrink-0 drop-shadow-lg">
                                <img src="{{ asset('images/logo-smkn20.png') }}" alt="Logo SMK Negeri 20 Jakarta" class="w-full h-full object-contain">
                            </div>
                            <span class="text-xs uppercase tracking-widest text-emerald-300 font-bold bg-emerald-950/60 px-3 py-1 rounded-lg border border-emerald-500/30">
                                SMK NEGERI 20 JAKARTA
                            </span>
                        </div>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-tight">
                            Kantin Sekolah <br>
                            <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-amber-300 bg-clip-text text-transparent">
                                MELATIAREA
                            </span>
                        </h1>
                        <p class="text-xl sm:text-2xl font-bold text-emerald-200">
                            Pesan Makanan Favoritmu dengan Mudah
                        </p>
                    </div>

                    <p class="text-base text-slate-300 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        Nikmati kemudahan memesan makanan dan minuman lezat dari <strong>8 Stand Jurusan SMK</strong> secara online. Pesan lebih awal melalui sistem pre-order dan ambil pesananmu saat jam istirahat tanpa perlu antre berdesakan!
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-2">
                        <a href="{{ route('menu.index') }}" class="px-7 py-3.5 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-base transition duration-300 shadow-lg shadow-emerald-500/30 flex items-center gap-2.5 transform hover:-translate-y-0.5">
                            <i class="fas fa-utensils"></i> Pesan Sekarang
                        </a>
                        <a href="{{ route('stands.index') }}" class="px-7 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-base transition duration-300 border border-white/20 backdrop-blur-md flex items-center gap-2.5">
                            <i class="fas fa-store"></i> Lihat 8 Stand
                        </a>
                    </div>

                    <!-- Quick Stats -->
                    <div class="pt-6 grid grid-cols-3 gap-4 border-t border-white/10 max-w-lg mx-auto lg:mx-0">
                        <div class="text-center lg:text-left">
                            <span class="block text-2xl sm:text-3xl font-black text-amber-300">8 Stand</span>
                            <span class="text-xs text-slate-300">Berbasis Jurusan</span>
                        </div>
                        <div class="text-center lg:text-left">
                            <span class="block text-2xl sm:text-3xl font-black text-emerald-300">{{ $totalProducts }}+ Menu</span>
                            <span class="text-xs text-slate-300">Makanan & Minuman</span>
                        </div>
                        <div class="text-center lg:text-left">
                            <span class="block text-2xl sm:text-3xl font-black text-teal-300">0 Menit</span>
                            <span class="text-xs text-slate-300">Bebas Antre Istirahat</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Visual Card & Jam Operasional -->
                <div class="lg:col-span-5 space-y-5">
                    <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-6 sm:p-7 shadow-2xl relative overflow-hidden">
                        <div class="flex items-center justify-between pb-5 border-b border-white/15">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center text-lg border border-emerald-400/30">
                                    <i class="far fa-clock"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-white">Informasi & Jam Operasional</h3>
                                    <p class="text-xs text-emerald-300">Kantin Sehat Melatiarea</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500 text-slate-950 font-black text-xs">
                                <span class="w-2 h-2 rounded-full bg-slate-950"></span> Buka
                            </span>
                        </div>

                        <!-- Schedule List -->
                        <div class="py-5 space-y-3.5 text-sm">
                            <div class="flex justify-between items-center bg-black/20 p-3 rounded-xl border border-white/10">
                                <span class="text-slate-300 font-medium"><i class="fas fa-calendar-day text-emerald-400 mr-2"></i>Senin - Jumat</span>
                                <span class="text-amber-300 font-extrabold font-mono">07.00 - 14.00 WIB</span>
                            </div>
                            <div class="flex justify-between items-center bg-black/20 p-3 rounded-xl border border-white/10">
                                <span class="text-slate-400 font-medium"><i class="fas fa-calendar-xmark text-rose-400 mr-2"></i>Sabtu & Minggu</span>
                                <span class="text-rose-300 font-semibold text-xs">Libur Akhir Pekan</span>
                            </div>
                        </div>

                        <!-- Info Banner Pengambilan -->
                        <div class="bg-gradient-to-r from-emerald-500/20 to-teal-500/20 border border-emerald-400/40 rounded-2xl p-4 text-xs space-y-2">
                            <div class="flex items-center gap-2 font-bold text-emerald-300">
                                <i class="fas fa-bullhorn text-sm"></i> Sistem Pemesanan & Pengambilan
                            </div>
                            <p class="text-slate-300 leading-relaxed">
                                Pesan menu sebelum jam <strong>09.45 WIB</strong> (Istirahat 1) atau <strong>12.00 WIB</strong> (Istirahat 2). Pesanan dapat diambil langsung di stand jurusan masing-masing dengan menunjukkan nomor pesanan.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION: MENU FAVORIT SISWA -->
    <section class="py-20 bg-white border-y border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-800 font-bold text-xs tracking-wider uppercase mb-2">
                        <i class="fas fa-fire text-amber-500"></i> Rekomendasi Terlaris
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                        Menu Favorit Siswa
                    </h2>
                    <p class="text-sm text-slate-600 mt-1">
                        Pilihan makanan dan minuman favorit yang paling banyak diminati siswa.
                    </p>
                </div>
                <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-700 hover:text-emerald-800 group flex-shrink-0">
                    <span>Lihat Semua Menu</span>
                    <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition text-xs"></i>
                </a>
            </div>

            <!-- Grid 4 Produk: 2 Makanan + 2 Minuman -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- 2 Produk Makanan Terlaris --}}
                @foreach($featuredMakanan as $product)
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden group">
                        <!-- Product Image -->
                        <div class="relative h-44 w-full bg-slate-100 overflow-hidden">
                            <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500' }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 rounded-full bg-emerald-600 text-white font-extrabold text-[10px] shadow-sm uppercase tracking-wider">
                                    {{ $product->category->name }}
                                </span>
                            </div>
                            @if($product->stock <= 0)
                                <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-[2px] flex items-center justify-center">
                                    <span class="px-3 py-1 rounded-full bg-rose-600 text-white font-extrabold text-xs tracking-wider uppercase shadow-md">Habis</span>
                                </div>
                            @endif
                        </div>
                        <!-- Product Content -->
                        <div class="p-5 flex-grow flex flex-col justify-between space-y-3">
                            <div>
                                <span class="text-[11px] font-bold text-emerald-700 tracking-wide uppercase block mb-0.5">
                                    {{ $product->stand->name }}
                                </span>
                                <h3 class="text-base font-bold text-slate-900 leading-snug group-hover:text-emerald-700 transition">
                                    {{ $product->name }}
                                </h3>
                                <p class="text-xs text-slate-500 line-clamp-2 mt-1 leading-relaxed">
                                    {{ $product->description }}
                                </p>
                            </div>
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] text-slate-400 font-semibold block leading-tight">Harga Satuan</span>
                                    <span class="text-base font-black text-slate-900">{{ $product->formatted_price }}</span>
                                </div>
                                <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">
                                    Stok: <strong>{{ $product->stock }}</strong>
                                </span>
                            </div>
                            @if($product->isAvailable())
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="pt-1">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition duration-200 shadow-sm flex items-center justify-center gap-2">
                                        <i class="fas fa-plus text-[10px]"></i>
                                        <span>Tambah ke Keranjang</span>
                                    </button>
                                </form>
                            @else
                                <button disabled class="w-full py-2.5 rounded-xl bg-slate-200 text-slate-400 font-bold text-xs cursor-not-allowed flex items-center justify-center gap-2">
                                    <i class="fas fa-ban text-[10px]"></i>
                                    <span>Stok Habis</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach

                {{-- 2 Produk Minuman Terlaris --}}
                @foreach($featuredMinuman as $product)
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden group">
                        <!-- Product Image -->
                        <div class="relative h-44 w-full bg-slate-100 overflow-hidden">
                            <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=500' }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 rounded-full bg-sky-600 text-white font-extrabold text-[10px] shadow-sm uppercase tracking-wider">
                                    {{ $product->category->name }}
                                </span>
                            </div>
                            @if($product->stock <= 0)
                                <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-[2px] flex items-center justify-center">
                                    <span class="px-3 py-1 rounded-full bg-rose-600 text-white font-extrabold text-xs tracking-wider uppercase shadow-md">Habis</span>
                                </div>
                            @endif
                        </div>
                        <!-- Product Content -->
                        <div class="p-5 flex-grow flex flex-col justify-between space-y-3">
                            <div>
                                <span class="text-[11px] font-bold text-emerald-700 tracking-wide uppercase block mb-0.5">
                                    {{ $product->stand->name }}
                                </span>
                                <h3 class="text-base font-bold text-slate-900 leading-snug group-hover:text-emerald-700 transition">
                                    {{ $product->name }}
                                </h3>
                                <p class="text-xs text-slate-500 line-clamp-2 mt-1 leading-relaxed">
                                    {{ $product->description }}
                                </p>
                            </div>
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] text-slate-400 font-semibold block leading-tight">Harga Satuan</span>
                                    <span class="text-base font-black text-slate-900">{{ $product->formatted_price }}</span>
                                </div>
                                <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">
                                    Stok: <strong>{{ $product->stock }}</strong>
                                </span>
                            </div>
                            @if($product->isAvailable())
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="pt-1">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition duration-200 shadow-sm flex items-center justify-center gap-2">
                                        <i class="fas fa-plus text-[10px]"></i>
                                        <span>Tambah ke Keranjang</span>
                                    </button>
                                </form>
                            @else
                                <button disabled class="w-full py-2.5 rounded-xl bg-slate-200 text-slate-400 font-bold text-xs cursor-not-allowed flex items-center justify-center gap-2">
                                    <i class="fas fa-ban text-[10px]"></i>
                                    <span>Stok Habis</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- Tombol Lihat Semua Menu -->
            <div class="mt-10 text-center">
                <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2.5 px-8 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm transition duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-utensils"></i>
                    Lihat Semua Menu
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

        </div>
    </section>

    <!-- SECTION: CARA PEMESANAN DI KANTIN MELATIAREA -->
    <section class="py-20 bg-slate-900 text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="inline-block px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 font-bold text-xs tracking-wider uppercase border border-emerald-400/30">
                    Panduan Siswa
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                    Cara Pesan Mudah Tanpa Antri
                </h2>
                <p class="text-sm text-slate-300">
                    Ikuti 4 langkah sederhana berikut untuk memesan sarapan atau makan siangmu di kantin sekolah.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="bg-slate-800/80 rounded-3xl p-6 border border-slate-700/80 text-center relative group hover:border-emerald-500 transition">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 text-emerald-400 font-black text-xl mx-auto flex items-center justify-center mb-4 border border-emerald-400/30 group-hover:scale-110 transition">
                        1
                    </div>
                    <h3 class="text-base font-bold text-white mb-2">Pilih Stand & Menu</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Pilih makanan atau minuman favoritmu dari 8 stand jurusan yang tersedia di website.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="bg-slate-800/80 rounded-3xl p-6 border border-slate-700/80 text-center relative group hover:border-emerald-500 transition">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 text-emerald-400 font-black text-xl mx-auto flex items-center justify-center mb-4 border border-emerald-400/30 group-hover:scale-110 transition">
                        2
                    </div>
                    <h3 class="text-base font-bold text-white mb-2">Masukkan Keranjang</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Atur jumlah porsi sesuai keinginan dan lakukan checkout dengan mengisi nama dan kelas.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="bg-slate-800/80 rounded-3xl p-6 border border-slate-700/80 text-center relative group hover:border-emerald-500 transition">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 text-emerald-400 font-black text-xl mx-auto flex items-center justify-center mb-4 border border-emerald-400/30 group-hover:scale-110 transition">
                        3
                    </div>
                    <h3 class="text-base font-bold text-white mb-2">Pilih Pembayaran</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Pilih metode bayar di kasir, transfer bank, atau scan QRIS praktis dari smartphone-mu.
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="bg-slate-800/80 rounded-3xl p-6 border border-slate-700/80 text-center relative group hover:border-emerald-500 transition">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 text-emerald-400 font-black text-xl mx-auto flex items-center justify-center mb-4 border border-emerald-400/30 group-hover:scale-110 transition">
                        4
                    </div>
                    <h3 class="text-base font-bold text-white mb-2">Ambil Makananmu</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Saat jam istirahat, tunjukkan nomor pesanan di stand jurusan dan nikmati makananmu hangat!
                    </p>
                </div>
            </div>

            <!-- Bottom CTA Banner -->
            <div class="mt-14 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-3xl p-8 sm:p-10 flex flex-col sm:flex-row items-center justify-between gap-6 shadow-xl">
                <div class="space-y-1 text-center sm:text-left">
                    <h3 class="text-2xl font-black text-white">Sudah lapar? Jangan sampai kehabisan!</h3>
                    <p class="text-emerald-100 text-sm">Pesan menu istirahatmu sekarang juga sebelum stok harian habis.</p>
                </div>
                <a href="{{ route('menu.index') }}" class="px-8 py-3.5 rounded-2xl bg-white text-slate-900 font-extrabold text-sm hover:bg-slate-100 transition shadow-lg flex-shrink-0">
                    Buka Daftar Menu <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

        </div>
    </section>

@endsection
