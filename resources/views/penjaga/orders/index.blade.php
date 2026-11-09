@extends('layouts.penjaga')

@section('title', 'Pesanan Masuk - Panel Penjaga')
@section('page_title', 'Pesanan Masuk')

@section('content')

    <div class="space-y-6">

        {{-- Header --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Daftar Pesanan Stand Anda</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola dan pantau status pesanan yang masuk ke stand Anda.</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-xs font-semibold text-slate-500">
                        Total:
                        <span class="text-emerald-600 font-extrabold">{{ $statusCounts['all'] }}</span>
                        pesanan
                    </span>
                </div>
            </div>
        </div>

        {{-- Status Filter Tabs --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="flex overflow-x-auto border-b border-slate-100 scrollbar-thin">
                @php
                    $currentStatus = request('status', '');
                    $tabs = [
                        ''                => ['label' => 'Semua',           'count' => $statusCounts['all'],             'color' => 'slate'],
                        'Pesanan Dibuat'  => ['label' => 'Pesanan Dibuat',  'count' => $statusCounts['Pesanan Dibuat'],  'color' => 'amber'],
                        'Sedang Diproses' => ['label' => 'Sedang Diproses', 'count' => $statusCounts['Sedang Diproses'], 'color' => 'blue'],
                        'Siap Diambil'    => ['label' => 'Siap Diambil',    'count' => $statusCounts['Siap Diambil'],    'color' => 'purple'],
                        'Selesai'         => ['label' => 'Selesai',         'count' => $statusCounts['Selesai'],         'color' => 'emerald'],
                    ];
                    $badgeColors = [
                        'slate'   => 'bg-slate-100 text-slate-700',
                        'amber'   => 'bg-amber-100 text-amber-700',
                        'blue'    => 'bg-blue-100 text-blue-700',
                        'purple'  => 'bg-purple-100 text-purple-700',
                        'emerald' => 'bg-emerald-100 text-emerald-700',
                    ];
                @endphp

                @foreach($tabs as $statusKey => $tab)
                    @php
                        $isActive = $currentStatus === $statusKey;
                        $href = $statusKey === ''
                            ? route('penjaga.orders.index')
                            : route('penjaga.orders.index', ['status' => $statusKey]);
                    @endphp
                    <a href="{{ $href }}"
                       class="flex items-center gap-2 px-5 py-4 text-xs font-bold whitespace-nowrap transition border-b-2 flex-shrink-0
                              {{ $isActive
                                  ? 'border-emerald-500 text-emerald-700 bg-emerald-50/50'
                                  : 'border-transparent text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                        {{ $tab['label'] }}
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold
                                     {{ $isActive ? 'bg-emerald-500 text-white' : $badgeColors[$tab['color']] }}">
                            {{ $tab['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            {{-- Orders Table --}}
            @if($orders->isEmpty())
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center py-20 text-center px-6">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center mb-4">
                        <i class="fas fa-clipboard-list text-2xl text-emerald-400"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-700 mb-1">
                        @if($currentStatus)
                            Tidak Ada Pesanan "{{ $currentStatus }}"
                        @else
                            Belum Ada Pesanan
                        @endif
                    </h3>
                    <p class="text-sm text-slate-400 max-w-xs">
                        @if($currentStatus)
                            Belum ada pesanan dengan status ini untuk stand Anda saat ini.
                        @else
                            Stand Anda belum menerima pesanan. Pesanan yang masuk akan ditampilkan di sini.
                        @endif
                    </p>
                    @if($currentStatus)
                        <a href="{{ route('penjaga.orders.index') }}"
                           class="mt-4 inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition">
                            <i class="fas fa-arrow-left text-[10px]"></i>
                            Lihat Semua Pesanan
                        </a>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-100 bg-slate-50/70">
                                <th class="py-3.5 px-4">No. Pesanan</th>
                                <th class="py-3.5 px-4">Nama Pembeli</th>
                                <th class="py-3.5 px-4 text-center">Items</th>
                                <th class="py-3.5 px-4 text-right">Total</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-4 text-center">Waktu</th>
                                <th class="py-3.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($orders as $order)
                                @php
                                    $badge = $order->status_badge;
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition">

                                    {{-- No. Pesanan --}}
                                    <td class="py-4 px-4">
                                        <span class="font-mono font-bold text-slate-900 text-xs tracking-tight">
                                            #{{ $order->order_number }}
                                        </span>
                                    </td>

                                    {{-- Nama Pembeli --}}
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

                                    {{-- Status Badge --}}
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
