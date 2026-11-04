@extends('layouts.app')

@section('title', $product->name . ' - Kantin Sekolah MELATIAREA')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-emerald-700">Beranda</a>
            <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
            <a href="{{ route('menu.index') }}" class="hover:text-emerald-700">Menu</a>
            <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
            <a href="{{ route('stands.show', $product->stand_id) }}" class="hover:text-emerald-700">{{ $product->stand->name }}</a>
            <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="text-slate-800 font-bold truncate max-w-xs">{{ $product->name }}</span>
        </nav>

        <!-- Product Main Card -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden p-6 sm:p-10 mb-14">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 items-center">
                
                <!-- Left: Product Photo -->
                <div class="md:col-span-5 relative rounded-2xl overflow-hidden bg-slate-100 aspect-square shadow-inner">
                    <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=700' }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover">
                    
                    @if($product->stock <= 0)
                        <div class="absolute inset-0 bg-slate-900/75 backdrop-blur-[2px] flex items-center justify-center">
                            <span class="px-4 py-2 rounded-full bg-rose-600 text-white font-extrabold text-sm uppercase shadow-xl tracking-wider">
                                Stok Habis
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Right: Product Info & Actions -->
                <div class="md:col-span-7 space-y-5">
                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs">
                                {{ $product->category->name }}
                            </span>
                            <a href="{{ route('stands.show', $product->stand_id) }}" class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200 font-semibold text-xs transition">
                                <i class="fas fa-store text-emerald-600 mr-1"></i> {{ $product->stand->name }}
                            </a>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                            {{ $product->name }}
                        </h1>
                        <p class="text-2xl font-black text-emerald-600">
                            {{ $product->formatted_price }}
                        </p>
                    </div>

                    <div class="border-y border-slate-100 py-4 space-y-2">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Deskripsi Menu:</h4>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            {{ $product->description ?: 'Menu lezat disajikan higienis oleh stand jurusan ' . $product->stand->department . '.' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-xs">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 block mb-0.5">Ketersediaan Stok</span>
                            <span class="font-bold text-sm {{ $product->stock > 0 ? 'text-slate-800' : 'text-rose-600' }}">
                                {{ $product->stock > 0 ? $product->stock . ' porsi tersisa' : 'Habis' }}
                            </span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <span class="text-slate-400 block mb-0.5">Stand Pengelola</span>
                            <span class="font-bold text-sm text-slate-800 truncate block">
                                {{ $product->stand->department }}
                            </span>
                        </div>
                    </div>

                    <!-- Add to Cart Form -->
                    @if($product->isAvailable())
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="pt-3 flex flex-col sm:flex-row items-center gap-3" x-data="{ qty: 1, max: {{ $product->stock }} }">
                            @csrf
                            <div class="flex items-center border border-slate-200 rounded-xl bg-slate-50 p-1 w-full sm:w-auto">
                                <button type="button" @click="if(qty > 1) qty--" class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center font-bold text-slate-700 hover:bg-slate-100">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <input type="number" name="quantity" x-model="qty" min="1" :max="max" class="w-16 text-center bg-transparent font-bold text-sm focus:outline-none">
                                <button type="button" @click="if(qty < max) qty++" class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center font-bold text-slate-700 hover:bg-slate-100">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>

                            <button type="submit" class="w-full sm:flex-grow py-3.5 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm transition shadow-md flex items-center justify-center gap-2">
                                <i class="fas fa-cart-plus"></i>
                                <span>Tambah ke Keranjang</span>
                            </button>
                        </form>
                    @else
                        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center gap-2">
                            <i class="fas fa-circle-exclamation text-base"></i>
                            <span>Maaf, menu ini saat ini sedang habis terjual. Silakan pilih menu lain dari stand ini.</span>
                        </div>
                    @endif

                </div>

            </div>
        </div>

        <!-- Related Products from Same Stand -->
        @if($relatedProducts->count() > 0)
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900">
                        Menu Lain di {{ $product->stand->name }}
                    </h3>
                    <a href="{{ route('stands.show', $product->stand_id) }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800">
                        Lihat Stand Ini <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $rel)
                        <div class="bg-white rounded-3xl border border-slate-200 p-4 hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                <img src="{{ $rel->image ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400' }}" alt="{{ $rel->name }}" class="w-full h-32 object-cover rounded-2xl mb-3">
                                <h4 class="font-bold text-sm text-slate-800 leading-tight">{{ $rel->name }}</h4>
                                <p class="text-xs text-emerald-600 font-extrabold mt-1">{{ $rel->formatted_price }}</p>
                            </div>
                            <a href="{{ route('menu.show', $rel->id) }}" class="mt-3 block text-center py-2 bg-slate-100 hover:bg-emerald-600 hover:text-white text-slate-700 font-bold text-xs rounded-xl transition">
                                Lihat Menu
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

@endsection

