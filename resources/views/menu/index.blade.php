@extends('layouts.app')

@section('title', 'Daftar Menu Kantin Sekolah MELATIAREA')

@section('content')

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl space-y-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 font-bold text-xs uppercase tracking-wider border border-emerald-400/30">
                    <i class="fas fa-bowl-food"></i> Katalog Menu Terpadu
                </span>
                <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                    Menu Makanan & Minuman
                </h1>
                <p class="text-slate-300 text-sm leading-relaxed">
                    Jelajahi seluruh pilihan hidangan lezat dan minuman segar dari 8 stand kantin jurusan. Gunakan filter untuk menemukan menu favoritmu dengan cepat.
                </p>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white border-b border-slate-200 sticky top-20 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            
            <form action="{{ route('menu.index') }}" method="GET" class="flex flex-col lg:flex-row items-center gap-3">
                
                <!-- Search Input -->
                <div class="relative w-full lg:w-1/3">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-search text-sm"></i>
                    </span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari menu favoritmu (cth: nasi goreng, kopi, risol)..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 text-xs sm:text-sm bg-slate-50 focus:bg-white transition">
                </div>

                <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full lg:w-2/3">
                    <!-- Filter Stand -->
                    <select name="stand" class="w-full sm:w-1/3 py-2.5 px-3 rounded-xl border border-slate-200 text-xs sm:text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium text-slate-700">
                        <option value="">Semua 8 Stand</option>
                        @foreach($stands as $s)
                            <option value="{{ $s->id }}" {{ request('stand') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }} ({{ $s->department }})
                            </option>
                        @endforeach
                    </select>

                    <!-- Filter Kategori -->
                    <select name="category" class="w-full sm:w-1/3 py-2.5 px-3 rounded-xl border border-slate-200 text-xs sm:text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium text-slate-700">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ request('category') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Sorting -->
                    <select name="sort" class="w-full sm:w-1/3 py-2.5 px-3 rounded-xl border border-slate-200 text-xs sm:text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium text-slate-700">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Favorit Siswa</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Termurah</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi</option>
                    </select>

                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition flex items-center justify-center gap-1.5 shadow-sm">
                        <i class="fas fa-filter"></i> Terapkan
                    </button>

                    @if(request()->hasAny(['q', 'stand', 'category', 'sort']))
                        <a href="{{ route('menu.index') }}" class="w-full sm:w-auto px-3 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 font-semibold text-xs text-center transition" title="Reset Filter">
                            Reset
                        </a>
                    @endif
                </div>

            </form>

        </div>
    </div>

    <!-- Menu Items Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="flex items-center justify-between mb-6">
            <p class="text-xs sm:text-sm text-slate-500">
                Menampilkan <strong class="text-slate-800">{{ $products->total() }}</strong> menu makanan & minuman
            </p>
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden group">
                        
                        <!-- Image & Badges -->
                        <div class="relative h-44 w-full bg-slate-100 overflow-hidden">
                            <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500' }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            
                            <!-- Category Badge -->
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 rounded-full bg-emerald-600 text-white font-extrabold text-[10px] shadow-sm uppercase tracking-wider">
                                    {{ $product->category->name }}
                                </span>
                            </div>

                            @if($product->is_featured)
                                <div class="absolute top-3 right-3">
                                    <span class="px-2 py-0.5 rounded-full bg-amber-400 text-slate-950 font-black text-[10px] shadow-sm flex items-center gap-1">
                                        <i class="fas fa-star text-[9px]"></i> Favorit
                                    </span>
                                </div>
                            @endif

                            @if($product->stock <= 0)
                                <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-[2px] flex items-center justify-center">
                                    <span class="px-3.5 py-1.5 rounded-full bg-rose-600 text-white font-black text-xs tracking-wider uppercase shadow-lg">
                                        Habis
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex-grow flex flex-col justify-between space-y-3">
                            <div>
                                <a href="{{ route('stands.show', $product->stand_id) }}" class="text-[11px] font-bold text-emerald-700 hover:text-emerald-800 tracking-wide uppercase block mb-1">
                                    <i class="fas fa-store mr-1"></i> {{ $product->stand->name }}
                                </a>
                                <h3 class="text-base font-bold text-slate-900 leading-snug group-hover:text-emerald-700 transition">
                                    <a href="{{ route('menu.show', $product->id) }}">{{ $product->name }}</a>
                                </h3>
                                <p class="text-xs text-slate-500 line-clamp-2 mt-1 leading-relaxed">
                                    {{ $product->description }}
                                </p>
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] text-slate-400 font-semibold block leading-tight">Harga</span>
                                    <span class="text-base font-black text-slate-900">
                                        {{ $product->formatted_price }}
                                    </span>
                                </div>
                                <span class="text-[11px] font-semibold {{ $product->stock > 0 ? 'text-slate-600 bg-slate-100' : 'text-rose-600 bg-rose-50' }} px-2.5 py-1 rounded-lg">
                                    Stok: <strong>{{ $product->stock }}</strong>
                                </span>
                            </div>

                            <!-- Add to Cart / Disabled Button -->
                            @if($product->isAvailable())
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="pt-1">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition duration-200 shadow-sm flex items-center justify-center gap-2">
                                        <i class="fas fa-plus text-[10px]"></i>
                                        <span>Tambah ke Keranjang</span>
                                    </button>
                                </form>
                            @else
                                <button disabled class="w-full py-2.5 rounded-xl bg-slate-200 text-slate-400 font-bold text-xs cursor-not-allowed flex items-center justify-center gap-2">
                                    <i class="fas fa-ban text-[10px]"></i>
                                    <span>Habis</span>
                                </button>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-3xl border border-slate-200">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                    <i class="fas fa-magnifying-glass"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800">Tidak ada menu yang sesuai</h3>
                <p class="text-xs text-slate-500 mt-1">Coba gunakan kata kunci lain atau reset filter pencarian.</p>
                <a href="{{ route('menu.index') }}" class="inline-block mt-4 px-5 py-2.5 bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-sm">
                    Tampilkan Semua Menu
                </a>
            </div>
        @endif

    </div>

@endsection

