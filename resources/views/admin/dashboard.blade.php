@extends('layouts.admin')

@section('title', 'Dashboard Administrator - Kantin MELATIAREA')
@section('page_title', 'Ringkasan & Dashboard Utama')

@section('content')

    <div class="space-y-8">

        <!-- Header with User Management Link -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Ringkasan Hari Ini</h2>
            </div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                <i class="fas fa-users text-xs"></i>
                <span>Kelola Pengguna</span>
            </a>
        </div>

        <!-- 8 Key Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-8 gap-4">

            <!-- Penjualan Hari Ini -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between text-emerald-600 mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Penjualan Hari Ini</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-sm">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 font-mono">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                    <p class="text-[11px] text-emerald-600 font-semibold mt-0.5">Pendapatan Bersih</p>
                </div>
            </div>

            <!-- Pesanan Hari Ini -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between text-blue-600 mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pesanan Hari Ini</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center text-sm">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900">{{ $todayOrdersCount }}</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Transaksi Masuk</p>
                </div>
            </div>

            <!-- Pesanan Diproses -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between text-amber-500 mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sedang Diproses</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center text-sm">
                        <i class="fas fa-fire-burner"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-amber-600">{{ $processingOrdersCount }}</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Di Dapur Stand</p>
                </div>
            </div>

            <!-- Siap Diambil -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between text-teal-600 mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Siap Diambil</span>
                    <div class="w-8 h-8 rounded-xl bg-teal-50 flex items-center justify-center text-sm">
                        <i class="fas fa-box-open"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-teal-600">{{ $readyOrdersCount }}</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Menunggu Siswa</p>
                </div>
            </div>

            <!-- Total Stand -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between text-purple-600 mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Stand</span>
                    <div class="w-8 h-8 rounded-xl bg-purple-50 flex items-center justify-center text-sm">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900">{{ $totalStands }}</h3>
                    <p class="text-[11px] text-purple-700 font-semibold mt-0.5">8 Stand Jurusan</p>
                </div>
            </div>

            <!-- Total Produk Menu -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between text-rose-600 mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Menu Aktif</span>
                    <div class="w-8 h-8 rounded-xl bg-rose-50 flex items-center justify-center text-sm">
                        <i class="fas fa-utensils"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900">{{ $totalProducts }}</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Varian Menu</p>
                </div>
            </div>

            <!-- Total Siswa -->
            <a href="{{ route('admin.users.index', ['role' => 'siswa']) }}" class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-indigo-300 hover:shadow-md transition group">
                <div class="flex items-center justify-between text-indigo-600 mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider group-hover:text-indigo-600 transition">Total Siswa</span>
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center text-sm">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900">{{ $totalSiswa }}</h3>
                    <p class="text-[11px] text-indigo-600 font-semibold mt-0.5">Akun Siswa</p>
                </div>
            </a>

            <!-- Total Penjaga -->
            <a href="{{ route('admin.users.index', ['role' => 'penjaga']) }}" class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between hover:border-emerald-300 hover:shadow-md transition group">
                <div class="flex items-center justify-between text-emerald-600 mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider group-hover:text-emerald-600 transition">Total Penjaga</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-sm">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900">{{ $totalPenjaga }}</h3>
                    <p class="text-[11px] text-emerald-600 font-semibold mt-0.5">Penjaga Kantin</p>
                </div>
            </a>

        </div>

        <!-- 2 Analytic Charts (Sales Trend & Best Selling Products) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Chart 1: Daily Sales Trend (7 Days) -->
            <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">Grafik Penjualan Harian (7 Hari Terakhir)</h3>
                        <p class="text-xs text-slate-400">Total akumulasi transaksi dari seluruh stand</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100">
                        Rupiah (IDR)
                    </span>
                </div>
                <div class="h-64 sm:h-72">
                    <canvas id="dailySalesChart"></canvas>
                </div>
            </div>

            <!-- Chart 2: Top Selling Products -->
            <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm flex flex-col justify-between">
                <div class="mb-4">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Produk Paling Laris</h3>
                    <p class="text-xs text-slate-400">5 menu paling diminati siswa</p>
                </div>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="topProductsChart"></canvas>
                </div>
                <div class="text-[11px] text-center text-slate-400 pt-3 border-t border-slate-100">
                    Berdasarkan jumlah kuantitas porsi terjual
                </div>
            </div>

        </div>

        <!-- Recent Orders Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden p-6 space-y-4">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Pesanan Terbaru Masuk</h3>
                    <p class="text-xs text-slate-400">Daftar transaksi pesanan terkini yang memerlukan tindak lanjut</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1">
                    <span>Lihat Semua Pesanan</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-100">
                            <th class="py-3 px-3">No. Pesanan</th>
                            <th class="py-3 px-3">Nama Siswa</th>
                            <th class="py-3 px-3">Kelas & Lokasi</th>
                            <th class="py-3 px-3">Stand Terkait</th>
                            <th class="py-3 px-3 text-right">Total</th>
                            <th class="py-3 px-3 text-center">Status</th>
                            <th class="py-3 px-3">Waktu</th>
                            <th class="py-3 px-3 text-center">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3.5 px-3 font-mono font-bold text-slate-900">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:text-emerald-600">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="py-3.5 px-3 font-bold text-slate-800">
                                    {{ $order->customer_name }}
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="block font-semibold text-slate-700">{{ $order->customer_class }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $order->customer_room }}</span>
                                </td>
                                <td class="py-3.5 px-3">
                                    @php
                                        $standNames = $order->orderDetails->map(fn($d) => $d->product->stand->name ?? 'Stand')->unique()->implode(', ');
                                    @endphp
                                    <span class="text-[11px] text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded font-semibold truncate max-w-[150px] inline-block">
                                        {{ $standNames ?: 'Stand Melati' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 text-right font-mono font-bold text-slate-900">
                                    {{ $order->formatted_total }}
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    @php $badge = $order->status_badge; @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $badge['bg'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $badge['dot'] }}"></span>
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 text-slate-400 text-[11px]">
                                    {{ $order->created_at->diffForHumans() }}
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="p-1.5 bg-slate-100 hover:bg-emerald-600 hover:text-white rounded-lg text-slate-600 transition" title="Lihat Detail / Invoice">
                                            <i class="far fa-eye text-xs"></i>
                                        </a>

                                        @if($order->status === 'Pesanan Dibuat')
                                            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="status" value="Sedang Diproses">
                                                <button type="submit" class="px-2 py-1 bg-blue-100 hover:bg-blue-600 hover:text-white text-blue-700 font-bold rounded-lg text-[10px] transition" title="Mulai Proses">
                                                    Proses
                                                </button>
                                            </form>
                                        @elseif($order->status === 'Sedang Diproses')
                                            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="status" value="Siap Diambil">
                                                <button type="submit" class="px-2 py-1 bg-emerald-100 hover:bg-emerald-600 hover:text-white text-emerald-800 font-bold rounded-lg text-[10px] transition" title="Tandai Siap">
                                                    Siap
                                                </button>
                                            </form>
                                        @elseif($order->status === 'Siap Diambil')
                                            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="status" value="Selesai">
                                                <button type="submit" class="px-2 py-1 bg-slate-800 hover:bg-black text-white font-bold rounded-lg text-[10px] transition" title="Selesai">
                                                    Selesai
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-slate-400">
                                    Belum ada pesanan terbaru hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    @push('scripts')
    <script>
        // 1. Daily Sales Chart
        const salesCtx = document.getElementById('dailySalesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dates) !!},
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: {!! json_encode($salesData) !!},
                    borderColor: '#059669',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#047857',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000) + 'k';
                            }
                        }
                    }
                }
            }
        });

        // 2. Top Products Doughnut Chart
        const topCtx = document.getElementById('topProductsChart').getContext('2d');
        new Chart(topCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($topProductLabels) !!},
                datasets: [{
                    data: {!! json_encode($topProductData) !!},
                    backgroundColor: [
                        '#10b981',
                        '#3b82f6',
                        '#f59e0b',
                        '#8b5cf6',
                        '#ec4899'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 10 } }
                    }
                }
            }
        });
    </script>
    @endpush

@endsection

