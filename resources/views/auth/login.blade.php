@extends('layouts.app')

@section('title', 'Login Pengguna - Kantin Sekolah MELATIAREA')

@section('content')

    <div class="max-w-md mx-auto px-4 py-16">
        
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl overflow-hidden p-6 sm:p-8 space-y-6">
            
            <!-- Header & Branding -->
            <div class="text-center space-y-2">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-600 to-teal-500 p-0.5 mx-auto flex items-center justify-center shadow-md">
                    <div class="w-full h-full bg-white rounded-xl flex flex-col items-center justify-center p-1">
                        <span class="text-[9px] font-black text-brand-700 leading-none">LOGO</span>
                        <span class="text-[8px] font-bold text-slate-600 leading-none mt-0.5">SEKOLAH</span>
                    </div>
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Masuk ke Akun</h1>
                <p class="text-xs text-slate-500">Pilih akunmu untuk mulai memesan atau mengelola kantin.</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                            <i class="far fa-envelope text-xs"></i>
                        </span>
                        <input type="email" id="emailInput" name="email" value="{{ old('email') }}" required placeholder="nama@melatiarea.sch.id" 
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border @error('email') border-rose-400 @else border-slate-200 @enderror text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    </div>
                    @error('email') <p class="text-[11px] text-rose-500 mt-0.5">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div class="space-y-1">
                    <div class="flex justify-between items-center">
                        <label class="block text-xs font-bold text-slate-700">Password</label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                            <i class="fas fa-lock text-xs"></i>
                        </span>
                        <input type="password" id="passwordInput" name="password" required placeholder="••••••••" 
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50 focus:bg-white">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" class="rounded text-brand-600 focus:ring-brand-500">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm transition shadow-md shadow-emerald-600/30">
                    Masuk Sekarang
                </button>
            </form>

            <!-- Quick Demo Credentials Box -->
            <div class="pt-4 border-t border-slate-100 space-y-3">
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center">
                    Coba Akun Demo Cepat (1-Klik)
                </span>

                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button type="button" onclick="fillCredentials('admin@melatiarea.sch.id', 'password123')" 
                            class="p-2.5 rounded-xl border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-900 font-bold transition text-center space-y-0.5">
                        <span class="block text-xs"><i class="fas fa-shield-alt text-amber-600"></i> Admin Kantin</span>
                        <span class="text-[10px] text-amber-700/80 font-normal">Klik untuk isi form</span>
                    </button>

                    <button type="button" onclick="fillCredentials('siswa@melatiarea.sch.id', 'password123')" 
                            class="p-2.5 rounded-xl border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-emerald-900 font-bold transition text-center space-y-0.5">
                        <span class="block text-xs"><i class="fas fa-graduation-cap text-emerald-600"></i> Akun Siswa</span>
                        <span class="text-[10px] text-emerald-700/80 font-normal">Klik untuk isi form</span>
                    </button>
                </div>
            </div>

            <!-- Register Link -->
            <div class="text-center text-xs text-slate-500 pt-2">
                Belum punya akun siswa? 
                <a href="{{ route('register') }}" class="font-bold text-emerald-700 hover:underline">
                    Daftar di sini
                </a>
            </div>

        </div>

    </div>

    @push('scripts')
    <script>
        function fillCredentials(email, password) {
            document.getElementById('emailInput').value = email;
            document.getElementById('passwordInput').value = password;
        }
    </script>
    @endpush

@endsection

