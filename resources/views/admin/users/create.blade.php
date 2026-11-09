@extends('layouts.admin')

@section('title', 'Tambah Akun Pengguna - Panel Admin')
@section('page_title', 'Tambah Akun Pengguna')

@section('content')

    <div class="max-w-2xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-slate-500 hover:text-emerald-700 flex items-center gap-1.5 transition">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pengguna
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Form Tambah Akun Pengguna</h2>
                <p class="text-xs text-slate-400">Buat akun baru untuk Siswa, Penjaga Kantin, atau Administrator.</p>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Nama --}}
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Budi Santoso"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    @error('name') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           placeholder="Contoh: budi@smkn20jkt.sch.id"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    @error('email') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Password & Konfirmasi --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required minlength="8"
                               placeholder="Minimal 8 karakter"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                        @error('password') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Konfirmasi Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password_confirmation" required minlength="8"
                               placeholder="Ulangi password"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                        @error('password_confirmation') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Role --}}
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Role <span class="text-rose-500">*</span></label>
                    <select name="role" id="roleSelect" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50">
                        <option value="">— Pilih Role —</option>
                        <option value="siswa"   {{ old('role') === 'siswa'   ? 'selected' : '' }}>Siswa</option>
                        <option value="penjaga" {{ old('role') === 'penjaga' ? 'selected' : '' }}>Penjaga Kantin</option>
                        <option value="admin"   {{ old('role') === 'admin'   ? 'selected' : '' }}>Administrator</option>
                    </select>
                    @error('role') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Stand (visible only for role = penjaga) --}}
                <div id="standField" class="space-y-1 {{ old('role') === 'penjaga' ? '' : 'hidden' }}">
                    <label class="block text-xs font-bold text-slate-700">
                        Stand yang Ditugaskan <span class="text-rose-500">*</span>
                    </label>
                    <select name="stand_id" id="standSelect"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50">
                        <option value="">— Pilih Stand —</option>
                        @foreach($stands as $stand)
                            <option value="{{ $stand->id }}" {{ old('stand_id') == $stand->id ? 'selected' : '' }}>
                                {{ $stand->name }}@if($stand->department) ({{ $stand->department }})@endif
                            </option>
                        @endforeach
                    </select>
                    @error('stand_id') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                    <p class="text-[11px] text-slate-400">Penjaga hanya dapat ditugaskan ke satu stand.</p>
                </div>

                {{-- Info fields: NIS & Kelas (opsional, tampil untuk semua — hanya relevan untuk siswa) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">NIS <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="nis" value="{{ old('nis') }}"
                               placeholder="Contoh: 2021001234"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                        @error('nis') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Kelas <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="class_room" value="{{ old('class_room') }}"
                               placeholder="Contoh: XII RPL 1"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                        @error('class_room') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Nomor Telepon --}}
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Nomor Telepon <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="Contoh: 08123456789"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    @error('phone') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Actions --}}
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="{{ route('admin.users.index') }}"
                       class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-100 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-sm">
                        <i class="fas fa-user-plus mr-1.5"></i> Buat Akun
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    (function () {
        const roleSelect  = document.getElementById('roleSelect');
        const standField  = document.getElementById('standField');
        const standSelect = document.getElementById('standSelect');

        function toggleStand() {
            const isPenjaga = roleSelect.value === 'penjaga';
            standField.classList.toggle('hidden', !isPenjaga);
            // Make stand_id required only when visible
            standSelect.required = isPenjaga;
        }

        roleSelect.addEventListener('change', toggleStand);

        // Run once on page load to handle old() repopulation
        toggleStand();
    })();
</script>
@endpush
