@extends('layouts.admin')

@section('title', 'Kategori Menu - Panel Admin')
@section('page_title', 'Kelola Kategori Menu')

@section('content')

    <div class="space-y-6">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left: Add New Category Form -->
            <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Tambah Kategori Baru</h3>
                    <p class="text-xs text-slate-400">Buat klasifikasi menu baru (cth: Makanan, Minuman, Snack, dll.)</p>
                </div>

                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-3.5">
                    @csrf

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Nama Kategori <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Paket Hemat / Minuman Dingin" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                        @error('name') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Deskripsi Singkat</label>
                        <textarea name="description" rows="2" placeholder="Keterangan kategori..." 
                                  class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-sm">
                        Simpan Kategori
                    </button>
                </form>
            </div>

            <!-- Right: Categories List Table -->
            <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Daftar Kategori Menu</h3>
                    <p class="text-xs text-slate-400">Kelola kelompok menu makanan dan minuman kantin.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-100">
                                <th class="py-3 px-3">Nama Kategori</th>
                                <th class="py-3 px-3">Slug</th>
                                <th class="py-3 px-3">Deskripsi</th>
                                <th class="py-3 px-3 text-center">Jumlah Menu</th>
                                <th class="py-3 px-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($categories as $cat)
                                <tr class="hover:bg-slate-50 transition" x-data="{ editing: false, name: '{{ $cat->name }}', desc: '{{ $cat->description }}' }">
                                    <td class="py-3 px-3 font-bold text-slate-900">
                                        <template x-if="!editing">
                                            <span>{{ $cat->name }}</span>
                                        </template>
                                        <template x-if="editing">
                                            <input type="text" x-model="name" class="w-full px-2 py-1 text-xs border rounded">
                                        </template>
                                    </td>
                                    <td class="py-3 px-3 font-mono text-[11px] text-slate-400">
                                        {{ $cat->slug }}
                                    </td>
                                    <td class="py-3 px-3 text-slate-500 text-[11px]">
                                        <template x-if="!editing">
                                            <span>{{ $cat->description ?? '-' }}</span>
                                        </template>
                                        <template x-if="editing">
                                            <input type="text" x-model="desc" class="w-full px-2 py-1 text-xs border rounded">
                                        </template>
                                    </td>
                                    <td class="py-3 px-3 text-center font-bold text-slate-800">
                                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-[11px]">
                                            {{ $cat->products_count }} Produk
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <template x-if="!editing">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button @click="editing = true" class="p-1.5 bg-amber-50 hover:bg-amber-100 rounded text-amber-700 transition" title="Edit">
                                                    <i class="fas fa-pencil text-xs"></i>
                                                </button>
                                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-100 rounded text-rose-700 transition" title="Hapus">
                                                        <i class="fas fa-trash-can text-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </template>
                                        <template x-if="editing">
                                            <form action="{{ route('admin.categories.update', $cat->id) }}" method="POST" class="flex items-center justify-center gap-1">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="name" :value="name">
                                                <input type="hidden" name="description" :value="desc">
                                                <button type="submit" class="p-1.5 bg-emerald-600 text-white rounded text-xs" title="Simpan">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" @click="editing = false" class="p-1.5 bg-slate-200 text-slate-700 rounded text-xs" title="Batal">
                                                    <i class="fas fa-xmark"></i>
                                                </button>
                                            </form>
                                        </template>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

@endsection

