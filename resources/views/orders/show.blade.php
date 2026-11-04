@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number . ' - Kantin MELATIAREA')

@section('content')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('orders.index') }}" class="text-xs font-bold text-slate-500 hover:text-emerald-700 flex items-center gap-1.5 transition">
                <i class="fas fa-arrow-left"></i> Kembali ke Pesanan Saya
            </a>
            <span class="text-xs text-slate-400">Dibuat pada: {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 space-y-8">
            
            <!-- Order Header -->
            <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">ID Transaksi Kantin</span>
                    <h1 class="text-2xl font-black text-slate-900 font-mono">{{ $order->order_number }}</h1>
                </div>

                @php $badge = $order->status_badge; @endphp
                <div class="text-right">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-extrabold border {{ $badge['bg'] }}">
                        <span class="w-2 h-2 rounded-full {{ $badge['dot'] }}"></span>
                        {{ $order->status }}
                    </span>
                </div>
            </div>

            <!-- Stepper Progress Indicator -->
            @if($order->status !== 'Dibatalkan')
                @php $step = $order->status_step; @endphp
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6 text-center">Status Proses Pesanan</h3>
                    <div class="relative flex items-center justify-between max-w-xl mx-auto">
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-slate-200 -z-0"></div>
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-emerald-500 transition-all duration-500 -z-0" 
                             style="width: {{ $step == 1 ? '15%' : ($step == 2 ? '48%' : ($step == 3 ? '82%' : '100%')) }};"></div>

                        <!-- 1. Pesanan Dibuat -->
                        <div class="flex flex-col items-center relative z-10 text-center">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold {{ $step >= 1 ? 'bg-emerald-600 text-white shadow' : 'bg-slate-200 text-slate-500' }}">
                                @if($step > 1) <i class="fas fa-check"></i> @else 1 @endif
                            </div>
                            <span class="text-[11px] font-bold mt-2 {{ $step >= 1 ? 'text-emerald-800' : 'text-slate-400' }}">Pesanan Dibuat</span>
                        </div>

                        <!-- 2. Sedang Diproses -->
                        <div class="flex flex-col items-center relative z-10 text-center">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold {{ $step >= 2 ? 'bg-emerald-600 text-white shadow' : 'bg-slate-200 text-slate-500' }}">
                                @if($step > 2) <i class="fas fa-check"></i> @else 2 @endif
                            </div>
                            <span class="text-[11px] font-bold mt-2 {{ $step >= 2 ? 'text-emerald-800' : 'text-slate-400' }}">Sedang Diproses</span>
                        </div>

                        <!-- 3. Siap Diambil -->
                        <div class="flex flex-col items-center relative z-10 text-center">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold {{ $step >= 3 ? 'bg-emerald-600 text-white shadow' : 'bg-slate-200 text-slate-500' }}">
                                @if($step > 3) <i class="fas fa-check"></i> @else 3 @endif
                            </div>
                            <span class="text-[11px] font-bold mt-2 {{ $step >= 3 ? 'text-emerald-800' : 'text-slate-400' }}">Siap Diambil</span>
                        </div>

                        <!-- 4. Selesai -->
                        <div class="flex flex-col items-center relative z-10 text-center">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold {{ $step >= 4 ? 'bg-emerald-600 text-white shadow' : 'bg-slate-200 text-slate-500' }}">
                                @if($step >= 4) <i class="fas fa-check"></i> @else 4 @endif
                            </div>
                            <span class="text-[11px] font-bold mt-2 {{ $step >= 4 ? 'text-emerald-800' : 'text-slate-400' }}">Selesai</span>
                        </div>
                    </div>

                    @if($order->status === 'Siap Diambil')
                        <div class="mt-6 p-3 rounded-xl bg-emerald-100/70 border border-emerald-300 text-emerald-900 text-xs font-bold text-center animate-pulse">
                            <i class="fas fa-bell mr-1"></i> Makananmu sudah siap diambil di stand masing-masing! Silakan menuju kantin.
                        </div>
                    @endif
                </div>
            @endif

            <!-- Customer & Pickup Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 p-5 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                <div>
                    <span class="text-slate-400 block mb-0.5">Nama Pemesan</span>
                    <strong class="text-slate-800 text-sm">{{ $order->customer_name }}</strong>
                    @if($order->customer_nis) <span class="text-slate-400 block text-[11px]">NIS: {{ $order->customer_nis }}</span> @endif
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5">Kelas</span>
                    <strong class="text-slate-800 text-sm">{{ $order->customer_class }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5">Lokasi Pengambilan</span>
                    <strong class="text-slate-800 text-sm text-emerald-800">{{ $order->customer_room }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5">Metode Bayar</span>
                    <strong class="text-slate-800 text-sm uppercase">{{ $order->payment_method }}</strong>
                    <span class="block text-[10px] {{ $order->payment && $order->payment->status === 'paid' ? 'text-emerald-600 font-bold' : 'text-amber-600 font-semibold' }}">
                        ({{ $order->payment && $order->payment->status === 'paid' ? 'Lunas' : 'Menunggu Bayar' }})
                    </span>
                </div>
            </div>

            @if($order->customer_note)
                <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900">
                    <strong>Catatan Pemesan:</strong> "{{ $order->customer_note }}"
                </div>
            @endif

            <!-- Item Table -->
            <div>
                <h3 class="text-sm font-bold text-slate-900 mb-3 uppercase tracking-wider">Rincian Menu</h3>
                <div class="border border-slate-200 rounded-2xl overflow-hidden divide-y divide-slate-100 text-xs">
                    @foreach($order->orderDetails as $detail)
                        <div class="p-4 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $detail->product->image ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=100' }}" alt="" class="w-12 h-12 rounded-xl object-cover">
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">{{ $detail->product->name ?? 'Menu' }}</h4>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-emerald-700 font-semibold text-[11px] bg-emerald-50 px-2 py-0.5 rounded">
                                            {{ $detail->product->stand->name ?? 'Stand Melati' }}
                                        </span>
                                        <span class="text-slate-400">{{ $detail->quantity }} x Rp {{ number_format($detail->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="font-bold text-slate-900 text-sm font-mono">
                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Total Price Summary -->
            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-3">
                <span class="text-xs text-slate-500 font-medium">Total Pembayaran Keseluruhan:</span>
                <span class="text-2xl font-black text-emerald-700 font-mono">{{ $order->formatted_total }}</span>
            </div>

        </div>

    </div>

@endsection

