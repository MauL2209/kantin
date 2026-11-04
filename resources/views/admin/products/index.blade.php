@extends('layouts.admin')

@section('title', 'Kelola Menu & Stok Produk - Panel Admin')
@section('page_title', 'Kelola Produk & Stok Kantin')

@section('content')

    <div class="space-y-6">
        
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
            <div>
                <h2 class="text-base font-bold text-slate-900">Katalog Menu & Stok Real-Time</h2>
                <p class="text-xs text-slate-400">Atur harga, persediaan porsi harian, dan rekomendasi menu favorit siswa.</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm flex items-center gap-1.5 transition">
                <i class="fas fa-plus"></i> Tambah Menu Baru
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="w-full sm:w-64">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama menu..." 
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50">
                </div>

                <select name="stand_id" class="px-3 py-2 rounded-xl border border-slate-200 text-xs bg-slate-50 focus:outline-none text-slate-700">
                    <option value="">Semua Stand (8 Stand)</option>
                    @foreach($stands as $stand)
                        <option value="{{ $stand->id }}" {{ request('stand_id') == $stand->id ? 'selected' : '' }}>
                            {{ $stand->name }}
                        </option>
                    @endforeach
                </select>

                <select name="category_id" class="px-3 py-2 rounded-xl border border-slate-200 text-xs bg-slate-50 focus:outline-none text-slate-700">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-900 transition">
                    Filter
                </button>

                @if(request()->hasAny(['q', 'stand_id', 'category_id']))
                    <a href="{{ route('admin.products.index') }}" class="px-3 py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-100 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-100">
                            <th class="py-3 px-3">Menu</th>
                            <th class="py-3 px-3">Stand Jurusan</th>
                            <th class="py-3 px-3">Kategori</th>
                            <th class="py-3 px-3 text-right">Harga</th>
                            <th class="py-3 px-3 text-center">Atur Stok</th>
                            <th class="py-3 px-3 text-center">Favorit</th>
                            <th class="py-3 px-3 text-center">Status</th>
                            <th class="py-3 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($products as $product)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 px-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=100' }}" 
                                             alt="" class="w-12 h-12 rounded-xl object-cover border border-slate-200">
                                        <div>
                                            <h4 class="font-bold text-slate-900 text-sm leading-tight">{{ $product->name }}</h4>
                                            <p class="text-[11px] text-slate-400 line-clamp-1 max-w-xs">{{ $product->description }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded text-[11px]">
                                        {{ $product->stand->name ?? 'Stand' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[11px] font-semibold">
                                        {{ $product->category->name ?? 'Kategori' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 text-right font-mono font-bold text-slate-900 text-sm">
                                    {{ $product->formatted_price }}
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    <!-- Quick Stock Update Form -->
                                    <form action="{{ route('admin.products.stock', $product->id) }}" method="POST" class="inline-flex items-center gap-1.5">
                                        @csrf
                                        <input type="number" name="stock" value="{{ $product->stock }}" min="0" 
                                               class="w-14 text-center px-1.5 py-1 text-xs font-bold rounded-lg border border-slate-200 focus:outline-none focus:ring-1 focus:ring-emerald-500 {{ $product->stock <= 0 ? 'bg-rose-50 text-rose-700 border-rose-300' : 'bg-slate-50 text-slate-800' }}">
                                        <button type="submit" class="p-1.5 bg-slate-100 hover:bg-emerald-600 hover:text-white rounded-lg text-slate-600 transition" title="Simpan Stok">
                                            <i class="fas fa-check text-[10px]"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    @if($product->is_featured)
                                        <span class="text-amber-500 font-bold text-xs" title="Menu Favorit Siswa">
                                            <i class="fas fa-star"></i> Ya
                                        </span>
                                    @else
                                        <span class="text-slate-300 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    @if($product->isAvailable())
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tersedia
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 font-bold text-[10px]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Habis
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('menu.show', $product->id) }}" target="_blank" class="p-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-600 transition" title="Lihat">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="p-2 bg-amber-50 hover:bg-amber-100 rounded-lg text-amber-700 transition" title="Edit">
                                            <i class="fas fa-pencil text-xs"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus menu {{ $product->name }}?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 rounded-lg text-rose-700 transition" title="Hapus">
                                                <i class="fas fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>

    </div>

@endsection

