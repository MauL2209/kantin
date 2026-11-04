@extends('layouts.app')

@section('title', 'Pesanan Saya - Kantin Sekolah MELATIAREA')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-4 border-b border-slate-200">
            <div>
                <span class="text-xs font-bold text-emerald-700 tracking-wider uppercase">Pelacakan Pesanan</span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Pesanan Saya</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Pantau status penyiapan makanan dan minumanmu secara real-time.</p>
            </div>

            <!-- Search by Order Number / Phone if Guest -->
            <form action="{{ route('orders.index') }}" method="GET" class="flex items-center gap-2 max-w-sm w-full">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari No. Pesanan / No. HP..." 
                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm">
                    Cari
                </button>
            </form>
        </div>

        <!-- Status Filter Tabs -->
        <div class="flex flex-wrap items-center gap-2 mb-8">
            <a href="{{ route('orders.index') }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ !request('status') ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Semua Pesanan
            </a>
            <a href="{{ route('orders.index', ['status' => 'Pesanan Dibuat']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ request('status') === 'Pesanan Dibuat' ? 'bg-amber-500 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Pesanan Dibuat
            </a>
            <a href="{{ route('orders.index', ['status' => 'Sedang Diproses']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ request('status') === 'Sedang Diproses' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Sedang Diproses
            </a>
            <a href="{{ route('orders.index', ['status' => 'Siap Diambil']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ request('status') === 'Siap Diambil' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Siap Diambil
            </a>
            <a href="{{ route('orders.index', ['status' => 'Selesai']) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ request('status') === 'Selesai' ? 'bg-slate-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Selesai
            </a>
        </div>

        @if($orders->count() > 0)
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 hover:shadow-md transition">
                        
                        <!-- Top Header -->
                        <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="font-mono font-black text-sm text-slate-900">{{ $order->order_number }}</span>
                                <span class="text-xs text-slate-400">• {{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                            </div>

                            @php $badge = $order->status_badge; @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold border {{ $badge['bg'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $badge['dot'] }}"></span>
                                {{ $order->status }}
                            </span>
                        </div>

                        <!-- Progress Indicator Stepper (1 to 4) -->
                        @if($order->status !== 'Dibatalkan')
                            @php $step = $order->status_step; @endphp
                            <div class="py-6 px-2 sm:px-6">
                                <div class="relative flex items-center justify-between">
                                    <!-- Progress Line -->
                                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-slate-200 -z-0"></div>
                                    <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-emerald-500 transition-all duration-500 -z-0" 
                                         style="width: {{ $step == 1 ? '15%' : ($step == 2 ? '48%' : ($step == 3 ? '82%' : '100%')) }};"></div>

                                    <!-- Step 1 -->
                                    <div class="flex flex-col items-center relative z-10">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition shadow-sm {{ $step >= 1 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                                            @if($step > 1) <i class="fas fa-check"></i> @else 1 @endif
                                        </div>
                                        <span class="text-[10px] sm:text-xs font-bold mt-1.5 text-center {{ $step >= 1 ? 'text-emerald-800' : 'text-slate-400' }}">
                                            Pesanan Dibuat
                                        </span>
                                    </div>

                                    <!-- Step 2 -->
                                    <div class="flex flex-col items-center relative z-10">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition shadow-sm {{ $step >= 2 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                                            @if($step > 2) <i class="fas fa-check"></i> @else 2 @endif
                                        </div>
                                        <span class="text-[10px] sm:text-xs font-bold mt-1.5 text-center {{ $step >= 2 ? 'text-emerald-800' : 'text-slate-400' }}">
                                            Sedang Diproses
                                        </span>
                                    </div>

                                    <!-- Step 3 -->
                                    <div class="flex flex-col items-center relative z-10">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition shadow-sm {{ $step >= 3 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                                            @if($step > 3) <i class="fas fa-check"></i> @else 3 @endif
                                        </div>
                                        <span class="text-[10px] sm:text-xs font-bold mt-1.5 text-center {{ $step >= 3 ? 'text-emerald-800' : 'text-slate-400' }}">
                                            Siap Diambil
                                        </span>
                                    </div>

                                    <!-- Step 4 -->
                                    <div class="flex flex-col items-center relative z-10">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition shadow-sm {{ $step >= 4 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                                            @if($step >= 4) <i class="fas fa-check"></i> @else 4 @endif
                                        </div>
                                        <span class="text-[10px] sm:text-xs font-bold mt-1.5 text-center {{ $step >= 4 ? 'text-emerald-800' : 'text-slate-400' }}">
                                            Selesai
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="my-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center gap-2">
                                <i class="fas fa-circle-xmark text-sm"></i>
                                <span>Pesanan ini telah dibatalkan oleh pihak pengelola stand/admin.</span>
                            </div>
                        @endif

                        <!-- Items Summary -->
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 my-2 space-y-2 text-xs">
                            @foreach($order->orderDetails as $detail)
                                <div class="flex justify-between items-center text-slate-700">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-900">{{ $detail->quantity }}x</span>
                                        <span>{{ $detail->product->name ?? 'Menu' }}</span>
                                        <span class="text-[10px] text-emerald-800 bg-emerald-100/60 px-1.5 py-0.5 rounded font-semibold">
                                            {{ $detail->product->stand->name ?? 'Stand Kantin' }}
                                        </span>
                                    </div>
                                    <span class="font-mono font-bold text-slate-800">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Footer -->
                        <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="text-xs text-slate-500 space-y-0.5">
                                <p>Pemesan: <strong>{{ $order->customer_name }}</strong> ({{ $order->customer_class }})</p>
                                <p>Lokasi Ambil: <strong>{{ $order->customer_room }}</strong> • Bayar: <strong class="uppercase text-emerald-700">{{ $order->payment_method }}</strong></p>
                            </div>
                            <div class="flex items-center justify-between sm:justify-end gap-4">
                                <div class="text-right">
                                    <span class="text-[10px] text-slate-400 block leading-tight">Total Tagihan</span>
                                    <span class="text-base font-black text-slate-900 font-mono">{{ $order->formatted_total }}</span>
                                </div>
                                <a href="{{ route('orders.show', $order->id) }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white font-bold text-xs transition shadow-sm">
                                    Detail Pesanan
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-3xl border border-slate-200/90 shadow-sm max-w-xl mx-auto p-8">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                    <i class="fas fa-receipt"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Tidak ada riwayat pesanan ditemukan</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    @guest
                        Silakan login untuk melihat riwayat pesanan akunmu atau masukkan nomor pesanan pada kotak pencarian di atas.
                    @else
                        Kamu belum pernah memesan di kantin. Yuk mulai pesan makanan favoritmu sekarang!
                    @endguest
                </p>
                <div class="mt-5">
                    <a href="{{ route('menu.index') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm inline-block">
                        Lihat Menu Kantin
                    </a>
                </div>
            </div>
        @endif

    </div>

@endsection

