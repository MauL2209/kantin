@extends('layouts.penjaga')

@section('title', 'Detail Pesanan #' . $order->order_number . ' - Panel Penjaga')
@section('page_title', 'Detail Pesanan')

@section('content')

    @php
        $badge = $order->status_badge;

        // Valid next status for this penjaga
        $transitions = [
            'Pesanan Dibuat'  => 'Sedang Diproses',
            'Sedang Diproses' => 'Siap Diambil',
            'Siap Diambil'    => 'Selesai',
        ];
        $nextStatus = $transitions[$order->status] ?? null;

        // Subtotal scoped to this stand
        $standSubtotal = $standOrderDetails->sum('subtotal');
    @endphp

    <div class="max-w-3xl mx-auto space-y-6">

        {{-- =================== BREADCRUMB =================== --}}
        <nav class="flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('penjaga.orders.index') }}"
               class="hover:text-emerald-600 transition font-semibold flex items-center gap-1.5">
                <i class="fas fa-arrow-left text-[10px]"></i>
                Daftar Pesanan
            </a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-semibold">#{{ $order->order_number }}</span>
        </nav>

        {{-- =================== ORDER HEADER =================== --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-receipt text-emerald-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">No. Pesanan</p>
                        <h2 class="text-xl font-black text-slate-900 font-mono tracking-tight">
                            #{{ $order->order_number }}
                        </h2>
                        <p class="text-[11px] text-slate-400 mt-0.5">
                            <i class="far fa-clock mr-1"></i>
                            {{ $order->created_at->locale('id')->translatedFormat('l, d F Y — H:i') }} WIB
                        </p>
                    </div>
                </div>

                {{-- Status Badge --}}
                <div class="flex items-center gap-3 flex-shrink-0">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full border font-bold text-sm whitespace-nowrap {{ $badge['bg'] }}">
                        <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $badge['dot'] }}"></span>
                        {{ $order->status }}
                    </span>
                </div>

            </div>

            {{-- Progress Steps --}}
            @php
                $steps = ['Pesanan Dibuat', 'Sedang Diproses', 'Siap Diambil', 'Selesai'];
                $currentStep = $order->status_step;
            @endphp
            @if($order->status !== 'Dibatalkan')
                <div class="mt-6 flex items-center gap-0 overflow-x-auto pb-1">
                    @foreach($steps as $i => $step)
                        @php
                            $stepNum  = $i + 1;
                            $isDone   = $currentStep > $stepNum;
                            $isActive = $currentStep === $stepNum;
                        @endphp
                        <div class="flex items-center flex-1 min-w-0">
                            {{-- Step circle --}}
                            <div class="flex flex-col items-center gap-1 flex-shrink-0">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-xs border-2 transition
                                            {{ $isDone   ? 'bg-emerald-500 border-emerald-500 text-white'  : '' }}
                                            {{ $isActive ? 'bg-white border-emerald-500 text-emerald-600' : '' }}
                                            {{ !$isDone && !$isActive ? 'bg-slate-100 border-slate-200 text-slate-400' : '' }}">
                                    @if($isDone)
                                        <i class="fas fa-check text-[10px]"></i>
                                    @else
                                        {{ $stepNum }}
                                    @endif
                                </div>
                                <span class="text-[9px] font-bold text-center leading-tight whitespace-nowrap
                                             {{ $isActive ? 'text-emerald-600' : ($isDone ? 'text-emerald-500' : 'text-slate-400') }}">
                                    {{ $step }}
                                </span>
                            </div>
                            {{-- Connector line (not after last step) --}}
                            @if(!$loop->last)
                                <div class="flex-1 h-0.5 mx-1 mt-[-18px]
                                            {{ $isDone ? 'bg-emerald-400' : 'bg-slate-200' }}"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- =================== BUYER INFO =================== --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
                <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-blue-500 text-xs"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Informasi Pembeli</h3>
            </div>

            <div class="px-6 py-5">
                <div class="flex items-center gap-4">
                    {{-- Avatar initial --}}
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white font-black text-base flex items-center justify-center flex-shrink-0 shadow-sm">
                        {{ strtoupper(substr($order->customer_name ?? ($order->user->name ?? '?'), 0, 1)) }}
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-1.5 flex-1 min-w-0">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama</p>
                            <p class="text-sm font-bold text-slate-900 truncate">
                                {{ $order->customer_name ?? ($order->user->name ?? '-') }}
                            </p>
                        </div>
                        @if($order->user)
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Email</p>
                                <p class="text-sm text-slate-700 truncate">{{ $order->user->email }}</p>
                            </div>
                        @endif
                        @if($order->customer_nis)
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">NIS</p>
                                <p class="text-sm text-slate-700">{{ $order->customer_nis }}</p>
                            </div>
                        @endif
                        @if($order->customer_class || $order->customer_room)
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kelas</p>
                                <p class="text-sm text-slate-700">
                                    {{ trim(($order->customer_class ?? '') . ' ' . ($order->customer_room ?? '')) ?: '-' }}
                                </p>
                            </div>
                        @endif
                        @if($order->customer_phone)
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Telepon</p>
                                <p class="text-sm text-slate-700">{{ $order->customer_phone }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pembayaran</p>
                            <p class="text-sm font-semibold text-slate-700">{{ $order->payment_method ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                @if($order->customer_note)
                    <div class="mt-4 p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-sm text-amber-800">
                        <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider mb-1">
                            <i class="fas fa-note-sticky mr-1"></i>Catatan Pembeli
                        </p>
                        {{ $order->customer_note }}
                    </div>
                @endif
            </div>
        </div>

        {{-- =================== PRODUCTS FROM STAND =================== --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-utensils text-emerald-500 text-xs"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Produk dari Stand Anda</h3>
                    <p class="text-[11px] text-slate-400">
                        {{ $standOrderDetails->count() }} item dari stand Anda dalam pesanan ini
                    </p>
                </div>
            </div>

            @if($standOrderDetails->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-center px-6">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center mb-3">
                        <i class="fas fa-box-open text-xl text-slate-300"></i>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Tidak ada produk dari stand Anda dalam pesanan ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-100 bg-slate-50/70">
                                <th class="py-3.5 px-6">Produk</th>
                                <th class="py-3.5 px-4 text-center">Qty</th>
                                <th class="py-3.5 px-4 text-right">Harga Satuan</th>
                                <th class="py-3.5 px-6 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($standOrderDetails as $detail)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            @if($detail->product && $detail->product->image)
                                                <img src="{{ asset('storage/' . $detail->product->image) }}"
                                                     alt="{{ $detail->product->name }}"
                                                     class="w-10 h-10 object-cover rounded-xl border border-slate-200 flex-shrink-0">
                                            @else
                                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-utensils text-slate-400 text-sm"></i>
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <p class="font-bold text-slate-900 truncate max-w-[180px]">
                                                    {{ $detail->product->name ?? $detail->product_name ?? '-' }}
                                                </p>
                                                @if($detail->product && $detail->product->category)
                                                    <p class="text-[10px] text-slate-400">
                                                        {{ $detail->product->category->name }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-100 text-slate-700 font-extrabold text-xs">
                                            {{ $detail->quantity }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-right font-mono text-slate-600 whitespace-nowrap">
                                        Rp {{ number_format($detail->price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6 text-right font-mono font-bold text-slate-900 whitespace-nowrap">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-slate-200 bg-slate-50/70">
                                <td colspan="3" class="py-4 px-6 text-right text-xs font-bold text-slate-600 uppercase tracking-wider">
                                    Total dari Stand Anda
                                </td>
                                <td class="py-4 px-6 text-right font-mono font-black text-emerald-700 text-sm whitespace-nowrap">
                                    Rp {{ number_format($standSubtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>

        {{-- =================== STATUS UPDATE =================== --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-8 h-8 rounded-xl bg-teal-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-arrow-right-arrow-left text-teal-500 text-xs"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Update Status Pesanan</h3>
            </div>

            @if($nextStatus)
                {{-- Status transition form --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="text-sm text-slate-600">
                        <p>
                            Ubah status dari
                            <span class="font-bold text-slate-800">"{{ $order->status }}"</span>
                            menjadi
                            <span class="font-bold text-emerald-700">"{{ $nextStatus }}"</span>?
                        </p>
                        <p class="text-[11px] text-slate-400 mt-1">
                            <i class="fas fa-circle-info mr-1"></i>
                            Perubahan status tidak dapat dibatalkan oleh Penjaga.
                        </p>
                    </div>

                    <form method="POST"
                          action="{{ route('penjaga.orders.status', $order->id) }}"
                          onsubmit="return confirm('Yakin ingin mengubah status pesanan #{{ $order->order_number }} menjadi \'{{ $nextStatus }}\'?')">
                        @csrf
                        <input type="hidden" name="status" value="{{ $nextStatus }}">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl font-bold text-sm shadow-sm transition whitespace-nowrap
                                       @if($nextStatus === 'Sedang Diproses')
                                           bg-blue-600 hover:bg-blue-700 text-white
                                       @elseif($nextStatus === 'Siap Diambil')
                                           bg-purple-600 hover:bg-purple-700 text-white
                                       @else
                                           bg-emerald-600 hover:bg-emerald-700 text-white
                                       @endif">
                            @if($nextStatus === 'Sedang Diproses')
                                <i class="fas fa-fire-burner text-sm"></i>
                            @elseif($nextStatus === 'Siap Diambil')
                                <i class="fas fa-bell text-sm"></i>
                            @else
                                <i class="fas fa-circle-check text-sm"></i>
                            @endif
                            Tandai: {{ $nextStatus }}
                        </button>
                    </form>
                </div>

            @else
                {{-- Final status or cancelled --}}
                <div class="flex items-center gap-3 p-4 rounded-2xl
                            {{ $order->status === 'Selesai' ? 'bg-emerald-50 border border-emerald-200' : 'bg-slate-50 border border-slate-200' }}">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0
                                {{ $order->status === 'Selesai' ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 text-slate-500' }}">
                        <i class="fas {{ $order->status === 'Selesai' ? 'fa-circle-check' : 'fa-ban' }} text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold {{ $order->status === 'Selesai' ? 'text-emerald-800' : 'text-slate-700' }}">
                            @if($order->status === 'Selesai')
                                Pesanan ini sudah selesai.
                            @else
                                Tidak ada tindakan yang tersedia untuk status "{{ $order->status }}".
                            @endif
                        </p>
                        @if($order->status === 'Selesai')
                            <p class="text-[11px] text-emerald-600 mt-0.5">Semua proses telah selesai dilaksanakan.</p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Admin-only note for cancellation --}}
            <div class="mt-4 flex items-start gap-2.5 p-3.5 rounded-xl bg-rose-50 border border-rose-100">
                <i class="fas fa-shield-halved text-rose-400 text-sm mt-0.5 flex-shrink-0"></i>
                <p class="text-[11px] text-rose-600 leading-relaxed">
                    <span class="font-bold">Pembatalan pesanan</span> hanya dapat dilakukan oleh Admin.
                    Jika ada kendala, silakan hubungi Admin untuk membatalkan pesanan ini.
                </p>
            </div>
        </div>

        {{-- =================== BACK BUTTON =================== --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('penjaga.orders.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-xs font-bold transition">
                <i class="fas fa-arrow-left text-[10px]"></i>
                Kembali ke Daftar Pesanan
            </a>
            <a href="{{ route('penjaga.orders.index', ['status' => $order->status]) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-500 text-xs font-bold transition">
                <i class="fas fa-filter text-[10px]"></i>
                Lihat Pesanan "{{ $order->status }}"
            </a>
        </div>

    </div>

@endsection
