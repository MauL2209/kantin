<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kantin Sekolah MELATIAREA - Pesan Makanan Kantin Online')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS (CDN for zero-config reliability + Vite) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        },
                        school: {
                            blue: '#1e3a8a',
                            amber: '#f59e0b',
                            dark: '#0f172a'
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js for lightweight interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen antialiased selection:bg-brand-500 selection:text-white">

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-emerald-800 via-teal-900 to-slate-900 text-emerald-100 text-xs py-2 px-4 shadow-sm border-b border-emerald-700/40">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-semibold text-[11px] border border-emerald-400/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sistem Pre-Order Kantin
                </span>
                <span class="hidden sm:inline text-emerald-200/80">|</span>
                <span class="hidden md:inline text-emerald-200/90">
                    <i class="far fa-clock mr-1 text-emerald-400"></i> Jam Operasional: <strong>Senin - Jumat, 07.00 - 14.00 WIB</strong>
                </span>
            </div>
            <div class="flex items-center gap-4 text-xs font-medium">
                <span class="hidden lg:inline text-emerald-300/80">
                    <i class="fas fa-store mr-1"></i> 8 Stand Jurusan Siap Melayani
                </span>
                @if(Auth::check() && Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold rounded-lg transition shadow-sm text-[11px]">
                        <i class="fas fa-gauge-high"></i> Dashboard Admin
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-sm transition" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <!-- School & Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3.5 group">
                    <div class="w-12 h-12 group-hover:scale-105 transition duration-300 flex items-center justify-center flex-shrink-0">
                        <img src="{{ asset('images/logo-smkn20.png') }}" alt="Logo SMK Negeri 20 Jakarta" class="w-full h-full object-contain drop-shadow-sm">
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200/60">SMK NEGERI 20 JAKARTA</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900 group-hover:text-brand-600 transition">
                            Kantin <span class="text-brand-600">MELATIAREA</span>
                        </h1>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-1 lg:gap-2">
                    <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-100/70' }}">
                        <i class="fas fa-home mr-1.5 opacity-70"></i> Beranda
                    </a>
                    <a href="{{ route('stands.index') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('stands.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-100/70' }}">
                        <i class="fas fa-store mr-1.5 opacity-70"></i> 8 Stand Kantin
                    </a>
                    <a href="{{ route('menu.index') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('menu.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-100/70' }}">
                        <i class="fas fa-utensils mr-1.5 opacity-70"></i> Menu Lengkap
                    </a>
                    <a href="{{ route('orders.index') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('orders.*') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-100/70' }}">
                        <i class="fas fa-receipt mr-1.5 opacity-70"></i> Pesanan Saya
                    </a>
                </nav>

                <!-- Actions: Cart & Auth -->
                <div class="flex items-center gap-3">

                    <!-- Cart Button with Live Counter -->
                    @php
                        $cartItemsCount = array_sum(array_column(session('cart', []), 'quantity'));
                    @endphp
                    <a href="{{ route('cart.index') }}" class="relative p-2.5 rounded-xl text-slate-700 hover:text-brand-700 hover:bg-emerald-50/80 transition border border-slate-200 hover:border-emerald-300" title="Keranjang Belanja">
                        <i class="fas fa-bag-shopping text-lg"></i>
                        @if($cartItemsCount > 0)
                            <span class="absolute -top-1.5 -right-1.5 bg-brand-600 text-white font-extrabold text-[11px] w-5 h-5 rounded-full flex items-center justify-center ring-2 ring-white shadow-sm animate-pulse">
                                {{ $cartItemsCount }}
                            </span>
                        @endif
                    </a>

                    <!-- User / Auth Section -->
                    @auth
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50/50 transition">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-brand-600 to-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="hidden sm:block text-left text-xs">
                                    <p class="font-bold text-slate-800 leading-tight max-w-[100px] truncate">{{ Auth::user()->name }}</p>
                                    <span class="text-[10px] text-brand-600 font-semibold">{{ Auth::user()->isAdmin() ? 'Administrator' : (Auth::user()->class_room ?? 'Siswa') }}</span>
                                </div>
                                <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" x-cloak x-transition.origin.top.right class="absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-xl border border-slate-100 py-2 z-50">
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-xs text-slate-400">Masuk sebagai:</p>
                                    <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-brand-700 font-medium">
                                        <i class="fas fa-gauge-high text-amber-500 w-4"></i> Dashboard Admin
                                    </a>
                                    <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-brand-700 font-medium">
                                        <i class="fas fa-clipboard-list text-blue-500 w-4"></i> Kelola Pesanan
                                    </a>
                                    <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-brand-700 font-medium">
                                        <i class="fas fa-chart-line text-brand-600 w-4"></i> Laporan Penjualan
                                    </a>
                                    <div class="border-t border-slate-100 my-1"></div>
                                @endif

                                <a href="{{ route('orders.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-brand-700 font-medium">
                                    <i class="fas fa-receipt text-slate-400 w-4"></i> Pesanan Saya
                                </a>

                                <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-slate-100 pt-1">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 font-medium transition">
                                        <i class="fas fa-arrow-right-from-bracket w-4"></i> Keluar (Logout)
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="hidden sm:flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:text-brand-700 hover:bg-slate-100 transition">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-sm font-bold bg-brand-600 hover:bg-brand-700 text-white shadow-sm hover:shadow transition">
                                Daftar Siswa
                            </a>
                        </div>
                        <a href="{{ route('login') }}" class="sm:hidden p-2.5 rounded-xl border border-slate-200 text-slate-700">
                            <i class="fas fa-user"></i>
                        </a>
                    @endauth

                    <!-- Mobile Hamburger -->
                    <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2.5 rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-none">
                        <i class="fas fa-bars text-xl" x-show="!mobileMenu"></i>
                        <i class="fas fa-xmark text-xl" x-show="mobileMenu" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Drawer -->
        <div x-show="mobileMenu" x-cloak x-transition class="md:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-6 space-y-2">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700' }}">
                <i class="fas fa-home w-5 text-emerald-600"></i> Beranda
            </a>
            <a href="{{ route('stands.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('stands.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700' }}">
                <i class="fas fa-store w-5 text-emerald-600"></i> 8 Stand Kantin
            </a>
            <a href="{{ route('menu.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('menu.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700' }}">
                <i class="fas fa-utensils w-5 text-emerald-600"></i> Menu Lengkap
            </a>
            <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('orders.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700' }}">
                <i class="fas fa-receipt w-5 text-emerald-600"></i> Pesanan Saya
            </a>
            <a href="{{ route('cart.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 bg-slate-50">
                <span class="flex items-center gap-3">
                    <i class="fas fa-bag-shopping w-5 text-emerald-600"></i> Keranjang Belanja
                </span>
                <span class="bg-brand-600 text-white text-xs px-2 py-0.5 rounded-full font-bold">
                    {{ $cartItemsCount }} item
                </span>
            </a>
            @guest
                <div class="pt-4 grid grid-cols-2 gap-2 border-t border-slate-100">
                    <a href="{{ route('login') }}" class="text-center py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700">Masuk</a>
                    <a href="{{ route('register') }}" class="text-center py-2.5 rounded-xl bg-brand-600 text-white text-sm font-bold">Daftar</a>
                </div>
            @endguest
        </div>
    </header>

    <!-- Global Alert / Toast Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 flex items-center justify-between p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 shadow-sm transition">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-emerald-700 hover:text-emerald-900"><i class="fas fa-times"></i></button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 flex items-center justify-between p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 shadow-sm transition">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation text-sm"></i>
                    </div>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="text-rose-700 hover:text-rose-900"><i class="fas fa-times"></i></button>
            </div>
        @endif

        @if(session('warning'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 flex items-center justify-between p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 shadow-sm transition">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-triangle-exclamation text-sm"></i>
                    </div>
                    <p class="text-sm font-medium">{{ session('warning') }}</p>
                </div>
                <button @click="show = false" class="text-amber-700 hover:text-amber-900"><i class="fas fa-times"></i></button>
            </div>
        @endif

        @if(session('info'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 flex items-center justify-between p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 shadow-sm transition">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-info text-sm"></i>
                    </div>
                    <p class="text-sm font-medium">{{ session('info') }}</p>
                </div>
                <button @click="show = false" class="text-blue-700 hover:text-blue-900"><i class="fas fa-times"></i></button>
            </div>
        @endif
    </div>

    <!-- Main Page Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    @stack('scripts')
</body>
</html>

