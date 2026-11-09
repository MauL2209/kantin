<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - Kantin Sekolah MELATIAREA')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS (CDN + Vite) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            900: '#064e3b',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex">

        <!-- SIDEBAR -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col justify-between"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

            <div>
                <!-- Brand Header -->
                <div class="h-20 flex items-center gap-3 px-6 border-b border-slate-800 bg-slate-950/40">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-500 to-teal-400 p-0.5 flex items-center justify-center">
                        <div class="w-full h-full bg-slate-900 rounded-[10px] flex flex-col items-center justify-center text-center p-0.5">
                            <span class="text-[8px] font-black text-brand-400 leading-none">LOGO</span>
                            <span class="text-[7px] font-extrabold text-slate-400 leading-none mt-0.5">SMK</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold tracking-widest text-brand-400 uppercase">ADMIN PANEL</span>
                        <h2 class="text-base font-extrabold text-white tracking-tight leading-tight">MELATIAREA</h2>
                    </div>
                </div>

                <!-- Nav Menu -->
                <nav class="p-4 space-y-1.5 text-xs font-semibold">
                    <div class="px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-slate-500">Menu Utama</div>

                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white font-bold shadow' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                        <i class="fas fa-gauge-high w-4 text-sm"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.orders.*') ? 'bg-emerald-600 text-white font-bold shadow' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-clipboard-list w-4 text-sm"></i>
                            <span>Kelola Pesanan</span>
                        </span>
                        @php $processingOrders = \App\Models\Order::where('status', 'Sedang Diproses')->count(); @endphp
                        @if($processingOrders > 0)
                            <span class="bg-amber-500 text-slate-950 font-black text-[10px] px-2 py-0.5 rounded-full">
                                {{ $processingOrders }}
                            </span>
                        @endif
                    </a>

                    <div class="px-3 pt-4 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-500">Manajemen Kantin</div>

                    <a href="{{ route('admin.stands.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.stands.*') ? 'bg-emerald-600 text-white font-bold shadow' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                        <i class="fas fa-store w-4 text-sm"></i>
                        <span>8 Stand Kantin</span>
                    </a>

                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.products.*') ? 'bg-emerald-600 text-white font-bold shadow' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                        <i class="fas fa-utensils w-4 text-sm"></i>
                        <span>Kelola Menu & Stok</span>
                    </a>

                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.categories.*') ? 'bg-emerald-600 text-white font-bold shadow' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                        <i class="fas fa-tags w-4 text-sm"></i>
                        <span>Kategori Menu</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'bg-emerald-600 text-white font-bold shadow' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                        <i class="fas fa-users w-4 text-sm"></i>
                        <span>Kelola Pengguna</span>
                    </a>

                    <div class="px-3 pt-4 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-500">Laporan & Analitik</div>

                    <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->routeIs('admin.reports.*') ? 'bg-emerald-600 text-white font-bold shadow' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                        <i class="fas fa-chart-line w-4 text-sm"></i>
                        <span>Laporan Penjualan</span>
                    </a>
                </nav>
            </div>

            <!-- Sidebar Bottom: User & Logout -->
            <div class="p-4 border-t border-slate-800 space-y-3">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition">
                    <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
                    <span>Lihat Website Siswa</span>
                </a>

                <div class="flex items-center justify-between pt-1">
                    <div class="flex items-center gap-2.5 text-xs">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white font-bold flex items-center justify-center">
                            A
                        </div>
                        <div class="truncate max-w-[110px]">
                            <p class="font-bold text-white leading-tight truncate">{{ Auth::user()->name }}</p>
                            <span class="text-[10px] text-slate-400">Administrator</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 transition" title="Logout">
                            <i class="fas fa-arrow-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>

        </aside>

        <!-- MAIN WRAPPER -->
        <div class="flex-grow lg:pl-64 flex flex-col min-h-screen">

            <!-- Topbar Header -->
            <header class="h-20 bg-white border-b border-slate-200/80 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-8 shadow-sm">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-xl">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <div>
                        <h1 class="text-base sm:text-lg font-black text-slate-900 leading-tight">
                            @yield('page_title', 'Dashboard')
                        </h1>
                        <p class="text-[11px] text-slate-400 hidden sm:block">Kantin Sekolah Digital Melatiarea</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xs text-slate-500 hidden md:inline">
                        <i class="far fa-calendar-check text-emerald-600 mr-1"></i> {{ date('l, d F Y') }}
                    </span>
                    <a href="{{ route('home') }}" class="px-3.5 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center gap-1.5">
                        <i class="fas fa-store text-emerald-600"></i> Website
                    </a>
                </div>
            </header>

            <!-- Alerts -->
            <div class="px-4 sm:px-8 mt-4">
                @if(session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between mb-4 shadow-sm">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-emerald-600 text-base"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-semibold flex items-center justify-between mb-4 shadow-sm">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-exclamation-circle text-rose-600 text-base"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('info'))
                    <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 text-xs font-semibold flex items-center justify-between mb-4 shadow-sm">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600 text-base"></i>
                            <span>{{ session('info') }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Content Body -->
            <main class="flex-grow p-4 sm:p-8">
                @yield('content')
            </main>

            <!-- Admin Footer -->
            <footer class="py-4 px-8 border-t border-slate-200 text-xs text-slate-400 text-center sm:text-left flex flex-col sm:flex-row justify-between items-center gap-2">
                <span>&copy; {{ date('Y') }} Kantin Sekolah MELATIAREA. Panel Administrator.</span>
                <span>Framework: Laravel 12 & MySQL</span>
            </footer>

        </div>

    </div>

    <!-- Backdrop for mobile drawer -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 bg-slate-950/60 z-40 lg:hidden backdrop-blur-sm"></div>

    @stack('scripts')
</body>
</html>

