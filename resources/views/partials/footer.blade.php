<footer class="bg-slate-900 text-slate-300 border-t border-slate-800 mt-16 pt-14 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-slate-800">

            <!-- Kolom 1: Identitas Kantin -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 flex items-center justify-center flex-shrink-0">
                        <img src="{{ asset('images/logo-smkn20.png') }}" alt="Logo SMK Negeri 20 Jakarta" class="w-full h-full object-contain drop-shadow-md">
                    </div>
                    <div>
                        <span class="text-[10px] font-bold tracking-widest text-brand-400 uppercase">SMK NEGERI 20 JAKARTA</span>
                        <h4 class="text-lg font-bold text-white tracking-tight">Kantin <span class="text-brand-400">MELATIAREA</span></h4>
                    </div>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Sistem pemesanan makanan dan minuman kantin sekolah modern berbasis pre-order online. Praktis, higienis, dan tanpa antri panjang saat jam istirahat.
                </p>
                <div class="flex items-center gap-3 pt-2 text-slate-400">
                    <a href="https://www.instagram.com/smkn20jktofficial" target="_blank" rel="noopener noreferrer"
                       class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center hover:text-brand-400 hover:bg-slate-700 transition"
                       title="Instagram SMKN 20 Jakarta">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://youtube.com/@smkn20jakarta" target="_blank" rel="noopener noreferrer"
                       class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center hover:text-brand-400 hover:bg-slate-700 transition"
                       title="YouTube SMKN 20 Jakarta">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            <!-- Kolom 2: Jam Operasional -->
            <div>
                <h4 class="text-white font-bold text-sm tracking-wider uppercase mb-4 flex items-center gap-2">
                    <i class="far fa-clock text-brand-400"></i> Jam Operasional
                </h4>
                <div class="bg-slate-800/80 rounded-2xl p-4 border border-slate-700/60 space-y-2.5">
                    <div class="flex justify-between items-center text-xs pb-2 border-b border-slate-700">
                        <span class="text-slate-300 font-semibold">Senin - Jumat</span>
                        <span class="text-brand-400 font-bold">07.00 - 14.00 WIB</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-400">Sabtu & Minggu</span>
                        <span class="text-rose-400 font-medium">Libur</span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-3">
                    <i class="fas fa-circle-info text-brand-400 mr-1"></i> Pre-order disarankan dilakukan sebelum jam istirahat pertama (09.45 WIB).
                </p>
            </div>

            <!-- Kolom 3: 8 Stand Jurusan -->
            <div>
                <h4 class="text-white font-bold text-sm tracking-wider uppercase mb-4 flex items-center gap-2">
                    <i class="fas fa-store text-brand-400"></i> 8 Stand Jurusan
                </h4>
                <ul class="space-y-1.5 text-xs text-slate-400">
                    <li class="hover:text-white transition flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-400"></span> 1. Bisnis Digital
                    </li>
                    <li class="hover:text-white transition flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-400"></span> 2. Manajemen Logistik
                    </li>
                    <li class="hover:text-white transition flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-400"></span> 3. Layanan Perbankan Syariah
                    </li>
                    <li class="hover:text-white transition flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-400"></span> 4. Akuntansi 1
                    </li>
                    <li class="hover:text-white transition flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-400"></span> 5. Bisnis Retail
                    </li>
                    <li class="hover:text-white transition flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-400"></span> 6. Manajemen Perkantoran
                    </li>
                    <li class="hover:text-white transition flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-400"></span> 7. Rekayasa Perangkat Lunak
                    </li>
                    <li class="hover:text-white transition flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-400"></span> 8. Akuntansi 2
                    </li>
                </ul>
            </div>

            <!-- Kolom 4: Tautan Cepat & Lokasi -->
            <div>
                <h4 class="text-white font-bold text-sm tracking-wider uppercase mb-4 flex items-center gap-2">
                    <i class="fas fa-compass text-brand-400"></i> Tautan Cepat
                </h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="{{ route('home') }}" class="hover:text-brand-400 transition"><i class="fas fa-angle-right mr-1.5 text-xs text-brand-500"></i> Beranda Kantin</a></li>
                    <li><a href="{{ route('stands.index') }}" class="hover:text-brand-400 transition"><i class="fas fa-angle-right mr-1.5 text-xs text-brand-500"></i> Daftar Stand</a></li>
                    <li><a href="{{ route('menu.index') }}" class="hover:text-brand-400 transition"><i class="fas fa-angle-right mr-1.5 text-xs text-brand-500"></i> Semua Menu Makanan</a></li>
                    <li><a href="{{ route('orders.index') }}" class="hover:text-brand-400 transition"><i class="fas fa-angle-right mr-1.5 text-xs text-brand-500"></i> Cek Status Pesanan</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-brand-400 transition"><i class="fas fa-angle-right mr-1.5 text-xs text-brand-500"></i> Login Akun</a></li>
                </ul>
                <div class="mt-4 p-3 rounded-xl bg-slate-800/60 border border-slate-700/60 text-xs text-slate-400">
                    <p class="font-semibold text-white"><i class="fas fa-location-dot text-rose-400 mr-1"></i> Di Dalam Area Sekolah SMK Negeri 20 Jakarta</p>
                    <p class="text-[11px] mt-0.5 text-slate-400">Kantin berada di dalam area sekolah SMK Negeri 20 Jakarta.</p>
                </div>
            </div>

        </div>

        <!-- Copyright -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} <strong>Kantin MELATIAREA</strong> &mdash; SMK Negeri 20 Jakarta. Dikelola oleh Kolaborasi 8 Jurusan.</p>
            <p class="flex items-center gap-2">
                <span>Dikembangkan untuk Kemajuan Digitalisasi Sekolah</span>
            </p>
        </div>
    </div>
</footer>
