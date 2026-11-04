@extends('layouts.admin')

@section('title', 'Kelola 8 Stand Kantin - Panel Admin')
@section('page_title', 'Kelola Stand Kantin Jurusan')

@section('content')

    <div class="space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
            <div>
                <h2 class="text-base font-bold text-slate-900">Daftar 8 Stand Jurusan</h2>
                <p class="text-xs text-slate-400">Kelola status buka/tutup, informasi pengelola, dan foto stand kantin.</p>
            </div>
            <a href="{{ route('admin.stands.create') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm flex items-center gap-1.5 transition">
                <i class="fas fa-plus"></i> Tambah Stand Baru
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-100">
                            <th class="py-3 px-3">Stand</th>
                            <th class="py-3 px-3">Jurusan</th>
                            <th class="py-3 px-3">Lokasi</th>
                            <th class="py-3 px-3">Pengelola</th>
                            <th class="py-3 px-3 text-center">Menu Aktif</th>
                            <th class="py-3 px-3 text-center">Status Operasional</th>
                            <th class="py-3 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($stands as $stand)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 px-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $stand->image ?: 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=100' }}" 
                                             alt="" class="w-12 h-12 rounded-xl object-cover border border-slate-200">
                                        <div>
                                            <h4 class="font-bold text-slate-900 text-sm leading-tight">{{ $stand->name }}</h4>
                                            <p class="text-[11px] text-slate-400 line-clamp-1 max-w-xs">{{ $stand->description }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded text-[11px]">
                                        {{ $stand->department }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 text-slate-600 font-medium">
                                    {{ $stand->location ?? 'Area Kantin' }}
                                </td>
                                <td class="py-3.5 px-3 text-slate-500 text-[11px]">
                                    {{ $stand->contact_person ?? '-' }}
                                </td>
                                <td class="py-3.5 px-3 text-center font-bold text-slate-900">
                                    {{ $stand->products_count }} Menu
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    <form action="{{ route('admin.stands.toggle', $stand->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition {{ $stand->isOpen() ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-rose-100 text-rose-800 hover:bg-rose-200' }}" title="Klik untuk ubah status">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $stand->isOpen() ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                            {{ $stand->isOpen() ? 'BUKA' : 'TUTUP' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('stands.show', $stand->id) }}" target="_blank" class="p-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-600 transition" title="Lihat di Web">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('admin.stands.edit', $stand->id) }}" class="p-2 bg-amber-50 hover:bg-amber-100 rounded-lg text-amber-700 transition" title="Edit">
                                            <i class="fas fa-pencil text-xs"></i>
                                        </a>
                                        <form action="{{ route('admin.stands.destroy', $stand->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus stand {{ $stand->name }}? Semua menu di stand ini juga akan terhapus.')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 rounded-lg text-rose-700 transition" title="Hapus">
                                                <i class="fas fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection

