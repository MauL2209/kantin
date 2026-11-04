@extends('layouts.admin')

@section('title', 'Manajemen Pesanan Masuk - Panel Admin')
@section('page_title', 'Kelola Pesanan Siswa')

@section('content')

    <div class="space-y-6">
        
        <!-- Header & Summary -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
            <div>
                <h2 class="text-base font-bold text-slate-900">Daftar Seluruh Pesanan Kantin</h2>
                <p class="text-xs text-slate-400">Update status pesanan mulai dari diterima, dimasak, siap diambil, hingga selesai.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-slate-500">Total: <strong>{{ $orders->total() }}</strong> pesanan</span>
            </div>
        </div>

        <!-- Filter Tabs by Status -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.orders.index') }}" 
               class="px-3.5 py-2 rounded-2xl text-xs font-bold transition {{ !request('status') ? 'bg-slate-900 text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Semua ({{ $statusCounts['all'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'Pesanan Dibuat']) }}" 
               class="px-3.5 py-2 rounded-2xl text-xs font-bold transition {{ request('status') === 'Pesanan Dibuat' ? 'bg-amber-500 text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 inline-block mr-1"></span>
                Pesanan Dibuat ({{ $statusCounts['Pesanan Dibuat'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'Sedang Diproses']) }}" 
               class="px-3.5 py-2 rounded-2xl text-xs font-bold transition {{ request('status') === 'Sedang Diproses' ? 'bg-blue-600 text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400 inline-block mr-1"></span>
                Sedang Diproses ({{ $statusCounts['Sedang Diproses'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'Siap Diambil']) }}" 
               class="px-3.5 py-2 rounded-2xl text-xs font-bold transition {{ request('status') === 'Siap Diambil' ? 'bg-emerald-600 text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block mr-1"></span>
                Siap Diambil ({{ $statusCounts['Siap Diambil'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'Selesai']) }}" 
               class="px-3.5 py-2 rounded-2xl text-xs font-bold transition {{ request('status') === 'Selesai' ? 'bg-slate-700 text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Selesai ({{ $statusCounts['Selesai'] }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'Dibatalkan']) }}" 
               class="px-3.5 py-2 rounded-2xl text-xs font-bold transition {{ request('status') === 'Dibatalkan' ? 'bg-rose-600 text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Dibatalkan ({{ $statusCounts['Dibatalkan'] }})
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <input type="hidden" name="status" value="{{ request('status') }}">
                
                <div class="w-full sm:w-64">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="No. Pesanan / Nama Siswa / No. HP..." 
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50">
                </div>

                <div class="flex items-center gap-1.5">
                    <span class="text-xs text-slate-400">Tanggal:</span>
                    <input type="date" name="date" value="{{ request('date') }}" 
                           class="px-3 py-2 rounded-xl border border-slate-200 text-xs bg-slate-50 focus:outline-none text-slate-700">
                </div>

                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-900 transition">
                    Cari Pesanan
                </button>

                @if(request()->hasAny(['q', 'date']))
                    <a href="{{ route('admin.orders.index', ['status' => request('status')]) }}" class="px-3 py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-100 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-100">
                            <th class="py-3 px-3">No. Pesanan</th>
                            <th class="py-3 px-3">Waktu</th>
                            <th class="py-3 px-3">Nama Pemesan</th>
                            <th class="py-3 px-3">Kelas & Lokasi</th>
                            <th class="py-3 px-3">Menu Dipesan</th>
                            <th class="py-3 px-3">Bayar</th>
                            <th class="py-3 px-3 text-right">Total Tagihan</th>
                            <th class="py-3 px-3 text-center">Status</th>
                            <th class="py-3 px-3 text-center">Ubah Status</th>
                            <th class="py-3 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 px-3 font-mono font-bold text-slate-900">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:text-emerald-600">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="py-3.5 px-3 text-slate-400 text-[11px]">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3.5 px-3 font-bold text-slate-800">
                                    {{ $order->customer_name }}
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="block font-semibold text-slate-700">{{ $order->customer_class }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $order->customer_room }}</span>
                                </td>
                                <td class="py-3.5 px-3">
                                    <div class="text-[11px] space-y-0.5 max-w-xs">
                                        @foreach($order->orderDetails as $d)
                                            <div>
                                                <strong>{{ $d->quantity }}x</strong> {{ $d->product->name ?? 'Menu' }}
                                                <span class="text-slate-400 text-[10px]">({{ $d->product->stand->name ?? 'Stand' }})</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 uppercase text-[11px] font-bold text-slate-600">
                                    {{ $order->payment_method }}
                                </td>
                                <td class="py-3.5 px-3 text-right font-mono font-bold text-slate-900 text-sm">
                                    {{ $order->formatted_total }}
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    @php $badge = $order->status_badge; @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $badge['bg'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $badge['dot'] }}"></span>
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    <!-- Change Status Dropdown Form -->
                                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="inline" 
                                          onsubmit="if(this.status.value === 'Dibatalkan') { return confirm('Batalkan pesanan ini? Stok produk akan dikembalikan otomatis.'); }">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()" class="px-2 py-1 text-[11px] font-semibold rounded-lg border border-slate-200 bg-white focus:outline-none focus:ring-1 focus:ring-emerald-500">
                                            <option value="Pesanan Dibuat" {{ $order->status === 'Pesanan Dibuat' ? 'selected' : '' }}>Pesanan Dibuat</option>
                                            <option value="Sedang Diproses" {{ $order->status === 'Sedang Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                                            <option value="Siap Diambil" {{ $order->status === 'Siap Diambil' ? 'selected' : '' }}>Siap Diambil</option>
                                            <option value="Selesai" {{ $order->status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="Dibatalkan" {{ $order->status === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="p-1.5 bg-slate-100 hover:bg-slate-200 rounded text-slate-600 transition" title="Lihat Invoice">
                                            <i class="far fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="p-1.5 bg-slate-100 hover:bg-slate-200 rounded text-slate-600 transition" title="Cetak Struk">
                                            <i class="fas fa-print text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-10 text-slate-400">
                                    Tidak ada data pesanan pada kriteria ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>

    </div>

@endsection

