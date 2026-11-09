@extends('layouts.admin')

@section('title', 'Edit Stand ' . $stand->name . ' - Panel Admin')
@section('page_title', 'Edit Stand Kantin')

@section('content')

    <div class="max-w-2xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('admin.stands.index') }}" class="text-xs font-bold text-slate-500 hover:text-emerald-700 flex items-center gap-1.5 transition">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Stand
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Perbarui Stand Kantin</h2>
                <p class="text-xs text-slate-400">Edit informasi operasional dan profil stand jurusan {{ $stand->department }}.</p>
            </div>

            <form action="{{ route('admin.stands.update', $stand->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Nama Stand <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $stand->name) }}" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    @error('name') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Jurusan <span class="text-rose-500">*</span></label>
                    <input type="text" name="department" value="{{ old('department', $stand->department) }}" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    @error('department') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Lokasi / Titik Stand</label>
                        <input type="text" name="location" value="{{ old('location', $stand->location) }}" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Penanggung Jawab / Kontak</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person', $stand->contact_person) }}" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Deskripsi Stand</label>
                    <textarea name="description" rows="3" 
                              class="w-full px-4 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">{{ old('description', $stand->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Status Operasional</label>
                        <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50">
                            <option value="open" {{ $stand->status === 'open' ? 'selected' : '' }}>Buka (Aktif)</option>
                            <option value="closed" {{ $stand->status === 'closed' ? 'selected' : '' }}>Tutup (Nonaktif)</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">URL Gambar</label>
                        <input type="text" name="image_url" value="{{ old('image_url', $stand->image) }}" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Ganti Foto Stand (Unggah Baru)</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                </div>

                @if($stand->image)
                    <div class="p-3 bg-slate-50 rounded-xl flex items-center gap-3">
                        <img src="{{ $stand->image }}" alt="" class="w-12 h-12 rounded-lg object-cover">
                        <span class="text-xs text-slate-500">Foto stand saat ini aktif ditampilkan.</span>
                    </div>
                @endif

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="{{ route('admin.stands.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-100 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-sm">
                        Perbarui Stand
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

