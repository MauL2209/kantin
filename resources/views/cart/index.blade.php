@extends('layouts.app')

@section('title', 'Keranjang Belanja - Kantin Sekolah MELATIAREA')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-200">
            <div>
                <span class="text-xs font-bold text-emerald-700 tracking-wider uppercase">Sistem Pre-Order</span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Keranjang Belanja</h1>
            </div>
            @if(count($cart) > 0)
                <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan semua menu dari keranjang?')">
                    @csrf
                    <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-semibold flex items-center gap-1.5 transition">
                        <i class="fas fa-trash-can"></i> Kosongkan Keranjang
                    </button>
                </form>
            @endif
        </div>

        @if(count($cart) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: List of Cart Items -->
                <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 space-y-6">
                    <div class="divide-y divide-slate-100">
                        @foreach($cart as $id => $item)
                            <div class="py-5 first:pt-0 last:pb-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                
                                <!-- Product Info -->
                                <div class="flex items-center gap-4">
                                    <div class="w-20 h-20 rounded-2xl bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200/60">
                                        <img src="{{ $item['image'] ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=200' }}" 
                                             alt="{{ $item['name'] }}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="space-y-1">
                                        <span class="inline-block px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 font-bold text-[10px]">
                                            {{ $item['stand_name'] }}
                                        </span>
                                        <h3 class="font-bold text-slate-900 text-sm sm:text-base leading-snug">
                                            {{ $item['name'] }}
                                        </h3>
                                        <p class="text-xs text-slate-500">
                                            Harga Satuan: <strong class="text-slate-800">Rp {{ number_format($item['price'], 0, ',', '.') }}</strong>
                                        </p>
                                    </div>
                                </div>

                                <!-- Quantity Controls & Subtotal -->
                                <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-0 border-slate-50">
                                    
                                    <!-- Quantity Stepper -->
                                    <div class="flex items-center border border-slate-200 rounded-xl bg-slate-50 p-1">
                                        <form action="{{ route('cart.update', $id) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center font-bold text-slate-600 hover:bg-slate-100 transition">
                                                <i class="fas fa-minus text-[10px]"></i>
                                            </button>
                                        </form>

                                        <span class="w-10 text-center font-black text-sm text-slate-800 font-mono">
                                            {{ $item['quantity'] }}
                                        </span>

                                        <form action="{{ route('cart.update', $id) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center font-bold text-slate-600 hover:bg-slate-100 transition">
                                                <i class="fas fa-plus text-[10px]"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="text-right min-w-[100px]">
                                        <span class="text-[10px] text-slate-400 block leading-tight">Subtotal</span>
                                        <span class="text-sm sm:text-base font-black text-emerald-700">
                                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <!-- Delete Button -->
                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition" title="Hapus menu">
                                            <i class="far fa-trash-can text-sm"></i>
                                        </button>
                                    </form>

                                </div>

                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <a href="{{ route('menu.index') }}" class="font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1.5">
                            <i class="fas fa-arrow-left"></i> Tambah Menu Lainnya
                        </a>
                        <span>Jumlah Total Item: <strong>{{ $itemsCount }} porsi</strong></span>
                    </div>
                </div>

                <!-- Right: Order Summary Card -->
                <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 space-y-5 sticky top-28">
                    <h3 class="text-base font-black text-slate-900 border-b border-slate-100 pb-3">
                        Ringkasan Pembayaran
                    </h3>

                    <div class="space-y-3 text-xs sm:text-sm">
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Subtotal Menu ({{ $itemsCount }} item)</span>
                            <span class="font-bold text-slate-800 font-mono">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Biaya Layanan Kantin</span>
                            <span class="font-bold text-emerald-600">GRATIS</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Biaya Kemasan Higienis</span>
                            <span class="font-bold text-emerald-600">GRATIS</span>
                        </div>
                    </div>

                    <div class="border-t border-slate-200 pt-4 flex justify-between items-center">
                        <div>
                            <span class="text-xs text-slate-400 block font-semibold">Total Pembayaran</span>
                            <span class="text-xl font-black text-emerald-700 font-mono">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('checkout.index') }}" class="w-full py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm text-center transition duration-200 shadow-md shadow-emerald-600/30 flex items-center justify-center gap-2">
                            <span>Lanjut ke Checkout</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-[11px] text-slate-500 space-y-1">
                        <p class="font-bold text-slate-700"><i class="fas fa-shield-halved text-emerald-600 mr-1"></i> Pre-Order Sekolah Aman</p>
                        <p>Pesanan langsung masuk ke stand jurusan. Siapkan uang pas atau scan QRIS saat istirahat.</p>
                    </div>
                </div>

            </div>
        @else
            <!-- Empty Cart State -->
            <div class="text-center py-20 bg-white rounded-3xl border border-slate-200/90 shadow-sm max-w-2xl mx-auto p-8">
                <div class="w-20 h-20 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4 text-3xl">
                    <i class="fas fa-bag-shopping"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2">Keranjang Belanja Masih Kosong</h3>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mb-6 leading-relaxed">
                    Kamu belum menambahkan makanan atau minuman ke dalam keranjang. Yuk pilih menu favoritmu dari 8 stand kantin sekarang!
                </p>
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('menu.index') }}" class="px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs sm:text-sm transition shadow-sm">
                        <i class="fas fa-utensils mr-1.5"></i> Lihat Semua Menu
                    </a>
                    <a href="{{ route('stands.index') }}" class="px-6 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm transition">
                        <i class="fas fa-store mr-1.5"></i> Jelajahi 8 Stand
                    </a>
                </div>
            </div>
        @endif

    </div>

@endsection

