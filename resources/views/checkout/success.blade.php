@extends('layouts.app')

@section('title', 'Pesanan Berhasil Dibuat #' . $order->order_number . ' - Kantin MELATIAREA')

@section('content')

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl overflow-hidden text-center p-6 sm:p-10">

            <!-- Success Icon -->
            <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-4 text-3xl shadow-sm">
                <i class="fas fa-check"></i>
            </div>

            <span class="inline-block px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 font-bold text-xs uppercase tracking-wider mb-2">
                Pemesanan Berhasil
            </span>

            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Terima Kasih, Pesananmu Telah Diterima!
            </h1>

            <p class="text-xs sm:text-sm text-slate-500 mt-2 max-w-md mx-auto">
                Pesanan telah diteruskan ke stand jurusan terkait dan akan segera diproses sebelum jam istirahat.
            </p>

            <!-- Order Ticket Card -->
            <div class="bg-slate-50 rounded-2xl border border-slate-200/80 p-5 sm:p-6 text-left my-8 space-y-4">

                <div class="flex flex-wrap items-center justify-between gap-2 pb-4 border-b border-slate-200">
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Nomor Pesanan</span>
                        <span class="text-lg font-black text-slate-900 font-mono">{{ $order->order_number }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Status Pesanan</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-800 font-extrabold text-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            {{ $order->status }}
                        </span>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[11px]">Nama Pemesan</span>
                        <strong class="text-slate-800">{{ $order->customer_name }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Kelas</span>
                        <strong class="text-slate-800">{{ $order->customer_class }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Lokasi Ambil</span>
                        <strong class="text-slate-800">{{ $order->customer_room }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Metode Bayar</span>
                        <strong class="text-slate-800 uppercase">{{ $order->payment_method }}</strong>
                    </div>
                </div>

                <!-- Item Details -->
                <div class="pt-3 border-t border-slate-200">
                    <span class="text-xs font-bold text-slate-700 block mb-2">Detail Menu:</span>
                    <ul class="space-y-1.5 text-xs">
                        @foreach($order->orderDetails as $detail)
                            <li class="flex justify-between items-center text-slate-600">
                                <span>{{ $detail->product->name ?? 'Menu' }} <span class="text-slate-400 font-semibold">({{ $detail->quantity }}x)</span> <span class="text-[11px] text-emerald-700 font-medium">[{{ $detail->product->stand->name ?? 'Stand' }}]</span></span>
                                <span class="font-bold font-mono text-slate-800">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="pt-3 border-t border-slate-200 flex justify-between items-center text-sm">
                    <span class="font-bold text-slate-700">Total Pembayaran:</span>
                    <span class="text-lg font-black text-emerald-700 font-mono">{{ $order->formatted_total }}</span>
                </div>

            </div>

            <!-- QRIS / Payment Box if QRIS or Transfer -->
            @if($order->payment_method === 'qris')
                <div class="bg-emerald-50 rounded-2xl border border-emerald-200 p-6 mb-8 text-center max-w-sm mx-auto space-y-4">

                    <!-- Judul -->
                    <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block">
                        <i class="fas fa-qrcode mr-1"></i> Scan QRIS Pembayaran Kantin
                    </span>

                    <!-- Total Pembayaran dinamis -->
                    <div class="bg-white rounded-xl border border-emerald-300 px-4 py-2.5 shadow-sm">
                        <p class="text-[11px] text-slate-500 font-medium">Total yang harus dibayar:</p>
                        <p class="text-2xl font-black text-emerald-700 font-mono">{{ $order->formatted_total }}</p>
                    </div>

                    <!-- Gambar QRIS Asli -->
                    <div class="bg-white p-3 rounded-2xl border-2 border-emerald-300 shadow-md mx-auto inline-block">
                        <img src="{{ asset('images/qris-kantin.jpeg') }}"
                             alt="QRIS Pembayaran Kantin MELATIAREA SMK Negeri 20 Jakarta"
                             class="w-full max-w-[260px] h-auto object-contain block mx-auto rounded-lg">
                    </div>

                    <!-- Instruksi utama -->
                    <p class="text-xs text-emerald-900 leading-relaxed">
                        Silakan scan QRIS di atas menggunakan aplikasi pembayaran yang mendukung QRIS
                        (GoPay, OVO, Dana, ShopeePay, BCA, Mandiri, dll).
                    </p>

                    <!-- Peringatan nominal -->
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-left space-y-1.5">
                        <p class="text-[11px] font-bold text-amber-800 flex items-center gap-1.5">
                            <i class="fas fa-triangle-exclamation text-amber-500"></i>
                            Perhatian
                        </p>
                        <p class="text-[11px] text-amber-900 leading-relaxed">
                            Karena QRIS ini bersifat statis, masukkan nominal <strong>{{ $order->formatted_total }}</strong> secara manual jika aplikasi pembayaran memintanya.
                        </p>
                        <p class="text-[11px] text-amber-900 leading-relaxed">
                            <strong>Pastikan nama penerima yang muncul pada aplikasi pembayaran sesuai dengan identitas QRIS sebelum melakukan pembayaran.</strong>
                        </p>
                    </div>

                    <!-- Status pembayaran -->
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Status pesanan akan diperbarui oleh admin setelah pembayaran dikonfirmasi.
                    </p>

                </div>
            @elseif($order->payment_method === 'transfer')
                <div class="bg-blue-50 rounded-2xl border border-blue-200 p-5 mb-8 text-center max-w-md mx-auto space-y-2 text-xs text-blue-900">
                    <h4 class="font-bold text-sm"><i class="fas fa-building-columns mr-1"></i> Instruksi Transfer Bank</h4>
                    <p>Bank Syariah Indonesia (BSI): <strong>7123-4567-89</strong></p>
                    <p>Atas Nama: <strong>Kantin Melatiarea SMK</strong></p>
                    <p>Jumlah: <strong class="text-sm font-black">{{ $order->formatted_total }}</strong></p>
                </div>
            @else
                <div class="bg-amber-50 rounded-2xl border border-amber-200 p-4 mb-8 text-center max-w-md mx-auto text-xs text-amber-900">
                    <i class="fas fa-cash-register mr-1 text-amber-600"></i>
                    Silakan siapkan uang pas sebesar <strong>{{ $order->formatted_total }}</strong> dan bayarkan langsung di stand saat mengambil pesanan.
                </div>
            @endif

            <!-- Navigation Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('orders.show', $order->id) }}" class="px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm transition shadow-md flex items-center gap-2">
                    <i class="fas fa-receipt"></i>
                    <span>Pantau Status Pesanan Ini</span>
                </a>
                <a href="{{ route('home') }}" class="px-6 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm transition">
                    Kembali ke Beranda
                </a>
            </div>

        </div>

    </div>

@endsection

