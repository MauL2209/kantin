<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Penjaga - Kantin Sekolah MELATIAREA')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:  '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
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

        {{-- ===================== SIDEBAR ===================== --}}
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-emerald-800 to-teal-900 text-emerald-100 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

            {{-- Brand Header --}}
            <div class="h-20 flex items-center gap-3 px-5 border-b border-emerald-700/50 flex-shrink-0">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-white/10 flex-shrink-0 overflow-hidden">
                    <img src="{{ asset('images/logo-smkn20.png') }}" alt="Logo SMK Negeri 20 Jakarta"
                         class="w-full h-full object-contain"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <span class="hidden w-full h-full items-center justify-center text-[9px] font-black text-emerald-300 leading-none text-center">SMK<br>N20</span>
                </div>
                <div>
                    <span class="text-[9px] font-bold tracking-widest text-emerald-300/80 uppercase leading-none">PENJAGA KANTIN</span>
                    <h2 class="text-base font-extrabold text-white tracking-tight leading-tight">MELATIAREA</h2>
                </div>
            </div>

            {{-- Penjaga Identity Card --}}
            <div class="mx-4 mt-4 mb-2 px-3.5 py-3 rounded-2xl bg-white/10 border border-white/10 flex-shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-500 to-teal-400 text-white font-extrabold text-sm flex items-center justify-center flex-shrink-0 shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-white leading-tight truncate">{{ Auth::user()->name }}</p>
                        @if(Auth::user()->stand)
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-300 leading-snug">
                                <i class="fas fa-store text-[9px]"></i>
                                <span class="truncate">{{ Auth::user()->stand->name }}</span>
                            </span>
                        @else
                            <span class="text-[10px] text-amber-300 font-semibold leading-snug">
                                <i class="fas fa-triangle-exclamation mr-0.5 text-[9px]"></i> Stand belum ditugaskan
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-grow overflow-y-auto px-4 py-2 space-y-0.5 text-xs font-semibold">
                <div class="px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-emerald-300/60">Menu Penjaga</div>

                <a href="{{ route('penjaga.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition
                          {{ request()->routeIs('penjaga.dashboard') ? 'bg-white/20 text-white font-bold shadow-sm' : 'text-emerald-200 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-gauge-high w-4 text-sm"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('penjaga.products.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition
                          {{ request()->routeIs('penjaga.products.*') ? 'bg-white/20 text-white font-bold shadow-sm' : 'text-emerald-200 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-utensils w-4 text-sm"></i>
                    <span>Produk Saya</span>
                </a>

                <a href="{{ route('penjaga.orders.index') }}"
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition
                          {{ request()->routeIs('penjaga.orders.*') ? 'bg-white/20 text-white font-bold shadow-sm' : 'text-emerald-200 hover:bg-white/10 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <i class="fas fa-clipboard-list w-4 text-sm"></i>
                        <span>Pesanan</span>
                    </span>
                    @php
                        $pendingCount = 0;
                        if (Auth::user()->stand_id) {
                            $pendingCount = \App\Models\Order::where('status', 'Pesanan Dibuat')
                                ->whereHas('orderDetails', fn($q) =>
                                    $q->whereHas('product', fn($p) =>
                                        $p->where('stand_id', Auth::user()->stand_id)
                                    )
                                )->count();
                        }
                    @endphp
                    @if($pendingCount > 0)
                        <span class="bg-amber-400 text-slate-900 font-black text-[10px] px-2 py-0.5 rounded-full">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('penjaga.reports.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition
                          {{ request()->routeIs('penjaga.reports.*') ? 'bg-white/20 text-white font-bold shadow-sm' : 'text-emerald-200 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-chart-bar w-4 text-sm"></i>
                    <span>Laporan</span>
                </a>

                <div class="px-3 pt-4 pb-2 text-[10px] font-bold uppercase tracking-wider text-emerald-300/60">Lainnya</div>

                <a href="{{ route('profile.show') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition
                          {{ request()->routeIs('profile.*') || request()->routeIs('password.*') ? 'bg-white/20 text-white font-bold shadow-sm' : 'text-emerald-200 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-user-circle w-4 text-sm"></i>
                    <span>Profil Saya</span>
                </a>

                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition text-emerald-200 hover:bg-white/10 hover:text-white">
                    <i class="fas fa-arrow-up-right-from-square w-4 text-sm"></i>
                    <span>Website Kantin</span>
                </a>
            </nav>

            {{-- Sidebar Bottom: Logout --}}
            <div class="px-4 py-4 border-t border-emerald-700/50 flex-shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-white/10 hover:bg-rose-500/80 text-emerald-100 hover:text-white text-xs font-bold transition border border-white/10 hover:border-rose-400/50">
                        <i class="fas fa-arrow-right-from-bracket"></i>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </div>

        </aside>
        {{-- =================== END SIDEBAR =================== --}}

        {{-- =================== MAIN AREA =================== --}}
        <div class="flex-grow lg:pl-64 flex flex-col min-h-screen">

            {{-- Topbar --}}
            <header class="h-16 bg-white border-b border-slate-200/80 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 shadow-sm flex-shrink-0">
                <div class="flex items-center gap-3">
                    {{-- Mobile hamburger --}}
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-xl transition">
                        <i class="fas fa-bars text-lg" x-show="!sidebarOpen"></i>
                        <i class="fas fa-xmark text-lg" x-show="sidebarOpen" x-cloak></i>
                    </button>
                    <div>
                        <h1 class="text-base font-black text-slate-900 leading-tight">
                            @yield('page_title', 'Panel Penjaga')
                        </h1>
                        <p class="text-[11px] text-slate-400 hidden sm:block">
                            @if(Auth::user()->stand)
                                Stand: {{ Auth::user()->stand->name }}
                            @else
                                Kantin Sekolah MELATIAREA
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xs text-slate-400 hidden md:inline">
                        <i class="far fa-calendar-check text-brand-600 mr-1"></i>
                        {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                    </span>
                    {{-- Inline logout button for quick access --}}
                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 hover:border-rose-300 hover:bg-rose-50 text-slate-500 hover:text-rose-600 text-xs font-bold transition">
                            <i class="fas fa-arrow-right-from-bracket text-[11px]"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            {{-- Flash Alerts --}}
            <div class="px-4 sm:px-6 mt-4 space-y-3">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show"
                         class="flex items-center justify-between p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-xs"></i>
                            </div>
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-emerald-600 hover:text-emerald-800 ml-4">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show"
                         class="flex items-center justify-between p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full bg-rose-500 text-white flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-exclamation text-xs"></i>
                            </div>
                            <p class="text-sm font-medium">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="text-rose-600 hover:text-rose-800 ml-4">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                @endif

                @if(session('warning'))
                    <div x-data="{ show: true }" x-show="show"
                         class="flex items-center justify-between p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full bg-amber-500 text-white flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-triangle-exclamation text-xs"></i>
                            </div>
                            <p class="text-sm font-medium">{{ session('warning') }}</p>
                        </div>
                        <button @click="show = false" class="text-amber-600 hover:text-amber-800 ml-4">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                @endif

                @if(session('info'))
                    <div x-data="{ show: true }" x-show="show"
                         class="flex items-center justify-between p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full bg-blue-500 text-white flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-info text-xs"></i>
                            </div>
                            <p class="text-sm font-medium">{{ session('info') }}</p>
                        </div>
                        <button @click="show = false" class="text-blue-600 hover:text-blue-800 ml-4">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                @endif
            </div>

            {{-- Page Content --}}
            <main class="flex-grow p-4 sm:p-6">
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="py-4 px-6 border-t border-slate-200 text-xs text-slate-400 flex flex-col sm:flex-row justify-between items-center gap-2">
                <span>&copy; {{ date('Y') }} Kantin Sekolah MELATIAREA. Panel Penjaga Kantin.</span>
                <span class="text-slate-300">Laravel 12 &middot; SMK Negeri 20 Jakarta</span>
            </footer>

        </div>
        {{-- ================= END MAIN AREA ================= --}}

    </div>

    {{-- Mobile sidebar backdrop --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
         class="fixed inset-0 bg-slate-950/60 z-40 lg:hidden backdrop-blur-sm"></div>

    @stack('scripts')
</body>
</html>
