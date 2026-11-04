@extends('layouts.admin')

@section('title', 'Edit Menu ' . $product->name . ' - Panel Admin')
@section('page_title', 'Edit Menu Kantin')

@section('content')

    <div class="max-w-2xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-slate-500 hover:text-emerald-700 flex items-center gap-1.5 transition">
                <i class="fas fa-arrow-left"></i> Kembali ke Katalog Menu
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Perbarui Menu & Harga</h2>
                <p class="text-xs text-slate-400">Edit data menu untuk stand {{ $product->stand->name ?? '' }}.</p>
            </div>

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Nama Menu <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    @error('name') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Stand Pemilik <span class="text-rose-500">*</span></label>
                        <select name="stand_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50">
                            @foreach($stands as $stand)
                                <option value="{{ $stand->id }}" {{ old('stand_id', $product->stand_id) == $stand->id ? 'selected' : '' }}>
                                    {{ $stand->name }} ({{ $stand->department }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Kategori Menu <span class="text-rose-500">*</span></label>
                        <select name="category_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Harga Satuan (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" min="500" step="500" required 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Stok Porsi Tersedia <span class="text-rose-500">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Deskripsi Menu</label>
                    <textarea name="description" rows="3" 
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Status Ketersediaan</label>
                        <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50">
                            <option value="available" {{ $product->status === 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="unavailable" {{ $product->status === 'unavailable' ? 'selected' : '' }}>Tidak Tersedia / Habis</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">URL Gambar</label>
                        <input type="url" name="image_url" value="{{ old('image_url', $product->image) }}" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Ganti Foto Menu (Unggah File)</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                </div>

                @if($product->image)
                    <div class="p-3 bg-slate-50 rounded-xl flex items-center gap-3">
                        <img src="{{ $product->image }}" alt="" class="w-12 h-12 rounded-lg object-cover">
                        <span class="text-xs text-slate-500">Foto saat ini aktif.</span>
                    </div>
                @endif

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded text-brand-600 focus:ring-brand-500">
                        <span>Jadikan sebagai <strong>Menu Favorit Siswa</strong></span>
                    </label>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-100 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-sm">
                        Perbarui Menu
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

