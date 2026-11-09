@extends('layouts.penjaga')

@section('title', 'Tambah Produk Baru - Panel Penjaga')
@section('page_title', 'Tambah Produk Baru')

@section('content')

    <div class="max-w-2xl mx-auto space-y-6">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('penjaga.products.index') }}" class="hover:text-emerald-600 transition font-semibold">Produk Saya</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-semibold">Tambah Produk Baru</span>
        </nav>

        {{-- Form Card --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">

            {{-- Card Header --}}
            <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-plus text-emerald-500 text-sm"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Tambah Produk Baru</h2>
                    <p class="text-[11px] text-slate-400 mt-0.5">Produk akan otomatis terdaftar di Stand Anda.</p>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('penjaga.products.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="px-6 py-6 space-y-5">
                @csrf

                {{-- Nama Produk --}}
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Nama Produk <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Contoh: Nasi Goreng Spesial"
                           required
                           class="w-full px-4 py-2.5 rounded-xl border text-sm text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-400
                                  {{ $errors->has('name') ? 'border-rose-300 bg-rose-50' : 'border-slate-200 bg-slate-50 focus:bg-white' }}">
                    @error('name')
                        <p class="mt-1.5 text-xs text-rose-500 flex items-center gap-1">
                            <i class="fas fa-circle-exclamation text-[10px]"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="category_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select id="category_id"
                            name="category_id"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-400
                                   {{ $errors->has('category_id') ? 'border-rose-300 bg-rose-50' : 'border-slate-200 bg-slate-50 focus:bg-white' }}">
                        <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>— Pilih Kategori —</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1.5 text-xs text-rose-500 flex items-center gap-1">
                            <i class="fas fa-circle-exclamation text-[10px]"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Harga & Stok (2 kolom) --}}
                <div class="grid grid-cols-2 gap-4">

                    {{-- Harga --}}
                    <div>
                        <label for="price" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Harga (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs font-semibold pointer-events-none">Rp</span>
                            <input type="number"
                                   id="price"
                                   name="price"
                                   value="{{ old('price') }}"
                                   min="500"
                                   step="500"
                                   placeholder="5000"
                                   required
                                   class="w-full pl-9 pr-4 py-2.5 rounded-xl border text-sm text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-400
                                          {{ $errors->has('price') ? 'border-rose-300 bg-rose-50' : 'border-slate-200 bg-slate-50 focus:bg-white' }}">
                        </div>
                        @error('price')
                            <p class="mt-1.5 text-xs text-rose-500 flex items-center gap-1">
                                <i class="fas fa-circle-exclamation text-[10px]"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Stok --}}
                    <div>
                        <label for="stock" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Stok Awal <span class="text-rose-500">*</span>
                        </label>
                        <input type="number"
                               id="stock"
                               name="stock"
                               value="{{ old('stock', 0) }}"
                               min="0"
                               required
                               class="w-full px-4 py-2.5 rounded-xl border text-sm text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-400
                                      {{ $errors->has('stock') ? 'border-rose-300 bg-rose-50' : 'border-slate-200 bg-slate-50 focus:bg-white' }}">
                        @error('stock')
                            <p class="mt-1.5 text-xs text-rose-500 flex items-center gap-1">
                                <i class="fas fa-circle-exclamation text-[10px]"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Status <span class="text-rose-500">*</span>
                    </label>
                    <select id="status"
                            name="status"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-400
                                   {{ $errors->has('status') ? 'border-rose-300 bg-rose-50' : 'border-slate-200 bg-slate-50 focus:bg-white' }}">
                        <option value="available" {{ old('status', 'available') === 'available' ? 'selected' : '' }}>
                            ✅ Tersedia
                        </option>
                        <option value="unavailable" {{ old('status') === 'unavailable' ? 'selected' : '' }}>
                            ❌ Tidak Tersedia
                        </option>
                    </select>
                    <p class="mt-1.5 text-[11px] text-slate-400">
                        <i class="fas fa-circle-info mr-1"></i>
                        Jika stok diisi 0, status akan otomatis menjadi "Tidak Tersedia".
                    </p>
                    @error('status')
                        <p class="mt-1 text-xs text-rose-500 flex items-center gap-1">
                            <i class="fas fa-circle-exclamation text-[10px]"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="description" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Deskripsi
                        <span class="text-slate-400 font-normal">(opsional)</span>
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="3"
                              placeholder="Tuliskan deskripsi singkat produk, bahan, atau keterangan lainnya…"
                              class="w-full px-4 py-2.5 rounded-xl border text-sm text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 resize-none
                                     {{ $errors->has('description') ? 'border-rose-300 bg-rose-50' : 'border-slate-200 bg-slate-50 focus:bg-white' }}">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-rose-500 flex items-center gap-1">
                            <i class="fas fa-circle-exclamation text-[10px]"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Gambar Produk --}}
                <div>
                    <label for="image" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Gambar Produk
                        <span class="text-slate-400 font-normal">(opsional)</span>
                    </label>

                    {{-- Preview area --}}
                    <div x-data="imagePreview()"
                         class="space-y-3">

                        {{-- Drop zone / preview --}}
                        <div class="relative w-full">
                            <label for="image"
                                   class="flex flex-col items-center justify-center gap-2 w-full h-36 rounded-xl border-2 border-dashed cursor-pointer transition
                                          {{ $errors->has('image') ? 'border-rose-300 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:bg-emerald-50 hover:border-emerald-300' }}">

                                <template x-if="!preview">
                                    <div class="flex flex-col items-center gap-2 text-slate-400">
                                        <i class="fas fa-cloud-arrow-up text-2xl"></i>
                                        <span class="text-xs font-semibold">Klik untuk memilih gambar</span>
                                        <span class="text-[11px]">JPG, PNG, atau WebP — maks. 2 MB</span>
                                    </div>
                                </template>

                                <template x-if="preview">
                                    <img :src="preview" alt="Preview" class="h-full w-full object-contain rounded-xl p-1">
                                </template>

                                <input type="file"
                                       id="image"
                                       name="image"
                                       accept="image/jpeg,image/png,image/webp"
                                       class="absolute inset-0 opacity-0 cursor-pointer"
                                       @change="showPreview($event)">
                            </label>
                        </div>

                        {{-- Remove preview button --}}
                        <template x-if="preview">
                            <button type="button" @click="clearPreview()"
                                    class="text-[11px] text-rose-500 hover:text-rose-700 font-semibold flex items-center gap-1 transition">
                                <i class="fas fa-xmark text-[10px]"></i>
                                Hapus gambar yang dipilih
                            </button>
                        </template>

                    </div>

                    @error('image')
                        <p class="mt-1.5 text-xs text-rose-500 flex items-center gap-1">
                            <i class="fas fa-circle-exclamation text-[10px]"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Divider --}}
                <hr class="border-slate-100">

                {{-- Action Buttons --}}
                <div class="flex items-center justify-between gap-3 pt-1">
                    <a href="{{ route('penjaga.products.index') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-xs font-bold transition">
                        <i class="fas fa-arrow-left text-[10px]"></i>
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition">
                        <i class="fas fa-floppy-disk text-[11px]"></i>
                        Simpan Produk
                    </button>
                </div>

            </form>
        </div>

    </div>

@endsection

@push('scripts')
<script>
    function imagePreview() {
        return {
            preview: null,
            fileInput: null,
            showPreview(event) {
                const file = event.target.files[0];
                if (!file) return;
                this.fileInput = event.target;
                const reader = new FileReader();
                reader.onload = (e) => { this.preview = e.target.result; };
                reader.readAsDataURL(file);
            },
            clearPreview() {
                this.preview = null;
                if (this.fileInput) {
                    this.fileInput.value = '';
                }
            }
        };
    }
</script>
@endpush
