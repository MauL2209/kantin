@extends('layouts.app')

@section('title', '8 Stand Kantin Jurusan - Kantin Sekolah MELATIAREA')

@section('content')

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-900 text-white py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl space-y-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 font-bold text-xs uppercase tracking-wider border border-emerald-400/30">
                    <i class="fas fa-store"></i> Direktori Stand Kantin
                </span>
                <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                    8 Stand Kantin Jurusan SMK
                </h1>
                <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                    Setiap stand dikelola oleh siswa dan guru pembimbing dari jurusan masing-masing. Dukung kreativitas kewirausahaan siswa dengan memesan makanan dan minuman favoritmu!
                </p>
            </div>
        </div>
    </div>

    <!-- Stand Cards List -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($stands as $stand)
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden group hover:-translate-y-1">
                    
                    <!-- Stand Photo -->
                    <div class="relative h-48 w-full bg-slate-200 overflow-hidden">
                        <img src="{{ $stand->image ?: 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=500' }}" 
                             alt="{{ $stand->name }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        
                        <!-- Open / Closed Badge -->
                        <div class="absolute top-3 right-3">
                            @if($stand->isOpen())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500 text-white font-black text-[11px] shadow">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> BUKA
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-500 text-white font-bold text-[11px] shadow">
                                    <i class="fas fa-lock text-[10px]"></i> TUTUP
                                </span>
                            @endif
                        </div>

                        <!-- Department Badge -->
                        <div class="absolute bottom-3 left-3 right-3">
                            <span class="inline-block px-2.5 py-0.5 rounded-md bg-white/95 text-emerald-800 font-extrabold text-[10px] mb-1 shadow-sm">
                                Jurusan {{ $stand->department }}
                            </span>
                            <h3 class="text-base font-bold text-white leading-tight drop-shadow">
                                {{ $stand->name }}
                            </h3>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="p-5 flex-grow flex flex-col justify-between space-y-4">
                        <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                            {{ $stand->description }}
                        </p>

                        <div class="space-y-1.5 text-xs text-slate-500 border-t border-slate-100 pt-3">
                            <div class="flex items-center justify-between">
                                <span><i class="fas fa-utensils text-emerald-600 mr-1.5"></i> Variasi Menu:</span>
                                <strong class="text-slate-800 font-bold">{{ $stand->products_count }} Menu</strong>
                            </div>
                            <div class="flex items-center justify-between">
                                <span><i class="fas fa-location-dot text-rose-500 mr-1.5"></i> Lokasi:</span>
                                <span class="text-slate-700 font-medium">{{ $stand->location ?? 'Area Kantin' }}</span>
                            </div>
                            @if($stand->contact_person)
                                <div class="flex items-center justify-between text-[11px] text-slate-400">
                                    <span><i class="fas fa-user-tie text-blue-500 mr-1.5"></i> Pengelola:</span>
                                    <span class="truncate max-w-[130px]">{{ $stand->contact_person }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- CTA Button -->
                        <a href="{{ route('stands.show', $stand->id) }}" class="w-full py-2.5 rounded-xl bg-slate-900 group-hover:bg-emerald-600 text-white font-bold text-xs text-center transition duration-200 flex items-center justify-center gap-2">
                            <span>Lihat Menu Stand</span>
                            <i class="fas fa-arrow-right text-[10px] transform group-hover:translate-x-1 transition"></i>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>
    </div>

@endsection

