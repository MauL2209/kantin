@extends('layouts.app')

@section('title', $stand->name . ' - Kantin Sekolah MELATIAREA')

@section('content')

    <!-- Stand Profile Header -->
    <div class="relative bg-slate-900 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-25">
            <img src="{{ $stand->image ?: 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=1200' }}" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/90 to-slate-900/60"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 relative z-10">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                
                <div class="space-y-3 max-w-2xl">
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('stands.index') }}" class="text-xs font-bold text-slate-400 hover:text-white transition flex items-center gap-1">
                            <i class="fas fa-arrow-left"></i> Semua Stand
                        </a>
                        <span class="text-slate-600">•</span>
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-400/30">
                            Jurusan {{ $stand->department }}
                        </span>
                        @if($stand->isOpen())
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-500 text-slate-950 text-xs font-black">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-950"></span> Buka
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-500 text-white text-xs font-bold">
                                Tutup
                            </span>
                        @endif
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                        {{ $stand->name }}
                    </h1>

                    <p class="text-slate-300 text-sm leading-relaxed">
                        {{ $stand->description }}
                    </p>

                    <div class="flex flex-wrap items-center gap-4 text-xs text-slate-300 pt-1">
                        @if($stand->location)
                            <span class="flex items-center gap-1.5">
                                <i class="fas fa-location-dot text-rose-400"></i> {{ $stand->location }}
                            </span>
                        @endif
                        @if($stand->contact_person)
                            <span class="flex items-center gap-1.5">
                                <i class="fas fa-phone text-emerald-400"></i> {{ $stand->contact_person }}
                            </span>
                        @endif
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-utensils text-amber-400"></i> {{ $products->total() }} Pilihan Menu
                        </span>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-5 text-center min-w-[200px]">
                    <span class="block text-3xl font-black text-emerald-400">{{ $products->total() }}</span>
                    <span class="text-xs text-slate-300">Menu Siap Dipesan</span>
                </div>

            </div>
        </div>
    </div>

    <!-- Stand Menus Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Category Filter Tabs -->
        <div class="flex flex-wrap items-center gap-2 pb-6 border-b border-slate-200">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-2">Filter Kategori:</span>
            <a href="{{ route('stands.show', $stand->id) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition {{ !request('category') ? 'bg-emerald-600 text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                Semua Kategori
            </a>
            @foreach($categories as $category)
                <a href="{{ route('stands.show', ['stand' => $stand->id, 'category' => $category->id]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('category') == $category->id ? 'bg-emerald-600 text-white shadow' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
                @foreach($products as $product)
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden group">
                        
                        <div class="relative h-44 w-full bg-slate-100 overflow-hidden">
                            <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500' }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 rounded-full bg-emerald-600 text-white font-extrabold text-[10px] shadow-sm uppercase">
                                    {{ $product->category->name }}
                                </span>
                            </div>

                            @if($product->stock <= 0)
                                <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-[2px] flex items-center justify-center">
                                    <span class="px-3 py-1 rounded-full bg-rose-600 text-white font-extrabold text-xs uppercase shadow">
                                        Habis
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="p-5 flex-grow flex flex-col justify-between space-y-3">
                            <div>
                                <h3 class="text-base font-bold text-slate-900 leading-snug group-hover:text-emerald-700 transition">
                                    {{ $product->name }}
                                </h3>
                                <p class="text-xs text-slate-500 line-clamp-2 mt-1 leading-relaxed">
                                    {{ $product->description }}
                                </p>
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] text-slate-400 font-semibold block leading-tight">Harga</span>
                                    <span class="text-base font-black text-slate-900">{{ $product->formatted_price }}</span>
                                </div>
                                <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">
                                    Stok: <strong>{{ $product->stock }}</strong>
                                </span>
                            </div>

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
                                    <span>Stok Habis</span>
                                </button>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-3xl border border-slate-200 mt-8">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800">Belum ada menu di kategori ini</h3>
                <p class="text-xs text-slate-500 mt-1">Silakan pilih kategori lain untuk melihat pilihan menu stand.</p>
                <a href="{{ route('stands.show', $stand->id) }}" class="inline-block mt-4 px-4 py-2 bg-emerald-600 text-white font-bold text-xs rounded-xl">
                    Lihat Semua Menu Stand
                </a>
            </div>
        @endif

    </div>

@endsection

