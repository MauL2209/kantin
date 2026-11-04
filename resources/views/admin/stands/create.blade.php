@extends('layouts.admin')

@section('title', 'Tambah Stand Baru - Panel Admin')
@section('page_title', 'Tambah Stand Kantin')

@section('content')

    <div class="max-w-2xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('admin.stands.index') }}" class="text-xs font-bold text-slate-500 hover:text-emerald-700 flex items-center gap-1.5 transition">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Stand
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Form Tambah Stand Kantin</h2>
                <p class="text-xs text-slate-400">Masukkan detail stand jurusan yang akan dibuka di kantin sekolah.</p>
            </div>

            <form action="{{ route('admin.stands.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Nama Stand <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Stand Rekayasa Perangkat Lunak" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    @error('name') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Jurusan <span class="text-rose-500">*</span></label>
                    <input type="text" name="department" value="{{ old('department') }}" required placeholder="Contoh: Rekayasa Perangkat Lunak / Bisnis Digital" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    @error('department') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Lokasi / Titik Stand</label>
                        <input type="text" name="location" value="{{ old('location') }}" placeholder="Contoh: Zona A - No. 01" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Penanggung Jawab / Kontak</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person') }}" placeholder="Contoh: Pak Dian (0812-xxxx)" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Deskripsi Stand</label>
                    <textarea name="description" rows="3" placeholder="Deskripsikan menu khas dan keunggulan stand jurusan ini..." 
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Status Operasional Awal</label>
                        <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50">
                            <option value="open">Buka (Aktif)</option>
                            <option value="closed">Tutup (Nonaktif)</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">URL Gambar (Opsional)</label>
                        <input type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://..." 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Atau Unggah Foto Stand (File)</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="{{ route('admin.stands.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-100 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-sm">
                        Simpan Stand
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

