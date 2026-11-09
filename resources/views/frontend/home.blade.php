@extends('layouts.student')

@section('title', 'EDU PESISIR | Rekomendasi Tempat Belajar Edukatif Kota Bengkulu')
@section('page_title', 'Selamat Datang di EDU PESISIR')

@section('content')
<div class="space-y-12">

    <!-- ============================================== -->
    <!-- 1. HERO SECTION (ATTRACTIVE & INSPIRING) -->
    <!-- ============================================== -->
    <div class="bg-gradient-to-br from-gymove-blue via-[#18349d] to-[#0c1c5e] rounded-card p-8 md:p-12 text-white shadow-xl relative overflow-hidden">
        <!-- Ambient decorative blurs -->
        <div class="absolute -right-16 -bottom-16 w-96 h-96 bg-gymove-orange/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute left-1/3 top-0 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
            
            <!-- Left 7 Cols: Inspiring Copywriting & CTA -->
            <div class="lg:col-span-7 space-y-5 text-center lg:text-left">
                
                <!-- Pill Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-bold text-amber-300">
                    <svg class="w-3.5 h-3.5 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    <span>Portal Edukasi Siswa Kota Bengkulu</span>
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                    Belajar Lebih Seru, Interaktif, dan Nyata di Luar Kelas!
                </h1>

                <p class="text-sm sm:text-base text-white/85 font-medium leading-relaxed max-w-xl mx-auto lg:mx-0">
                    Bosan dengan belajar di dalam kelas saja? Temukan tempat belajar edukatif terbaik di Kota Bengkulu yang pas dengan mata pelajaranmu. Mulai dari menelusuri cagar budaya bersejarah, laboratorium mangrove pesisir, hingga ruang literasi dan diskusi kelompok.
                </p>

                <!-- Action CTA Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                    <a href="{{ route('frontend.recommendations') }}" 
                       class="w-full sm:w-auto px-8 py-4 rounded-full bg-gymove-orange hover:bg-gymove-orange-hover text-white font-black text-sm tracking-wide shadow-orange-glow transition-all active:scale-95 flex items-center justify-center gap-2">
                        <span>Mulai Cari Rekomendasi Belajar</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>

                    <a href="{{ route('frontend.places') }}" 
                       class="w-full sm:w-auto px-7 py-4 rounded-full bg-white/10 hover:bg-white/20 text-white font-bold text-sm backdrop-blur-md border border-white/20 transition-all flex items-center justify-center gap-2">
                        <span>Jelajahi 50 Tempat Belajar</span>
                        <span>→</span>
                    </a>
                </div>

                <!-- 3 Quick Stats -->
                <div class="grid grid-cols-3 gap-3 pt-6 border-t border-white/15 max-w-lg mx-auto lg:mx-0">
                    <div class="text-center lg:text-left">
                        <span class="text-2xl sm:text-3xl font-black text-amber-300 font-mono block">50</span>
                        <span class="text-[11px] text-white/75 font-semibold">Tempat Terdata</span>
                    </div>
                    <div class="text-center lg:text-left">
                        <span class="text-2xl sm:text-3xl font-black text-cyan-300 font-mono block">29</span>
                        <span class="text-[11px] text-white/75 font-semibold">Mata Pelajaran</span>
                    </div>
                    <div class="text-center lg:text-left">
                        <span class="text-2xl sm:text-3xl font-black text-emerald-300 font-mono block">267</span>
                        <span class="text-[11px] text-white/75 font-semibold">Sekolah (SD, SMP, SMA)</span>
                    </div>
                </div>

            </div>

            <!-- Right 5 Cols: Visual Logo Badge & Glass Card -->
            <div class="lg:col-span-5 flex justify-center">
                <div class="relative w-full max-w-sm">
                    <!-- Glass Container -->
                    <div class="bg-white/10 backdrop-blur-xl p-8 rounded-card border border-white/20 shadow-2xl text-center space-y-4">
                        <div class="w-32 h-32 mx-auto rounded-3xl bg-white p-3 shadow-2xl ring-4 ring-white/40 flex items-center justify-center">
                            <img src="{{ asset('images/logo.png') }}" 
                                 alt="Logo EDU PESISIR" 
                                 class="w-full h-full object-contain transition-transform hover:scale-105 duration-300">
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-black text-white tracking-tight">EDU PESISIR</h3>
                            <p class="text-xs font-bold text-amber-300 uppercase tracking-widest mt-0.5">Sistem Rekomendasi Belajar</p>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-white/10 border border-white/10 text-left text-xs text-white/90 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                <span>Kurikulum SD, SMP, & SMA/SMK (29 Mapel)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                <span>267 Sekolah Terdata di Kota Bengkulu</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                                <span>50 Tempat Edukatif Lengkap Info Tiket</span>
                            </div>
                        </div>

                        <a href="{{ route('frontend.recommendations') }}" 
                           class="block w-full py-2.5 rounded-xl bg-white text-gymove-blue font-black text-xs hover:bg-slate-100 transition-colors shadow-sm">
                            Buka Rekomendasi Belajar →
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ============================================== -->
    <!-- 2. VALUE PROPOSITION: KENAPA HARUS BELAJAR DI LUAR KELAS? -->
    <!-- ============================================== -->
    <div>
        <div class="text-center max-w-2xl mx-auto mb-8">
            <span class="text-xs font-black uppercase tracking-widest text-gymove-orange block mb-1">
                Eksplorasi & Pengalaman Nyata
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Kenapa Belajar di Luar Kelas Lebih Mengasyikkan?
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">
                Pembelajaran lapangan memberikan wawasan langsung yang tidak kamu temukan di dalam dinding kelas biasa.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Benefit 1 -->
            <div class="bg-white rounded-card p-6 border border-slate-100 shadow-sm hover:shadow-soft-hover transition-all group">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900 mb-1.5">Materi Nyata & Bersejarah</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Sentuh langsung sejarah perjuangan di Benteng Marlborough dan Rumah Bung Karno, bukan hanya membaca dari buku teks.
                </p>
            </div>

            <!-- Benefit 2 -->
            <div class="bg-white rounded-card p-6 border border-slate-100 shadow-sm hover:shadow-soft-hover transition-all group">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900 mb-1.5">Laboratorium Alam Asli</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Amati langsung ekosistem mangrove Pulau Baai, dinamika pasir Pantai Panjang, serta kebun melon hidroponik modern.
                </p>
            </div>

            <!-- Benefit 3 -->
            <div class="bg-white rounded-card p-6 border border-slate-100 shadow-sm hover:shadow-soft-hover transition-all group">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-gymove-blue flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900 mb-1.5">Dihitung dari Sekolahmu</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Sistem otomatis menghitung jarak terdekat dari sekolah asalmu di Kota Bengkulu agar perjalanan lebih efisien dan hemat waktu.
                </p>
            </div>

            <!-- Benefit 4 -->
            <div class="bg-white rounded-card p-6 border border-slate-100 shadow-sm hover:shadow-soft-hover transition-all group">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900 mb-1.5">Ruang Belajar Kelompok</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Tersedia referensi kafe edukasi dan ruang literasi ramah pelajar untuk berdiskusi, mengerjakan tugas, dan berkolaborasi.
                </p>
            </div>

        </div>
    </div>

    <!-- ============================================== -->
    <!-- 3. HOW IT WORKS: 3 LANGKAH MUDAH -->
    <!-- ============================================== -->
    <div class="bg-white rounded-card p-8 md:p-10 border border-slate-100 shadow-soft-card">
        <div class="text-center max-w-xl mx-auto mb-10">
            <span class="text-xs font-black uppercase tracking-widest text-gymove-blue block mb-1">
                Panduan Praktis
            </span>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Cara Mudah Mendapatkan Rekomendasi
            </h2>
            <p class="text-xs text-slate-500 mt-1">Cukup 3 langkah sederhana untuk menemukan lokasi belajar yang ideal</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
            
            <!-- Step 1 -->
            <div class="text-center space-y-3 relative">
                <div class="w-16 h-16 rounded-3xl bg-gymove-soft-blue text-gymove-blue font-black text-2xl flex items-center justify-center mx-auto shadow-sm">
                    1
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Pilih Sekolah Asalmu</h3>
                <p class="text-xs text-slate-500 leading-relaxed max-w-xs mx-auto">
                    Pilih sekolahmu dari 267 SD, SMP, dan SMA/SMK/MA yang ada di Kota Bengkulu sebagai titik awal keberangkatan.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="text-center space-y-3 relative">
                <div class="w-16 h-16 rounded-3xl bg-amber-50 text-gymove-orange font-black text-2xl flex items-center justify-center mx-auto shadow-sm">
                    2
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Pilih Mata Pelajaran</h3>
                <p class="text-xs text-slate-500 leading-relaxed max-w-xs mx-auto">
                    Tentukan 1 atau beberapa mata pelajaran yang ingin kamu pelajari di lapangan (Biologi, Sejarah, Geografi, dll).
                </p>
            </div>

            <!-- Step 3 -->
            <div class="text-center space-y-3 relative">
                <div class="w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 font-black text-2xl flex items-center justify-center mx-auto shadow-sm">
                    3
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Lihat Rekomendasi Terbaik</h3>
                <p class="text-xs text-slate-500 leading-relaxed max-w-xs mx-auto">
                    Sistem langsung memberikan urutan tempat belajar terbaik lengkap dengan rute, jam operasional, dan info tiket.
                </p>
            </div>

        </div>

        <div class="mt-10 text-center pt-6 border-t border-slate-100">
            <a href="{{ route('frontend.recommendations') }}" 
               class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full bg-gymove-blue hover:bg-gymove-blue-hover text-white font-black text-xs shadow-blue-glow transition-all active:scale-95">
                <span>Coba Fitur Rekomendasi Sekarang</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>


    <!-- ============================================== -->
    <!-- 5. FEATURED PLACES SHOWCASE -->
    <!-- ============================================== -->
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Tempat Belajar Unggulan Kota Bengkulu
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Destinasi populer yang sering dikunjungi untuk studi lapangan</p>
            </div>

            <a href="{{ route('frontend.places') }}" 
               class="text-xs font-bold text-gymove-blue hover:text-gymove-orange transition-colors flex items-center gap-1">
                <span>Lihat Seluruh 50 Tempat</span>
                <span>→</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($featuredPlaces as $place)
                <div class="bg-white rounded-card p-5 border border-slate-100 shadow-sm hover:shadow-soft-hover transition-all group flex flex-col justify-between">
                    <div>
                        <!-- Header with ID & Ticket Price -->
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <span class="text-[10px] font-black px-2.5 py-1 rounded-lg bg-gymove-soft-blue text-gymove-blue uppercase tracking-wider font-mono">
                                {{ $place->id_tempat ?? 'TP' }}
                            </span>
                            <span class="text-[11px] font-extrabold px-2.5 py-0.5 rounded-full {{ stripos($place->biaya, 'gratis') !== false ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $place->biaya }}
                            </span>
                        </div>

                        <!-- Name & Address -->
                        <h3 class="text-base font-extrabold text-slate-800 group-hover:text-gymove-blue transition-colors line-clamp-1">
                            <a href="{{ route('frontend.places.show', $place) }}">
                                {{ $place->nama_lokasi }}
                            </a>
                        </h3>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed flex items-start gap-1">
                            <svg class="w-3.5 h-3.5 text-gymove-orange shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $place->alamat_kawasan }}</span>
                        </p>

                        <!-- Operational Hours -->
                        <div class="flex items-center gap-1.5 mt-3 text-xs text-slate-400 font-medium">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $place->jam_operasional }}</span>
                        </div>

                        <!-- Matched Subject Tags -->
                        <div class="flex flex-wrap gap-1.5 mt-3 pt-3 border-t border-slate-100">
                            @foreach($place->subjects->take(3) as $sub)
                                <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg"
                                      style="background-color: {{ $sub->warna_bg_soft }}; color: {{ $sub->warna_teks }};">
                                    {{ $sub->nama_mapel }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between">
                        <span class="text-[11px] font-bold text-emerald-600 flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            {{ $place->status }}
                        </span>
                        <a href="{{ route('frontend.places.show', $place) }}" 
                           class="text-xs font-extrabold text-gymove-blue hover:text-gymove-orange transition-colors flex items-center gap-1">
                            <span>Detail Tempat</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 6. FINAL INSPIRING CTA BANNER -->
    <!-- ============================================== -->
    <div class="bg-gradient-to-r from-gymove-orange to-[#f57418] rounded-card p-8 md:p-10 text-white shadow-orange-glow text-center space-y-4">
        <h2 class="text-2xl sm:text-3xl font-black tracking-tight">
            Siap Memulai Petualangan Belajar Luar Ruangmu?
        </h2>
        <p class="text-xs sm:text-sm text-white/90 max-w-xl mx-auto leading-relaxed">
            Pilih sekolahmu, tentukan mata pelajaran yang ingin kamu dalami, dan dapatkan daftar tempat terbaik yang siap kamu kunjungi hari ini!
        </p>
        <div class="pt-2">
            <a href="{{ route('frontend.recommendations') }}" 
               class="px-8 py-4 rounded-full bg-white text-gymove-orange hover:bg-slate-100 font-black text-sm shadow-xl transition-all inline-flex items-center gap-2 active:scale-95">
                <span>Dapatkan Rekomendasi Sekarang</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>

</div>
@endsection
