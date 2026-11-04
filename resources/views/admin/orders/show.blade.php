@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->order_number . ' - Panel Admin')
@section('page_title', 'Detail & Tiket Pesanan')

@section('content')

    <div class="max-w-4xl mx-auto space-y-6">
        
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-slate-500 hover:text-emerald-700 flex items-center gap-1.5 transition">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="px-3.5 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
                    <i class="fas fa-print"></i> Cetak Struk / Tiket Dapur
                </a>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
            
            <!-- Order Header -->
            <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">ID Pesanan Kantin</span>
                    <h1 class="text-2xl font-black text-slate-900 font-mono">{{ $order->order_number }}</h1>
                    <p class="text-xs text-slate-400 mt-0.5">Dipesan pada: {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
                </div>

                <!-- Update Status Box -->
                <div class="flex items-center gap-3">
                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex items-center gap-2"
                          onsubmit="if(this.status.value === 'Dibatalkan') { return confirm('Batalkan pesanan ini? Stok produk akan dikembalikan.'); }">
                        @csrf
                        <label class="text-xs font-bold text-slate-500">Status:</label>
                        <select name="status" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold bg-slate-50 focus:outline-none focus:ring-2 focus:ring-brand-500 text-slate-800">
                            <option value="Pesanan Dibuat" {{ $order->status === 'Pesanan Dibuat' ? 'selected' : '' }}>Pesanan Dibuat</option>
                            <option value="Sedang Diproses" {{ $order->status === 'Sedang Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                            <option value="Siap Diambil" {{ $order->status === 'Siap Diambil' ? 'selected' : '' }}>Siap Diambil</option>
                            <option value="Selesai" {{ $order->status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Dibatalkan" {{ $order->status === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                            Update
                        </button>
                    </form>
                </div>
            </div>

            <!-- Customer & Delivery Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 p-5 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                <div>
                    <span class="text-slate-400 block mb-0.5">Nama Siswa / Pemesan</span>
                    <strong class="text-slate-800 text-sm block">{{ $order->customer_name }}</strong>
                    <span class="text-[11px] text-slate-500">NIS: {{ $order->customer_nis ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5">Kelas / Ruang</span>
                    <strong class="text-slate-800 text-sm block">{{ $order->customer_class }}</strong>
                    <span class="text-[11px] text-slate-500">{{ $order->customer_room }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5">Kontak WhatsApp</span>
                    <strong class="text-slate-800 text-sm block">{{ $order->customer_phone }}</strong>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" class="text-emerald-600 hover:underline text-[11px] font-semibold">
                        <i class="fab fa-whatsapp"></i> Chat WhatsApp
                    </a>
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5">Metode Pembayaran</span>
                    <strong class="text-slate-800 text-sm uppercase block">{{ $order->payment_method }}</strong>
                    <span class="text-[11px] {{ $order->payment && $order->payment->status === 'paid' ? 'text-emerald-600 font-bold' : 'text-amber-600 font-semibold' }}">
                        {{ $order->payment && $order->payment->status === 'paid' ? 'LUNAS' : 'MENUNGGU BAYAR' }}
                    </span>
                </div>
            </div>

            @if($order->customer_note)
                <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900">
                    <strong>Catatan Khusus dari Siswa:</strong> "{{ $order->customer_note }}"
                </div>
            @endif

            <!-- Order Items Table -->
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Daftar Menu Yang Dipesan</h3>
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Menu</th>
                                <th class="py-3 px-4">Stand Jurusan</th>
                                <th class="py-3 px-4 text-center">Jumlah</th>
                                <th class="py-3 px-4 text-right">Harga Satuan</th>
                                <th class="py-3 px-4 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($order->orderDetails as $detail)
                                <tr>
                                    <td class="py-3 px-4 font-bold text-slate-900">
                                        {{ $detail->product->name ?? 'Menu' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded text-[11px]">
                                            {{ $detail->product->stand->name ?? 'Stand Melati' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center font-bold text-slate-900">
                                        {{ $detail->quantity }} porsi
                                    </td>
                                    <td class="py-3 px-4 text-right font-mono">
                                        {{ $detail->formatted_price }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-slate-900">
                                        {{ $detail->formatted_subtotal }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total Calculation -->
            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 flex justify-between items-center text-sm">
                <span class="font-bold text-slate-700">Total Pembayaran Akhir:</span>
                <span class="text-2xl font-black text-emerald-700 font-mono">{{ $order->formatted_total }}</span>
            </div>

        </div>

    </div>

@endsection

