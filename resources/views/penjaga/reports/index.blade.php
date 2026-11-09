@extends('layouts.penjaga')

@section('title', 'Laporan Penjualan - Panel Penjaga')
@section('page_title', 'Laporan Penjualan')

@section('content')

    @php
        $periodLabels = [
            'today'      => 'Hari Ini',
            'this_week'  => 'Minggu Ini',
            'this_month' => 'Bulan Ini',
        ];
        $periodLabel = $periodLabels[$period] ?? 'Hari Ini';
    @endphp

    <div class="space-y-6">

        {{-- =================== HEADER + PERIOD FILTER =================== --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Laporan Penjualan Stand Anda</h2>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Data penjualan dari
                        <span class="font-semibold text-slate-500">
                            {{ $startDate->locale('id')->translatedFormat('d F Y') }}
                        </span>
                        @if($startDate->format('Ymd') !== $endDate->format('Ymd'))
                            — <span class="font-semibold text-slate-500">{{ $endDate->locale('id')->translatedFormat('d F Y') }}</span>
                        @endif
                    </p>
                </div>

                {{-- Period Filter Tabs --}}
                <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-2xl flex-shrink-0">
                    @foreach($periodLabels as $key => $label)
                        <a href="{{ route('penjaga.reports.index', ['period' => $key]) }}"
                           class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition
                                  {{ $period === $key
                                      ? 'bg-white text-emerald-700 shadow-sm border border-slate-200/80'
                                      : 'text-slate-500 hover:text-slate-700 hover:bg-white/60' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- =================== STAT CARDS =================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- Total Pendapatan --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between group hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pendapatan</span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 group-hover:bg-emerald-100 flex items-center justify-center text-emerald-600 transition">
                        <i class="fas fa-sack-dollar text-sm"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 leading-none font-mono">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </h3>
                    <p class="text-[11px] text-emerald-600 font-semibold mt-1">
                        Periode: {{ $periodLabel }}
                    </p>
                </div>
            </div>

            {{-- Jumlah Pesanan --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between group hover:shadow-md transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jumlah Pesanan</span>
                    <div class="w-9 h-9 rounded-xl bg-blue-50 group-hover:bg-blue-100 flex items-center justify-center text-blue-600 transition">
                        <i class="fas fa-bag-shopping text-sm"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-900 leading-none">
                        {{ $totalOrders }}
                    </h3>
                    <p class="text-[11px] text-blue-600 font-semibold mt-1">
                        Pesanan masuk (tidak termasuk dibatalkan)
                    </p>
                </div>
            </div>

        </div>

        {{-- =================== TOP 5 PRODUCTS =================== --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
                <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-trophy text-amber-500 text-xs"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Produk Terlaris</h3>
                    <p class="text-[11px] text-slate-400">Top 5 berdasarkan jumlah terjual — {{ $periodLabel }}</p>
                </div>
            </div>

            @if($topProducts->isEmpty())
                <div class="flex flex-col items-center justify-center py-14 text-center px-6">
                    <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center mb-3">
                        <i class="fas fa-chart-bar text-2xl text-slate-300"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-600 mb-1">Belum Ada Data Penjualan</p>
                    <p class="text-xs text-slate-400 max-w-xs">
                        Belum ada produk yang terjual pada periode ini. Data akan muncul setelah ada pesanan yang selesai diproses.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-100 bg-slate-50/70">
                                <th class="py-3.5 px-6">Peringkat</th>
                                <th class="py-3.5 px-4">Nama Produk</th>
                                <th class="py-3.5 px-4 text-center">Qty Terjual</th>
                                <th class="py-3.5 px-6 text-right">Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($topProducts as $index => $item)
                                <tr class="hover:bg-slate-50/60 transition">
                                    {{-- Rank --}}
                                    <td class="py-4 px-6">
                                        @if($index === 0)
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-white font-black text-[11px] shadow-sm">
                                                1
                                            </span>
                                        @elseif($index === 1)
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-400 text-white font-black text-[11px]">
                                                2
                                            </span>
                                        @elseif($index === 2)
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-orange-400 text-white font-black text-[11px]">
                                                3
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-100 text-slate-500 font-bold text-[11px]">
                                                {{ $index + 1 }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Nama Produk --}}
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                     alt="{{ $item->product->name }}"
                                                     class="w-9 h-9 object-cover rounded-xl border border-slate-200 flex-shrink-0">
                                            @else
                                                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-utensils text-emerald-400 text-xs"></i>
                                                </div>
                                            @endif
                                            <span class="font-semibold text-slate-900 truncate max-w-[200px]">
                                                {{ $item->product->name ?? '-' }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Qty Terjual --}}
                                    <td class="py-4 px-4 text-center">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-extrabold text-xs">
                                            {{ number_format($item->total_qty) }}
                                        </span>
                                    </td>

                                    {{-- Total Pendapatan --}}
                                    <td class="py-4 px-6 text-right font-mono font-bold text-slate-900 whitespace-nowrap">
                                        Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- =================== RECENT ORDERS TABLE =================== --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between gap-3 px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-receipt text-emerald-500 text-xs"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Riwayat Pesanan</h3>
                        <p class="text-[11px] text-slate-400">
                            {{ $orders->total() }} pesanan pada periode {{ $periodLabel }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('penjaga.orders.index') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 hover:text-emerald-800 transition flex-shrink-0">
                    Lihat Semua
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            @if($orders->isEmpty())
                <div class="flex flex-col items-center justify-center py-14 text-center px-6">
                    <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center mb-3">
                        <i class="fas fa-clipboard-list text-2xl text-slate-300"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-600 mb-1">Belum Ada Pesanan</p>
                    <p class="text-xs text-slate-400">Belum ada pesanan yang masuk pada periode ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-100 bg-slate-50/70">
                                <th class="py-3.5 px-4">No. Pesanan</th>
                                <th class="py-3.5 px-4">Pembeli</th>
                                <th class="py-3.5 px-4 text-center">Items</th>
                                <th class="py-3.5 px-4 text-right">Total</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-4 text-center">Waktu</th>
                                <th class="py-3.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($orders as $order)
                                @php $badge = $order->status_badge; @endphp
                                <tr class="hover:bg-slate-50/60 transition">

                                    {{-- No. Pesanan --}}
                                    <td class="py-4 px-4">
                                        <span class="font-mono font-bold text-slate-900 text-xs tracking-tight">
                                            #{{ $order->order_number }}
                                        </span>
                                    </td>

                                    {{-- Pembeli --}}
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-emerald-500 to-teal-400 text-white font-extrabold text-[10px] flex items-center justify-center flex-shrink-0">
                                                {{ strtoupper(substr($order->customer_name ?? ($order->user->name ?? '?'), 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-semibold text-slate-900 truncate max-w-[140px]">
                                                    {{ $order->customer_name ?? ($order->user->name ?? '-') }}
                                                </p>
                                                @if($order->user)
                                                    <p class="text-[10px] text-slate-400 truncate max-w-[140px]">
                                                        {{ $order->user->email }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Items --}}
                                    <td class="py-4 px-4 text-center">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-100 text-slate-700 font-extrabold text-xs">
                                            {{ $order->orderDetails->count() }}
                                        </span>
                                    </td>

                                    {{-- Total --}}
                                    <td class="py-4 px-4 text-right font-mono font-bold text-slate-900 whitespace-nowrap">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>

                                    {{-- Status --}}
                                    <td class="py-4 px-4 text-center">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border font-bold text-[10px] whitespace-nowrap {{ $badge['bg'] }}">
                                            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 {{ $badge['dot'] }}"></span>
                                            {{ $order->status }}
                                        </span>
                                    </td>

                                    {{-- Waktu --}}
                                    <td class="py-4 px-4 text-center text-slate-400 whitespace-nowrap">
                                        <div class="flex flex-col items-center gap-0.5">
                                            <span class="font-semibold text-slate-600">
                                                {{ $order->created_at->format('d M Y') }}
                                            </span>
                                            <span class="text-[10px]">
                                                {{ $order->created_at->format('H:i') }} WIB
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="py-4 px-4 text-center">
                                        <a href="{{ route('penjaga.orders.show', $order->id) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-[11px] rounded-lg transition"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye text-[10px]"></i>
                                            Detail
                                        </a>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($orders->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $orders->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>

@endsection
