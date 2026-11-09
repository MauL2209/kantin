@extends('layouts.penjaga')

@section('title', 'Produk Saya - Panel Penjaga')
@section('page_title', 'Produk Saya')

@section('content')

    <div class="space-y-6">

        {{-- Header & Tambah Produk --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
            <div>
                <h2 class="text-base font-bold text-slate-900">Daftar Produk Stand Anda</h2>
                <p class="text-xs text-slate-400 mt-0.5">Kelola menu, stok, dan ketersediaan produk yang dijual di stand Anda.</p>
            </div>
            <a href="{{ route('penjaga.products.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition flex-shrink-0">
                <i class="fas fa-plus"></i>
                Tambah Produk Baru
            </a>
        </div>

        {{-- Tabel Produk --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">

            @if($products->isEmpty())
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center py-20 text-center px-6">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center mb-4">
                        <i class="fas fa-utensils text-2xl text-emerald-400"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-700 mb-1">Belum Ada Produk</h3>
                    <p class="text-sm text-slate-400 max-w-xs mb-5">Stand Anda belum memiliki produk. Mulai tambahkan menu makanan atau minuman sekarang.</p>
                    <a href="{{ route('penjaga.products.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition">
                        <i class="fas fa-plus"></i>
                        Tambah Produk Pertama
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-100 bg-slate-50/70">
                                <th class="py-3.5 px-4">Produk</th>
                                <th class="py-3.5 px-4">Kategori</th>
                                <th class="py-3.5 px-4 text-right">Harga</th>
                                <th class="py-3.5 px-4 text-center">Stok</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($products as $product)
                                <tr class="hover:bg-slate-50/60 transition">

                                    {{-- Produk (gambar + nama + deskripsi) --}}
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            @if($product->image)
                                                <img src="{{ $product->image }}"
                                                     alt="{{ $product->name }}"
                                                     class="w-12 h-12 rounded-xl object-cover border border-slate-200 flex-shrink-0">
                                            @else
                                                <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-utensils text-emerald-400 text-sm"></i>
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <p class="font-bold text-slate-900 text-sm leading-tight truncate max-w-[180px]">
                                                    {{ $product->name }}
                                                </p>
                                                @if($product->description)
                                                    <p class="text-[11px] text-slate-400 truncate max-w-[180px] mt-0.5">
                                                        {{ $product->description }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kategori --}}
                                    <td class="py-4 px-4">
                                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-[11px] font-semibold">
                                            {{ $product->category->name ?? '-' }}
                                        </span>
                                    </td>

                                    {{-- Harga --}}
                                    <td class="py-4 px-4 text-right font-mono font-bold text-slate-900 text-sm whitespace-nowrap">
                                        {{ $product->formatted_price }}
                                    </td>

                                    {{-- Stok (quick update inline) --}}
                                    <td class="py-4 px-4 text-center">
                                        <form action="{{ route('penjaga.products.stock', $product->id) }}" method="POST"
                                              class="inline-flex items-center gap-1.5">
                                            @csrf
                                            <input type="number" name="stock" value="{{ $product->stock }}" min="0"
                                                   class="w-16 text-center px-1.5 py-1.5 text-xs font-bold rounded-lg border focus:outline-none focus:ring-2 focus:ring-emerald-400
                                                          {{ $product->stock <= 0
                                                             ? 'bg-rose-50 text-rose-700 border-rose-300'
                                                             : 'bg-slate-50 text-slate-800 border-slate-200' }}">
                                            <button type="submit"
                                                    class="p-1.5 bg-slate-100 hover:bg-emerald-600 hover:text-white rounded-lg text-slate-600 transition"
                                                    title="Simpan Stok">
                                                <i class="fas fa-check text-[10px]"></i>
                                            </button>
                                        </form>
                                    </td>

                                    {{-- Status Badge --}}
                                    <td class="py-4 px-4 text-center">
                                        @if($product->status === 'available')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px] whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                                Tersedia
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 font-bold text-[10px] whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 flex-shrink-0"></span>
                                                Tidak Tersedia
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="py-4 px-4 text-center">
                                        <div class="inline-flex items-center gap-1.5">
                                            {{-- Edit --}}
                                            <a href="{{ route('penjaga.products.edit', $product->id) }}"
                                               class="p-2 bg-amber-50 hover:bg-amber-100 rounded-lg text-amber-700 transition"
                                               title="Edit Produk">
                                                <i class="fas fa-pencil text-xs"></i>
                                            </a>

                                            {{-- Hapus --}}
                                            <form action="{{ route('penjaga.products.destroy', $product->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus produk \'{{ addslashes($product->name) }}\'? Tindakan ini tidak dapat dibatalkan.')"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="p-2 bg-rose-50 hover:bg-rose-100 rounded-lg text-rose-700 transition"
                                                        title="Hapus Produk">
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

                {{-- Pagination --}}
                @if($products->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $products->links() }}
                    </div>
                @endif

            @endif
        </div>

        {{-- Ringkasan Stok --}}
        @if($products->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                    <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wide mb-1">Total Produk</p>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $products->total() }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4">
                    <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wide mb-1">Tersedia</p>
                    <p class="text-2xl font-extrabold text-emerald-600">
                        {{ $products->getCollection()->where('status', 'available')->count() }}
                    </p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 col-span-2 sm:col-span-1">
                    <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wide mb-1">Tidak Tersedia</p>
                    <p class="text-2xl font-extrabold text-rose-500">
                        {{ $products->getCollection()->where('status', 'unavailable')->count() }}
                    </p>
                </div>
            </div>
        @endif

    </div>

@endsection
