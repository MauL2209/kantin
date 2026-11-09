@extends('layouts.penjaga')

@section('title', 'Dashboard Penjaga - Kantin MELATIAREA')
@section('page_title', 'Dashboard')

@section('content')

<div class="space-y-6">

    @if($noStand ?? false)
        {{-- ===================== NO STAND ALERT ===================== --}}
        <div class="flex items-start gap-4 p-5 rounded-2xl bg-amber-50 border border-amber-200 shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0 text-amber-600">
                <i class="fas fa-triangle-exclamation text-lg"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-amber-900 leading-tight mb-1">Stand Belum Ditugaskan</h3>
                <p class="text-sm text-amber-800 leading-relaxed">
                    Akun Anda belum dihubungkan ke stand kantin manapun.
                    Hubungi Admin untuk mendapatkan akses ke stand Anda.
                </p>
                <p class="mt-2 text-xs text-amber-600 font-semibold">
                    <i class="fas fa-circle-info mr-1"></i>
                    Setelah stand ditugaskan, refresh halaman ini untuk mulai mengelola stand Anda.
                </p>
            </div>
        </div>

    @else
        {{-- ===================== STAT CARDS ===================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            {{-- Pesanan Hari Ini --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between group hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pesanan Hari Ini</span>
                    <div class="w-9 h-9 rounded-xl bg-blue-50 group-hover:bg-blue-100 flex items-center justify-center text-blue-600 transition">
                        <i class="fas fa-bag-shopping text-sm"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-900 leading-none">
                        {{ $todayOrders ?? 0 }}
                    </h3>
                    <p class="text-[11px] text-blue-600 font-semibold mt-1">Pesanan Masuk</p>
                </div>
            </div>

            {{-- Pendapatan Hari Ini --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between group hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pendapatan Hari Ini</span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 group-hover:bg-emerald-100 flex items-center justify-center text-emerald-600 transition">
                        <i class="fas fa-sack-dollar text-sm"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 leading-none font-mono">
                        Rp {{ number_format($todayRevenue ?? 0, 0, ',', '.') }}
                    </h3>
                    <p class="text-[11px] text-emerald-600 font-semibold mt-1">Total Pendapatan</p>
                </div>
            </div>

            {{-- Produk Aktif --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between group hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Produk Aktif</span>
                    <div class="w-9 h-9 rounded-xl bg-teal-50 group-hover:bg-teal-100 flex items-center justify-center text-teal-600 transition">
                        <i class="fas fa-utensils text-sm"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-900 leading-none">
                        {{ $activeProducts ?? 0 }}
                    </h3>
                    <p class="text-[11px] text-teal-600 font-semibold mt-1">Menu Tersedia</p>
                </div>
            </div>

        </div>

        {{-- ===================== QUICK ACTIONS ===================== --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">
                <i class="fas fa-bolt text-amber-400 mr-1.5"></i> Aksi Cepat
            </h2>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('penjaga.orders.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-800 font-semibold text-sm transition">
                    <i class="fas fa-clipboard-list text-amber-500"></i>
                    Lihat Pesanan Masuk
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

                <a href="{{ route('penjaga.products.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-800 font-semibold text-sm transition">
                    <i class="fas fa-utensils text-emerald-500"></i>
                    Kelola Produk
                </a>
            </div>
        </div>

        {{-- ===================== STAND INFO ===================== --}}
        @if($user->stand)
        <div class="bg-gradient-to-r from-emerald-700 to-teal-800 rounded-2xl p-5 text-white shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-emerald-300 uppercase tracking-wider mb-0.5">Stand Anda</p>
                    <h3 class="text-lg font-black leading-tight">{{ $user->stand->name }}</h3>
                    @if($user->stand->location ?? null)
                        <p class="text-sm text-emerald-200 mt-1">
                            <i class="fas fa-location-dot mr-1 text-emerald-400 text-xs"></i>
                            {{ $user->stand->location }}
                        </p>
                    @endif
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-store text-2xl text-emerald-200"></i>
                </div>
            </div>
        </div>
        @endif

    @endif

</div>

@endsection
