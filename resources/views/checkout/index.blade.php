@extends('layouts.app')

@section('title', 'Form Pemesanan & Checkout - Kantin Sekolah MELATIAREA')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="mb-8 pb-4 border-b border-slate-200">
            <span class="text-xs font-bold text-emerald-700 tracking-wider uppercase">Sistem Pre-Order</span>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Formulir Pemesanan & Checkout</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Lengkapi data diri dan lokasi pengambilan untuk kemudahan koordinasi dengan stand kantin.</p>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Checkout Form Fields -->
                <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 space-y-6">
                    
                    <div>
                        <h3 class="text-base font-bold text-slate-900 mb-1 flex items-center gap-2">
                            <i class="fas fa-user-circle text-emerald-600"></i> Informasi Pemesan
                        </h3>
                        <p class="text-xs text-slate-400">Pastikan data sesuai agar stand kantin mudah mengenali pesananmu.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Lengkap -->
                        <div class="sm:col-span-2 space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">Nama Lengkap Siswa / Pemesan <span class="text-rose-500">*</span></label>
                            <input type="text" name="customer_name" required value="{{ old('customer_name', $user->name ?? '') }}" placeholder="Contoh: Muhammad Rizky Pratama" 
                                   class="w-full px-4 py-2.5 rounded-xl border @error('customer_name') border-rose-400 @else border-slate-200 @enderror text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                            @error('customer_name') <p class="text-xs text-rose-500 mt-0.5">{{ $message }}</p> @enderror
                        </div>

                        <!-- NIS -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">NIS (Nomor Induk Siswa)</label>
                            <input type="text" name="customer_nis" value="{{ old('customer_nis', $user->nis ?? '') }}" placeholder="Contoh: 20241001" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                            @error('customer_nis') <p class="text-xs text-rose-500 mt-0.5">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kelas -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">Kelas <span class="text-rose-500">*</span></label>
                            <input type="text" name="customer_class" required value="{{ old('customer_class', $user->class_room ?? '') }}" placeholder="Contoh: XII RPL 1 / XI BD 2" 
                                   class="w-full px-4 py-2.5 rounded-xl border @error('customer_class') border-rose-400 @else border-slate-200 @enderror text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                            @error('customer_class') <p class="text-xs text-rose-500 mt-0.5">{{ $message }}</p> @enderror
                        </div>

                        <!-- Ruangan / Lokasi Pengambilan -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">Ruangan / Titik Pengambilan <span class="text-rose-500">*</span></label>
                            <input type="text" name="customer_room" required value="{{ old('customer_room', 'Ambil di Stand Kantin') }}" placeholder="Contoh: Ambil di Stand / Ruang Lab RPL 2" 
                                   class="w-full px-4 py-2.5 rounded-xl border @error('customer_room') border-rose-400 @else border-slate-200 @enderror text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                            <span class="text-[10px] text-slate-400">Tentukan lokasi pengantaran atau ambil mandiri di stand.</span>
                            @error('customer_room') <p class="text-xs text-rose-500 mt-0.5">{{ $message }}</p> @enderror
                        </div>

                        <!-- No HP / WhatsApp -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">Nomor WhatsApp / HP <span class="text-rose-500">*</span></label>
                            <input type="tel" name="customer_phone" required value="{{ old('customer_phone', $user->phone ?? '') }}" placeholder="Contoh: 081234567890" 
                                   class="w-full px-4 py-2.5 rounded-xl border @error('customer_phone') border-rose-400 @else border-slate-200 @enderror text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                            @error('customer_phone') <p class="text-xs text-rose-500 mt-0.5">{{ $message }}</p> @enderror
                        </div>

                        <!-- Catatan Tambahan -->
                        <div class="sm:col-span-2 space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">Catatan Tambahan (Opsional)</label>
                            <textarea name="customer_note" rows="2" placeholder="Contoh: Sambal dipisah, es jangan terlalu manis, ambil istirahat pertama." 
                                      class="w-full px-4 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">{{ old('customer_note') }}</textarea>
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="pt-6 border-t border-slate-100 space-y-3" x-data="{ method: 'cash' }">
                        <div>
                            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                <i class="fas fa-credit-card text-emerald-600"></i> Pilih Metode Pembayaran <span class="text-rose-500">*</span>
                            </h3>
                            <p class="text-xs text-slate-400">Pilih metode yang paling nyaman untuk Anda.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                            
                            <!-- Option 1: Bayar di Kasir -->
                            <label @click="method = 'cash'" class="cursor-pointer border rounded-2xl p-4 flex flex-col justify-between transition" 
                                   :class="method === 'cash' ? 'border-emerald-500 bg-emerald-50/50 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:border-slate-300'">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-sm">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <input type="radio" name="payment_method" value="cash" x-model="method" class="text-emerald-600 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <h4 class="font-bold text-xs text-slate-900">Bayar di Kasir</h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Bayar tunai di stand saat mengambil pesanan.</p>
                                </div>
                            </label>

                            <!-- Option 2: QRIS -->
                            <label @click="method = 'qris'" class="cursor-pointer border rounded-2xl p-4 flex flex-col justify-between transition"
                                   :class="method === 'qris' ? 'border-emerald-500 bg-emerald-50/50 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:border-slate-300'">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm">
                                        <i class="fas fa-qrcode"></i>
                                    </div>
                                    <input type="radio" name="payment_method" value="qris" x-model="method" class="text-emerald-600 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <h4 class="font-bold text-xs text-slate-900">QRIS Melatiarea</h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Scan barcode dari GoPay, OVO, Dana, BCA, dll.</p>
                                </div>
                            </label>

                            <!-- Option 3: Transfer Bank -->
                            <label @click="method = 'transfer'" class="cursor-pointer border rounded-2xl p-4 flex flex-col justify-between transition"
                                   :class="method === 'transfer' ? 'border-emerald-500 bg-emerald-50/50 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:border-slate-300'">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-sm">
                                        <i class="fas fa-building-columns"></i>
                                    </div>
                                    <input type="radio" name="payment_method" value="transfer" x-model="method" class="text-emerald-600 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <h4 class="font-bold text-xs text-slate-900">Transfer Bank</h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Rekening Bank Syariah Sekolah.</p>
                                </div>
                            </label>

                        </div>

                        <!-- Method Information Box -->
                        <div x-show="method === 'qris'" class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 flex items-center gap-3">
                            <i class="fas fa-qrcode text-xl text-emerald-600"></i>
                            <p>Setelah menekan tombol buat pesanan, QR Code QRIS akan langsung ditampilkan untuk pembayaran instan.</p>
                        </div>
                        <div x-show="method === 'transfer'" class="p-3.5 rounded-xl bg-blue-50 border border-blue-200 text-xs text-blue-900 flex items-center gap-3">
                            <i class="fas fa-info-circle text-xl text-blue-600"></i>
                            <p>Nomor rekening BSI SMK Melatiarea: <strong>7123-4567-89 (A.n Kantin Melatiarea)</strong>.</p>
                        </div>
                    </div>

                </div>

                <!-- Right: Order Review Sidebar -->
                <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-7 space-y-6 sticky top-28">
                    <h3 class="text-base font-black text-slate-900 border-b border-slate-100 pb-3">
                        Daftar Pesanan Kamu
                    </h3>

                    <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto pr-1">
                        @foreach($cart as $item)
                            <div class="py-3 first:pt-0 last:pb-0 flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item['image'] ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=100' }}" alt="" class="w-10 h-10 rounded-lg object-cover">
                                    <div>
                                        <h4 class="font-bold text-slate-800 leading-tight">{{ $item['name'] }}</h4>
                                        <span class="text-[10px] text-emerald-700 font-semibold">{{ $item['stand_name'] }}</span>
                                        <p class="text-slate-400 text-[11px]">{{ $item['quantity'] }}x @ Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <span class="font-bold text-slate-800 font-mono">
                                    Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-slate-200 pt-4 space-y-2 text-xs">
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Subtotal Menu</span>
                            <span class="font-bold text-slate-800 font-mono">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Biaya Pelayanan Kantin</span>
                            <span class="font-bold text-emerald-600">Rp 0 (Gratis)</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-900 font-black text-sm pt-2 border-t border-slate-100">
                            <span>Total Pembayaran</span>
                            <span class="text-lg font-black text-emerald-700 font-mono">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-sm text-center transition duration-200 shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        <span>Buat Pesanan Sekarang</span>
                    </button>

                    <p class="text-[11px] text-slate-400 text-center leading-relaxed">
                        Dengan membuat pesanan, kamu menyetujui ketentuan pre-order kantin sekolah SMK Melatiarea.
                    </p>
                </div>

            </div>

        </form>

    </div>

@endsection

