@extends('layouts.admin')

@section('title', 'Manajemen Pengguna - Panel Admin')
@section('page_title', 'Manajemen Akun Pengguna')

@section('content')

    <div class="space-y-6">

        {{-- Header & Action --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
            <div>
                <h2 class="text-base font-bold text-slate-900">Daftar Semua Pengguna</h2>
                <p class="text-xs text-slate-400">Kelola akun Siswa, Penjaga Kantin, dan Administrator sistem.</p>
            </div>
            <a href="{{ route('admin.users.create') }}"
               class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm flex items-center gap-1.5 transition">
                <i class="fas fa-plus"></i> Tambah Pengguna Baru
            </a>
        </div>

        {{-- Filter Bar --}}
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="w-full sm:w-72">
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="Cari nama, email, atau NIS..."
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50">
                </div>

                <select name="role"
                        class="px-3 py-2 rounded-xl border border-slate-200 text-xs bg-slate-50 focus:outline-none text-slate-700">
                    <option value="">Semua Role</option>
                    <option value="siswa"   {{ request('role') === 'siswa'   ? 'selected' : '' }}>Siswa</option>
                    <option value="penjaga" {{ request('role') === 'penjaga' ? 'selected' : '' }}>Penjaga Kantin</option>
                    <option value="admin"   {{ request('role') === 'admin'   ? 'selected' : '' }}>Administrator</option>
                </select>

                <button type="submit"
                        class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-900 transition">
                    Filter
                </button>

                @if(request()->hasAny(['q', 'role']))
                    <a href="{{ route('admin.users.index') }}"
                       class="px-3 py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-100 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Users Table --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-100">
                            <th class="py-3 px-3">Nama</th>
                            <th class="py-3 px-3">Email</th>
                            <th class="py-3 px-3">Role</th>
                            <th class="py-3 px-3">NIS</th>
                            <th class="py-3 px-3">Stand yang Ditugaskan</th>
                            <th class="py-3 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50 transition">

                                {{-- Nama --}}
                                <td class="py-3.5 px-3">
                                    <div class="flex items-center gap-3">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                                 alt="{{ $user->name }}"
                                                 class="w-9 h-9 rounded-xl object-cover border border-slate-200 flex-shrink-0">
                                        @else
                                            <div class="w-9 h-9 rounded-xl flex-shrink-0 flex items-center justify-center font-black text-sm
                                                {{ $user->role === 'admin'   ? 'bg-blue-100 text-blue-700'    :
                                                  ($user->role === 'penjaga' ? 'bg-emerald-100 text-emerald-700' :
                                                                               'bg-slate-100 text-slate-600') }}">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="font-bold text-slate-900 leading-tight">{{ $user->name }}</h4>
                                            @if($user->phone)
                                                <p class="text-[10px] text-slate-400">{{ $user->phone }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td class="py-3.5 px-3 text-slate-500">
                                    {{ $user->email }}
                                </td>

                                {{-- Role Badge --}}
                                <td class="py-3.5 px-3">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 font-bold text-[10px] uppercase tracking-wide">
                                            <i class="fas fa-shield-halved text-[9px]"></i> Admin
                                        </span>
                                    @elseif($user->role === 'penjaga')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px] uppercase tracking-wide">
                                            <i class="fas fa-store text-[9px]"></i> Penjaga
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-wide">
                                            <i class="fas fa-user-graduate text-[9px]"></i> Siswa
                                        </span>
                                    @endif
                                </td>

                                {{-- NIS --}}
                                <td class="py-3.5 px-3">
                                    @if($user->nis)
                                        <span class="font-mono text-slate-700 text-[11px]">{{ $user->nis }}</span>
                                        @if($user->class_room)
                                            <p class="text-[10px] text-slate-400">{{ $user->class_room }}</p>
                                        @endif
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>

                                {{-- Stand yang Ditugaskan --}}
                                <td class="py-3.5 px-3">
                                    @if($user->stand)
                                        <span class="font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded text-[11px]">
                                            {{ $user->stand->name }}
                                        </span>
                                    @elseif($user->role === 'penjaga')
                                        <span class="text-amber-600 text-[11px] font-semibold">
                                            <i class="fas fa-triangle-exclamation text-[10px]"></i> Belum ditugaskan
                                        </span>
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="py-3.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                           class="p-2 bg-amber-50 hover:bg-amber-100 rounded-lg text-amber-700 transition"
                                           title="Edit Akun">
                                            <i class="fas fa-pencil text-xs"></i>
                                        </a>

                                        <button type="button"
                                                onclick="openResetModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                                class="p-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-600 transition"
                                                title="Reset Password">
                                            <i class="fas fa-key text-xs"></i>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center text-slate-400">
                                    <i class="fas fa-users-slash text-3xl mb-3 block"></i>
                                    <p class="font-semibold text-sm">Tidak ada pengguna ditemukan</p>
                                    @if(request()->hasAny(['q', 'role']))
                                        <p class="text-xs mt-1">Coba ubah kata kunci atau filter pencarian Anda.</p>
                                        <a href="{{ route('admin.users.index') }}"
                                           class="mt-3 inline-block text-emerald-600 hover:underline text-xs font-bold">
                                            Tampilkan semua pengguna
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            @endif

        </div>

    </div>

    {{-- Reset Password Modal --}}
    <div id="resetPasswordModal"
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-sm hidden"
         x-data>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
                    <i class="fas fa-key text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-900">Reset Password</h3>
                    <p id="resetModalSubtitle" class="text-xs text-slate-400">Akun pengguna</p>
                </div>
            </div>

            <form id="resetPasswordForm" method="POST" action="">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Password Baru</label>
                        <input type="password" name="password" required minlength="8"
                               placeholder="Minimal 8 karakter"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required minlength="8"
                               placeholder="Ulangi password baru"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 bg-slate-50">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <button type="button" onclick="closeResetModal()"
                            class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-100 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition">
                        <i class="fas fa-key mr-1"></i> Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openResetModal(userId, userName) {
        const modal   = document.getElementById('resetPasswordModal');
        const form    = document.getElementById('resetPasswordForm');
        const subtitle = document.getElementById('resetModalSubtitle');

        form.action = `/admin/users/${userId}/reset-password`;
        subtitle.textContent = 'Akun: ' + userName;

        modal.classList.remove('hidden');
    }

    function closeResetModal() {
        const modal = document.getElementById('resetPasswordModal');
        modal.classList.add('hidden');

        // Clear inputs
        document.querySelectorAll('#resetPasswordForm input[type="password"]')
                .forEach(el => el.value = '');
    }

    // Close modal on backdrop click
    document.getElementById('resetPasswordModal').addEventListener('click', function (e) {
        if (e.target === this) closeResetModal();
    });
</script>
@endpush
