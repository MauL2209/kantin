@extends('layouts.app')

@section('title', 'Pendaftaran Akun Siswa - Kantin Sekolah MELATIAREA')

@section('content')

    <div class="max-w-lg mx-auto px-4 py-12">
        
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl overflow-hidden p-6 sm:p-8 space-y-6">
            
            <div class="text-center space-y-2">
                <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs uppercase tracking-wider">
                    Akun Siswa Baru
                </span>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Daftar Akun Siswa</h1>
                <p class="text-xs text-slate-500">Buat akun untuk mempermudah pesanan kantin setiap hari.</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Nama Lengkap -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Muhammad Rizky Pratama" 
                           class="w-full px-4 py-2.5 rounded-xl border @error('name') border-rose-400 @else border-slate-200 @enderror text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    @error('name') <p class="text-[11px] text-rose-500 mt-0.5">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="rizky@melatiarea.sch.id" 
                           class="w-full px-4 py-2.5 rounded-xl border @error('email') border-rose-400 @else border-slate-200 @enderror text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    @error('email') <p class="text-[11px] text-rose-500 mt-0.5">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- NIS -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">NIS (Nomor Induk Siswa)</label>
                        <input type="text" name="nis" value="{{ old('nis') }}" placeholder="20241001" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    </div>

                    <!-- Kelas -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Kelas / Jurusan</label>
                        <input type="text" name="class_room" value="{{ old('class_room') }}" placeholder="XII RPL 1" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    </div>
                </div>

                <!-- No HP / WhatsApp -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Nomor WhatsApp / HP</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="081234567890" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Password -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required placeholder="Minimal 6 karakter" 
                               class="w-full px-4 py-2.5 rounded-xl border @error('password') border-rose-400 @else border-slate-200 @enderror text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                        @error('password') <p class="text-[11px] text-rose-500 mt-0.5">{{ $message }}</p> @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Ulangi Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password_confirmation" required placeholder="Ulangi password" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    </div>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm transition shadow-md shadow-emerald-600/30">
                    Daftar Sekarang
                </button>
            </form>

            <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-100">
                Sudah memiliki akun siswa? 
                <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:underline">
                    Masuk di sini
                </a>
            </div>

        </div>

    </div>

@endsection

