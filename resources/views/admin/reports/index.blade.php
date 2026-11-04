@extends('layouts.admin')

@section('title', 'Laporan Penjualan Kantin - Panel Admin')
@section('page_title', 'Laporan Penjualan & Keuangan')

@section('content')

    <div class="space-y-6">
        
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
            <div>
                <h2 class="text-base font-bold text-slate-900">Laporan Penjualan Kantin Sekolah [MELATIAREA]</h2>
                <p class="text-xs text-slate-400">Rekapitulasi omset, performa 8 stand jurusan, dan menu terlaris.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.reports.export_csv', request()->all()) }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm flex items-center gap-1.5 transition">
                    <i class="fas fa-file-csv"></i> Unduh Laporan CSV / Excel
                </a>
                <a href="{{ route('admin.reports.print', request()->all()) }}" target="_blank" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-sm flex items-center gap-1.5 transition">
                    <i class="fas fa-print"></i> Cetak Laporan PDF
                </a>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="space-y-3">
                <div class="flex flex-wrap items-center gap-3">
                    
                    <!-- Quick Presets -->
                    <div class="flex items-center gap-1.5 bg-slate-100 p-1 rounded-xl text-xs font-bold text-slate-600">
                        <a href="{{ route('admin.reports.index', ['period' => 'today', 'stand_id' => request('stand_id')]) }}" 
                           class="px-3 py-1.5 rounded-lg transition {{ $filter === 'today' ? 'bg-white text-emerald-700 shadow-sm' : 'hover:text-slate-900' }}">
                            Hari Ini
                        </a>
                        <a href="{{ route('admin.reports.index', ['period' => 'this_week', 'stand_id' => request('stand_id')]) }}" 
                           class="px-3 py-1.5 rounded-lg transition {{ $filter === 'this_week' ? 'bg-white text-emerald-700 shadow-sm' : 'hover:text-slate-900' }}">
                            Minggu Ini
                        </a>
                        <a href="{{ route('admin.reports.index', ['period' => 'this_month', 'stand_id' => request('stand_id')]) }}" 
                           class="px-3 py-1.5 rounded-lg transition {{ $filter === 'this_month' ? 'bg-white text-emerald-700 shadow-sm' : 'hover:text-slate-900' }}">
                            Bulan Ini
                        </a>
                        <a href="{{ route('admin.reports.index', ['period' => 'custom', 'stand_id' => request('stand_id')]) }}" 
                           class="px-3 py-1.5 rounded-lg transition {{ $filter === 'custom' ? 'bg-white text-emerald-700 shadow-sm' : 'hover:text-slate-900' }}">
                            Kustom Tanggal
                        </a>
                    </div>

                    <!-- Filter by Stand -->
                    <select name="stand_id" class="px-3 py-2 rounded-xl border border-slate-200 text-xs bg-slate-50 focus:outline-none text-slate-700">
                        <option value="">Semua Stand (8 Stand)</option>
                        @foreach($stands as $stand)
                            <option value="{{ $stand->id }}" {{ request('stand_id') == $stand->id ? 'selected' : '' }}>
                                {{ $stand->name }}
                            </option>
                        @endforeach
                    </select>

                </div>

                <!-- Custom Date Inputs -->
                @if($filter === 'custom')
                    <div class="flex flex-wrap items-center gap-3 pt-2 border-t border-slate-100 text-xs">
                        <input type="hidden" name="period" value="custom">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-500">Mulai:</span>
                            <input type="date" name="start_date" value="{{ request('start_date', date('Y-m-01')) }}" class="px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 text-xs">
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-slate-500">Sampai:</span>
                            <input type="date" name="end_date" value="{{ request('end_date', date('Y-m-d')) }}" class="px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 text-xs">
                        </div>
                        <button type="submit" class="px-4 py-1.5 bg-emerald-600 text-white rounded-xl font-bold shadow-sm">
                            Terapkan Rentang
                        </button>
                    </div>
                @endif
            </form>
        </div>

        <!-- 3 Summary Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Pendapatan Bersih</span>
                <h3 class="text-3xl font-black text-emerald-700 font-mono">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 mt-1">Periode: {{ ucfirst(str_replace('_', ' ', $filter)) }}</p>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Transaksi Selesai</span>
                <h3 class="text-3xl font-black text-slate-900">{{ $totalOrders }} Pesanan</h3>
                <p class="text-xs text-slate-500 mt-1">Tidak termasuk pesanan batal</p>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Rata-Rata Nilai Transaksi</span>
                <h3 class="text-3xl font-black text-slate-900 font-mono">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 mt-1">Per satu tiket pesanan</p>
            </div>
        </div>

        <!-- 2 Side-by-Side Performance Cards: Top Products & Top Stands -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Top Stands by Revenue -->
            <div class="lg:col-span-6 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-trophy text-amber-500"></i> Performa Stand Jurusan
                    </h3>
                    <span class="text-xs text-slate-400">8 Stand</span>
                </div>

                <div class="space-y-3">
                    @foreach($standsReport as $stand)
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3 text-xs">
                            <div>
                                <h4 class="font-bold text-slate-900">{{ $stand['name'] }}</h4>
                                <span class="text-[11px] text-emerald-700 font-semibold">Jurusan {{ $stand['department'] }}</span>
                                <p class="text-[10px] text-slate-400">{{ $stand['items_sold'] }} porsi terjual</p>
                            </div>
                            <div class="text-right">
                                <span class="font-bold font-mono text-sm text-slate-900">
                                    Rp {{ number_format($stand['revenue'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Top Selling Products -->
            <div class="lg:col-span-6 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-fire text-rose-500"></i> Produk Paling Laris
                    </h3>
                    <span class="text-xs text-slate-400">Top 5 Menu</span>
                </div>

                <div class="space-y-3">
                    @forelse($topProducts as $prod)
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-3">
                                <img src="{{ $prod->product->image ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=100' }}" alt="" class="w-10 h-10 rounded-xl object-cover">
                                <div>
                                    <h4 class="font-bold text-slate-900">{{ $prod->product->name ?? 'Menu' }}</h4>
                                    <span class="text-[11px] text-slate-500">{{ $prod->product->stand->name ?? 'Stand' }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-emerald-700 block">{{ $prod->total_qty }} porsi terjual</span>
                                <span class="text-[11px] text-slate-400 font-mono">Rp {{ number_format($prod->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center py-6 text-slate-400 text-xs">Belum ada transaksi produk pada periode ini.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Detailed Transaction Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Riwayat Transaksi Penjualan</h3>
                <p class="text-xs text-slate-400">Daftar lengkap seluruh transaksi penjualan yang terekam pada periode ini.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-100">
                            <th class="py-3 px-3">No. Pesanan</th>
                            <th class="py-3 px-3">Tanggal & Waktu</th>
                            <th class="py-3 px-3">Pemesan</th>
                            <th class="py-3 px-3">Kelas</th>
                            <th class="py-3 px-3">Metode Bayar</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-right">Total Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-3 font-mono font-bold text-slate-900">
                                    {{ $order->order_number }}
                                </td>
                                <td class="py-3 px-3 text-slate-500">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3 px-3 font-bold text-slate-800">
                                    {{ $order->customer_name }}
                                </td>
                                <td class="py-3 px-3 text-slate-600">
                                    {{ $order->customer_class }}
                                </td>
                                <td class="py-3 px-3 uppercase text-[11px] font-bold text-slate-600">
                                    {{ $order->payment_method }}
                                </td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-slate-900">
                                    {{ $order->formatted_total }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-slate-400">
                                    Tidak ada data penjualan pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection

