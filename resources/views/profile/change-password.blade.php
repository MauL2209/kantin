@extends('layouts.app')

@section('title', 'Ubah Password - Kantin MELATIAREA')

@section('content')

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-1">
            <a href="{{ route('profile.show') }}"
               class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition">
                <i class="fas fa-arrow-left text-slate-500 text-xs"></i>
            </a>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Ubah Password</h1>
        </div>
        <p class="text-sm text-slate-500 mt-1 ml-11">Perbarui kata sandi akun Anda untuk menjaga keamanan.</p>
    </div>

    {{-- Success Flash Message --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check text-sm"></i>
            </div>
            <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Card --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">

        <div class="mb-6 pb-4 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-900">Ganti Kata Sandi</h2>
            <p class="text-xs text-slate-400 mt-0.5">Isi semua field di bawah untuk memperbarui password Anda.</p>
        </div>

        {{-- Validation Error Summary --}}
        @if($errors->any() && !$errors->has('current_password') && !$errors->has('new_password') && !$errors->has('new_password_confirmation'))
            <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation text-[10px]"></i>
                    </div>
                    <p class="text-xs font-bold text-rose-800">Terdapat kesalahan pada form:</p>
                </div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li class="text-xs text-rose-700">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Current Password --}}
            <div class="space-y-1.5">
                <label for="current_password" class="block text-xs font-bold text-slate-700">
                    Password Lama <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="password"
                           id="current_password"
                           name="current_password"
                           required
                           autocomplete="current-password"
                           placeholder="Masukkan password lama Anda"
                           class="w-full px-4 py-2.5 rounded-xl border @error('current_password') border-rose-400 bg-rose-50 @else border-slate-200 bg-slate-50 @enderror text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                </div>
                @error('current_password')
                    <p class="text-[11px] text-rose-500 flex items-center gap-1 mt-0.5">
                        <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Divider --}}
            <div class="border-t border-slate-100"></div>

            {{-- New Password --}}
            <div class="space-y-1.5">
                <label for="new_password" class="block text-xs font-bold text-slate-700">
                    Password Baru <span class="text-rose-500">*</span>
                </label>
                <input type="password"
                       id="new_password"
                       name="new_password"
                       required
                       autocomplete="new-password"
                       placeholder="Minimal 8 karakter"
                       class="w-full px-4 py-2.5 rounded-xl border @error('new_password') border-rose-400 bg-rose-50 @else border-slate-200 bg-slate-50 @enderror text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                @error('new_password')
                    <p class="text-[11px] text-rose-500 flex items-center gap-1 mt-0.5">
                        <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                    </p>
                @else
                    <p class="text-[11px] text-slate-400">Gunakan minimal 8 karakter yang kuat.</p>
                @enderror
            </div>

            {{-- Confirm New Password --}}
            <div class="space-y-1.5">
                <label for="new_password_confirmation" class="block text-xs font-bold text-slate-700">
                    Konfirmasi Password Baru <span class="text-rose-500">*</span>
                </label>
                <input type="password"
                       id="new_password_confirmation"
                       name="new_password_confirmation"
                       required
                       autocomplete="new-password"
                       placeholder="Ulangi password baru Anda"
                       class="w-full px-4 py-2.5 rounded-xl border @error('new_password_confirmation') border-rose-400 bg-rose-50 @else border-slate-200 bg-slate-50 @enderror text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                @error('new_password_confirmation')
                    <p class="text-[11px] text-rose-500 flex items-center gap-1 mt-0.5">
                        <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between gap-3 pt-2">
                <a href="{{ route('profile.show') }}"
                   class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                    <i class="fas fa-arrow-left mr-1.5"></i> Kembali ke Profil
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-emerald-600/20 hover:shadow-emerald-600/30 transition">
                    <i class="fas fa-key mr-1.5"></i> Simpan Password Baru
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
