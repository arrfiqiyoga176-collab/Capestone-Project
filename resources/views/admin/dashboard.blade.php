@extends('admin.layout')

@section('title', 'Admin Dashboard Overview | PESISIR-EDU')
@section('page_title', 'Overview Sistem Rekomendasi Lokasi Belajar Pesisir')
@section('page_subtitle', 'Monitoring data spasial lokasi pembelajaran luar ruang dan matriks kurikulum metode SAW')

@section('content')
<div class="space-y-7">
    <!-- 1. STATISTIC CARDS (Gymove Style) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Total Lokasi -->
        <div class="bg-white rounded-card p-6 shadow-soft-card border border-slate-100 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Lokasi Edukatif</span>
                <h3 class="text-3xl font-black text-slate-900 mt-1">{{ $totalLocations }}</h3>
                <span class="text-[11px] font-semibold text-emerald-600 mt-1 inline-block">● Terverifikasi di Bengkulu</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-gymove-blue flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>

        <!-- Card 2: Total Mata Pelajaran -->
        <div class="bg-white rounded-card p-6 shadow-soft-card border border-slate-100 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Mata Pelajaran</span>
                <h3 class="text-3xl font-black text-slate-900 mt-1">{{ $totalSubjects }}</h3>
                <span class="text-[11px] font-semibold text-gymove-blue mt-1 inline-block">7 Bidang Studi</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
        </div>

        <!-- Card 3: Rata-Rata Fasilitas (C3) -->
        <div class="bg-white rounded-card p-6 shadow-soft-card border border-slate-100 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Rata-Rata Fasilitas (C3)</span>
                <h3 class="text-3xl font-black text-slate-900 mt-1">{{ $avgFacility }} <span class="text-base font-bold text-slate-400">/ 100</span></h3>
                <span class="text-[11px] font-semibold text-amber-600 mt-1 inline-block">Kriteria Kelayakan Belajar</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>

        <!-- Card 4: Rata-Rata Keamanan (C4) -->
        <div class="bg-white rounded-card p-6 shadow-soft-card border border-slate-100 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Rata-Rata Keamanan (C4)</span>
                <h3 class="text-3xl font-black text-slate-900 mt-1">{{ $avgSafety }} <span class="text-base font-bold text-slate-400">/ 100</span></h3>
                <span class="text-[11px] font-semibold text-cyan-600 mt-1 inline-block">Kriteria Keamanan Siswa</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- 2. SAW PARAMETERS OVERVIEW & QUICK ACTIONS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-7">
        <!-- Struktur Kriteria SAW -->
        <div class="lg:col-span-8 bg-white rounded-card p-7 shadow-soft-card border border-slate-100">
            <div class="flex items-center justify-between pb-5 border-b border-slate-100 mb-5">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">4 Kriteria Rekomendasi SPK Metode SAW</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Konfigurasi atribut pembobotan dan normalisasi Simple Additive Weighting</p>
                </div>
                <span class="text-xs font-bold text-gymove-blue bg-gymove-soft-blue px-3 py-1 rounded-full">
                    Total Bobot: 1.0 (100%)
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                <div class="p-4 rounded-2xl bg-indigo-50/70 border border-indigo-100">
                    <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider">C1: Relevansi</span>
                    <div class="text-2xl font-black text-indigo-900 mt-1">35%</div>
                    <span class="text-[10px] text-indigo-600 font-semibold mt-1 block">Benefit (Kurikulum)</span>
                </div>

                <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-100">
                    <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">C2: Jarak</span>
                    <div class="text-2xl font-black text-amber-900 mt-1">25%</div>
                    <span class="text-[10px] text-amber-700 font-semibold mt-1 block">Cost (Haversine)</span>
                </div>

                <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-100">
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">C3: Fasilitas</span>
                    <div class="text-2xl font-black text-emerald-900 mt-1">20%</div>
                    <span class="text-[10px] text-emerald-600 font-semibold mt-1 block">Benefit (Sarana)</span>
                </div>

                <div class="p-4 rounded-2xl bg-cyan-50/70 border border-cyan-100">
                    <span class="text-xs font-bold text-cyan-700 uppercase tracking-wider">C4: Keamanan</span>
                    <div class="text-2xl font-black text-cyan-900 mt-1">20%</div>
                    <span class="text-[10px] text-cyan-700 font-semibold mt-1 block">Benefit (Proteksi)</span>
                </div>
            </div>
        </div>

        <!-- Quick Admin Action Shortcuts -->
        <div class="lg:col-span-4 bg-white rounded-card p-7 shadow-soft-card border border-slate-100 flex flex-col justify-between">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 pb-3 border-b border-slate-100 mb-4">Aksi Cepat Pengelolaan</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.locations') }}" class="w-full p-3.5 rounded-2xl bg-gymove-soft-blue text-gymove-blue font-bold text-xs flex items-center justify-between hover:bg-gymove-blue hover:text-white transition-all group">
                        <span class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Kelola Data Lokasi Edukatif (CRUD)</span>
                        </span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('admin.subjects') }}" class="w-full p-3.5 rounded-2xl bg-slate-50 text-slate-700 font-bold text-xs flex items-center justify-between hover:bg-slate-100 transition-all group">
                        <span class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-gymove-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span>Matriks Kurikulum & Mata Pelajaran (CRUD)</span>
                        </span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('frontend.places') }}" target="_blank" class="w-full p-3.5 rounded-2xl bg-gymove-soft-blue text-gymove-blue font-extrabold text-xs flex items-center justify-between hover:bg-gymove-blue hover:text-white transition-all group">
                        <span class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-gymove-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Pratinjau 50 Tempat Belajar</span>
                        </span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
            </div>
            
            <p class="text-[11px] text-slate-400 mt-4 leading-relaxed">
                Setiap pembaruan atribut C1, C3, dan C4 di panel ini langsung terhubung dengan kalkulasi SAW di portal siswa.
            </p>
        </div>
    </div>

    <!-- 3. TABEL LOKASI EDUKATIF TERDAFTAR -->
    <div class="bg-white rounded-card p-7 shadow-soft-card border border-slate-100">
        <div class="flex items-center justify-between pb-5 border-b border-slate-100 mb-4">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Daftar Tempat Belajar Edukatif Kota Bengkulu</h3>
                <p class="text-xs text-slate-400 mt-0.5">Ringkasan atribut fasilitas dan keamanan lokasi di Kota Bengkulu</p>
            </div>
            <a href="{{ route('admin.locations') }}" class="text-xs font-bold text-gymove-blue hover:underline">
                Buka CRUD Selengkapnya →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                        <th class="pb-3">Nama Lokasi Edukatif</th>
                        <th class="pb-3">Kawasan / Alamat</th>
                        <th class="pb-3">Fasilitas (C3)</th>
                        <th class="pb-3">Keamanan (C4)</th>
                        <th class="pb-3">Mata Pelajaran Relevan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @foreach($recentLocations as $loc)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-3.5 font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-gymove-orange shrink-0"></span>
                            <span>{{ $loc->nama_lokasi }}</span>
                        </td>
                        <td class="py-3.5 text-slate-500">{{ Str::limit($loc->alamat_kawasan, 35) }}</td>
                        <td class="py-3.5 font-mono font-bold text-gymove-blue">{{ $loc->skor_fasilitas_c3 }}/100</td>
                        <td class="py-3.5 font-mono font-bold text-emerald-600">{{ $loc->skor_keamanan_c4 }}/100</td>
                        <td class="py-3.5">
                            <div class="flex flex-wrap gap-1">
                                @foreach($loc->subjects as $s)
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold"
                                      style="background-color: {{ $s->warna_bg_soft ?? '#EEF2FF' }}; color: {{ $s->warna_teks ?? '#132985' }};">
                                    {{ $s->kode_mapel }}
                                </span>
                                @endforeach
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
