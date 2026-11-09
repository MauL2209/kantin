@extends('layouts.admin')

@section('title', 'Edit Akun ' . $user->name . ' - Panel Admin')
@section('page_title', 'Edit Akun Pengguna')

@section('content')

    <div class="max-w-2xl mx-auto space-y-6">
        <div class="mb-4">
            <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-slate-500 hover:text-emerald-700 flex items-center gap-1.5 transition">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pengguna
            </a>
        </div>

        {{-- ================================================================= --}}
        {{-- SECTION 1: FORM EDIT DATA PENGGUNA                                --}}
        {{-- ================================================================= --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Perbarui Data Akun</h2>
                <p class="text-xs text-slate-400">Edit informasi akun untuk pengguna <span class="font-semibold text-slate-600">{{ $user->email }}</span>.</p>
            </div>

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    @error('name') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    @error('email') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Role --}}
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Role / Hak Akses <span class="text-rose-500">*</span></label>
                    <select name="role" id="role-select" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50">
                        <option value="siswa"   {{ old('role', $user->role) === 'siswa'   ? 'selected' : '' }}>Siswa</option>
                        <option value="penjaga" {{ old('role', $user->role) === 'penjaga' ? 'selected' : '' }}>Penjaga Kantin</option>
                        <option value="admin"   {{ old('role', $user->role) === 'admin'   ? 'selected' : '' }}>Administrator</option>
                    </select>
                    @error('role') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Stand (visible only when role = penjaga) --}}
                <div id="stand-field" class="space-y-1" style="{{ old('role', $user->role) !== 'penjaga' ? 'display:none;' : '' }}">
                    <label class="block text-xs font-bold text-slate-700">Stand yang Ditugaskan <span class="text-rose-500">*</span></label>
                    <select name="stand_id"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50">
                        <option value="">-- Pilih Stand --</option>
                        @foreach($stands as $stand)
                            <option value="{{ $stand->id }}" {{ old('stand_id', $user->stand_id) == $stand->id ? 'selected' : '' }}>
                                {{ $stand->name }}@if($stand->department) ({{ $stand->department }})@endif
                            </option>
                        @endforeach
                    </select>
                    @error('stand_id') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- NIS & Kelas --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">NIS</label>
                        <input type="text" name="nis" value="{{ old('nis', $user->nis) }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                        @error('nis') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Kelas</label>
                        <input type="text" name="class_room" value="{{ old('class_room', $user->class_room) }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                        @error('class_room') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Telepon --}}
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    @error('phone') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Buttons --}}
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <a href="{{ route('admin.users.index') }}"
                       class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-100 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-sm">
                        Perbarui Akun
                    </button>
                </div>

            </form>
        </div>

        {{-- ================================================================= --}}
        {{-- SECTION 2: RESET PASSWORD (FORM TERPISAH)                         --}}
        {{-- ================================================================= --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Reset Password</h2>
                <p class="text-xs text-slate-400">Atur password baru untuk akun ini. Admin tidak perlu mengetahui password lama.</p>
            </div>

            <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST" class="space-y-4">
                @csrf

                {{-- Password Baru --}}
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Password Baru <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required minlength="8"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    <p class="text-xs text-slate-400">Minimal 8 karakter.</p>
                    @error('password') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Konfirmasi Password Baru --}}
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Konfirmasi Password Baru <span class="text-rose-500">*</span></label>
                    <input type="password" name="password_confirmation" required minlength="8"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    @error('password_confirmation') <p class="text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                {{-- Button --}}
                <div class="pt-4 flex items-center justify-end border-t border-slate-100">
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs transition shadow-sm">
                        <i class="fas fa-key mr-1.5"></i> Reset Password
                    </button>
                </div>

            </form>
        </div>

    </div>

@endsection

@push('scripts')
<script>
    (function () {
        const roleSelect  = document.getElementById('role-select');
        const standField  = document.getElementById('stand-field');
        const standSelect = standField ? standField.querySelector('select') : null;

        function toggleStand() {
            const isPenjaga = roleSelect.value === 'penjaga';
            standField.style.display = isPenjaga ? '' : 'none';

            // Remove required when hidden to avoid browser-native validation blocking submit
            if (standSelect) {
                standSelect.required = isPenjaga;
            }
        }

        // Run on page load and on every change
        toggleStand();
        roleSelect.addEventListener('change', toggleStand);
    })();
</script>
@endpush
