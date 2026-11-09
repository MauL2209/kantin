@extends('layouts.app')

@section('title', 'Profil Saya - Kantin MELATIAREA')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Profil Saya</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola informasi akun dan foto profil Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ===== LEFT COLUMN: Profile Card ===== --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Avatar & Info Card --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 flex flex-col items-center text-center gap-4">

                {{-- Avatar Image --}}
                <div class="relative">
                    @if($user->avatar)
                        <img src="{{ asset('avatars/' . $user->avatar) }}"
                             alt="Foto profil {{ $user->name }}"
                             class="w-24 h-24 rounded-2xl object-cover ring-4 ring-emerald-100 shadow-md">
                    @else
                        <div class="w-24 h-24 rounded-2xl bg-gradient-to-tr from-brand-600 to-teal-500 text-white font-black text-3xl flex items-center justify-center shadow-md ring-4 ring-emerald-100">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                {{-- Name & Email --}}
                <div>
                    <h2 class="text-base font-black text-slate-900">{{ $user->name }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5 break-all">{{ $user->email }}</p>
                </div>

                {{-- Role Badge --}}
                @php
                    $roleConfig = match($user->role) {
                        'admin'   => ['label' => 'Administrator',  'class' => 'bg-amber-100 text-amber-800 border-amber-200'],
                        'penjaga' => ['label' => 'Penjaga Kantin', 'class' => 'bg-blue-100 text-blue-800 border-blue-200'],
                        default   => ['label' => 'Siswa',          'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
                    };
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $roleConfig['class'] }}">
                    @if($user->role === 'admin')
                        <i class="fas fa-shield-halved text-[10px]"></i>
                    @elseif($user->role === 'penjaga')
                        <i class="fas fa-store text-[10px]"></i>
                    @else
                        <i class="fas fa-graduation-cap text-[10px]"></i>
                    @endif
                    {{ $roleConfig['label'] }}
                </span>

                {{-- Stand info for penjaga --}}
                @if($user->isPenjaga() && $user->stand)
                    <div class="w-full bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-2.5 text-left">
                        <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider">Stand Ditugaskan</p>
                        <p class="text-sm font-bold text-emerald-900 mt-0.5">{{ $user->stand->name }}</p>
                    </div>
                @endif

                {{-- Info items --}}
                <div class="w-full divide-y divide-slate-100 text-left">
                    {{-- Phone --}}
                    <div class="flex items-center gap-3 py-2.5">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-slate-400 text-xs"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Telepon</p>
                            <p class="text-xs font-semibold text-slate-700 truncate">
                                {{ $user->phone ?? '—' }}
                            </p>
                        </div>
                    </div>

                    {{-- NIS (siswa only) --}}
                    @if($user->isSiswa())
                        <div class="flex items-center gap-3 py-2.5">
                            <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-id-card text-slate-400 text-xs"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">NIS</p>
                                <p class="text-xs font-semibold text-slate-700 truncate">
                                    {{ $user->nis ?? '—' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 py-2.5">
                            <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-chalkboard-user text-slate-400 text-xs"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Kelas</p>
                                <p class="text-xs font-semibold text-slate-700 truncate">
                                    {{ $user->class_room ?? '—' }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Change Password Link Card --}}
            <a href="{{ route('password.change') }}"
               class="flex items-center justify-between bg-white rounded-2xl border border-slate-200/80 shadow-sm px-5 py-4 hover:border-emerald-300 hover:bg-emerald-50/50 transition group">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 group-hover:bg-emerald-100 flex items-center justify-center transition">
                        <i class="fas fa-key text-slate-500 group-hover:text-emerald-700 text-sm transition"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800 group-hover:text-emerald-800 transition">Ubah Password</p>
                        <p class="text-[11px] text-slate-400">Perbarui kata sandi Anda</p>
                    </div>
                </div>
                <i class="fas fa-chevron-right text-slate-300 group-hover:text-emerald-500 text-xs transition"></i>
            </a>

        </div>

        {{-- ===== RIGHT COLUMN: Edit Form ===== --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">

                <div class="mb-6 pb-4 border-b border-slate-100">
                    <h2 class="text-base font-bold text-slate-900">Edit Informasi Profil</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Perubahan akan langsung disimpan setelah Anda klik tombol simpan.</p>
                </div>

                {{-- Validation Error Summary --}}
                @if($errors->any())
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

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    {{-- Name --}}
                    <div class="space-y-1.5">
                        <label for="name" class="block text-xs font-bold text-slate-700">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               required
                               placeholder="Nama lengkap Anda"
                               class="w-full px-4 py-2.5 rounded-xl border @error('name') border-rose-400 bg-rose-50 @else border-slate-200 bg-slate-50 @enderror text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                        @error('name')
                            <p class="text-[11px] text-rose-500 flex items-center gap-1 mt-0.5">
                                <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="space-y-1.5">
                        <label for="phone" class="block text-xs font-bold text-slate-700">
                            Nomor Telepon / WhatsApp
                        </label>
                        <input type="tel"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', $user->phone) }}"
                               placeholder="081234567890"
                               class="w-full px-4 py-2.5 rounded-xl border @error('phone') border-rose-400 bg-rose-50 @else border-slate-200 bg-slate-50 @enderror text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                        @error('phone')
                            <p class="text-[11px] text-rose-500 flex items-center gap-1 mt-0.5">
                                <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- NIS & Kelas (Siswa only) --}}
                    @if($user->isSiswa())
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="nis" class="block text-xs font-bold text-slate-700">
                                    NIS (Nomor Induk Siswa)
                                </label>
                                <input type="text"
                                       id="nis"
                                       name="nis"
                                       value="{{ old('nis', $user->nis) }}"
                                       placeholder="Contoh: 20241001"
                                       class="w-full px-4 py-2.5 rounded-xl border @error('nis') border-rose-400 bg-rose-50 @else border-slate-200 bg-slate-50 @enderror text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                                @error('nis')
                                    <p class="text-[11px] text-rose-500 flex items-center gap-1 mt-0.5">
                                        <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label for="class_room" class="block text-xs font-bold text-slate-700">
                                    Kelas / Jurusan
                                </label>
                                <input type="text"
                                       id="class_room"
                                       name="class_room"
                                       value="{{ old('class_room', $user->class_room) }}"
                                       placeholder="Contoh: XII RPL 1"
                                       class="w-full px-4 py-2.5 rounded-xl border @error('class_room') border-rose-400 bg-rose-50 @else border-slate-200 bg-slate-50 @enderror text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                                @error('class_room')
                                    <p class="text-[11px] text-rose-500 flex items-center gap-1 mt-0.5">
                                        <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    @endif

                    {{-- Avatar Upload --}}
                    <div class="space-y-1.5" x-data="avatarPreview()">
                        <label class="block text-xs font-bold text-slate-700">Foto Profil</label>

                        <div class="flex items-start gap-4">
                            {{-- Current / Preview Avatar --}}
                            <div class="flex-shrink-0">
                                <div x-show="previewUrl" x-cloak>
                                    <img :src="previewUrl" alt="Preview" class="w-16 h-16 rounded-xl object-cover ring-2 ring-emerald-200">
                                </div>
                                <div x-show="!previewUrl">
                                    @if($user->avatar)
                                        <img src="{{ asset('avatars/' . $user->avatar) }}"
                                             alt="Avatar saat ini"
                                             class="w-16 h-16 rounded-xl object-cover ring-2 ring-slate-200">
                                    @else
                                        <div class="w-16 h-16 rounded-xl bg-gradient-to-tr from-brand-600 to-teal-500 text-white font-black text-xl flex items-center justify-center">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- File Input --}}
                            <div class="flex-1 space-y-1.5">
                                <label for="avatar"
                                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-dashed border-slate-300 bg-slate-50 hover:border-emerald-400 hover:bg-emerald-50/50 cursor-pointer transition">
                                    <i class="fas fa-cloud-arrow-up text-slate-400 text-sm"></i>
                                    <span class="text-xs text-slate-500" x-text="fileName || 'Pilih foto (JPG, PNG, WEBP, maks. 2MB)'"></span>
                                </label>
                                <input type="file"
                                       id="avatar"
                                       name="avatar"
                                       accept=".jpg,.jpeg,.png,.webp"
                                       class="sr-only"
                                       @change="handleFile($event)">
                                @error('avatar')
                                    <p class="text-[11px] text-rose-500 flex items-center gap-1 mt-0.5">
                                        <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                                    </p>
                                @enderror
                                <p class="text-[10px] text-slate-400">Format: JPG, JPEG, PNG, WEBP. Ukuran maksimum: 2 MB.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="border-t border-slate-100 pt-2"></div>

                    {{-- Read-only Info --}}
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-lock text-[10px]"></i> Informasi yang Tidak Dapat Diubah
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-xs text-slate-600">
                            <div>
                                <span class="font-semibold text-slate-400">Email:</span>
                                <span class="ml-1">{{ $user->email }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-slate-400">Role:</span>
                                <span class="ml-1 capitalize">{{ $roleConfig['label'] }}</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400">Untuk mengubah email atau role, hubungi Administrator.</p>
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center justify-end gap-3 pt-1">
                        <a href="{{ url()->previous() }}"
                           class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-emerald-600/20 hover:shadow-emerald-600/30 transition">
                            <i class="fas fa-floppy-disk mr-1.5"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function avatarPreview() {
        return {
            previewUrl: null,
            fileName: '',
            handleFile(event) {
                const file = event.target.files[0];
                if (!file) {
                    this.previewUrl = null;
                    this.fileName = '';
                    return;
                }
                this.fileName = file.name;
                const reader = new FileReader();
                reader.onload = (e) => { this.previewUrl = e.target.result; };
                reader.readAsDataURL(file);
            }
        };
    }
</script>
@endpush

@endsection
