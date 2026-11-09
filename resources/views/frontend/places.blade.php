@extends('layouts.student')

@section('title', 'Direktori 50 Tempat Belajar Kota Bengkulu | EDU PESISIR')
@section('page_title', 'Direktori Tempat Belajar Edukatif')

@section('content')
<div class="space-y-6">

    <!-- Header & Search Controls -->
    <div class="bg-white rounded-card p-6 shadow-soft-card border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-5">
        <div>
            <h2 class="text-xl font-black text-slate-900 tracking-tight">
                Daftar Seluruh Tempat Belajar di Kota Bengkulu
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Menampilkan <strong>{{ $places->count() }}</strong> dari total <strong>{{ $totalCount }}</strong> lokasi edukasi terdaftar.
            </p>
        </div>

        <!-- Filter Form -->
        <form action="/tempat" method="GET" class="flex items-center gap-2.5 w-full md:w-auto">
            <div class="relative flex-1 md:w-72">
                <input type="text" 
                       name="q" 
                       value="{{ $searchQuery }}"
                       placeholder="Cari nama, alamat, atau topik..." 
                       class="w-full bg-gymove-pill hover:bg-slate-100 focus:bg-white text-slate-700 text-xs font-medium pl-4 pr-9 py-2.5 rounded-full border border-slate-200 focus:border-gymove-blue focus:outline-none transition-all shadow-inner" />
                <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-gymove-blue">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>

            @if(!empty($searchQuery))
                <a href="{{ route('frontend.places') }}" class="px-3.5 py-2.5 rounded-full bg-slate-100 text-slate-500 hover:text-slate-800 text-xs font-bold shrink-0">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- 50 Places Grid Cards -->
    @if($places->isEmpty())
        <div class="bg-white rounded-card p-12 text-center border border-slate-100">
            <p class="text-sm font-bold text-slate-500">Tidak ada tempat belajar yang sesuai dengan kata kunci pencarian "{{ $searchQuery }}".</p>
            <div class="mt-4">
                <a href="{{ route('frontend.places') }}" class="px-5 py-2 rounded-full bg-gymove-blue text-white text-xs font-bold">
                    Lihat Semua Tempat
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($places as $loc)
                <div class="bg-white rounded-card p-5 border border-slate-100 shadow-sm hover:shadow-soft-hover transition-all group flex flex-col justify-between">
                    <div>
                        <!-- Top meta: ID & Price -->
                        <div class="flex items-start justify-between gap-3 mb-2.5">
                            <span class="text-[10px] font-black px-2.5 py-1 rounded-lg bg-gymove-soft-blue text-gymove-blue uppercase tracking-wider font-mono">
                                {{ $loc->id_tempat ?? 'TP' }}
                            </span>
                            <span class="text-[11px] font-extrabold px-2.5 py-0.5 rounded-full {{ stripos($loc->biaya, 'gratis') !== false ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $loc->biaya }}
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="text-base font-extrabold text-slate-900 group-hover:text-gymove-blue transition-colors line-clamp-1">
                            <a href="{{ route('frontend.places.show', $loc) }}">
                                {{ $loc->nama_lokasi }}
                            </a>
                        </h3>

                        <!-- Address -->
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed flex items-start gap-1">
                            <svg class="w-3.5 h-3.5 text-gymove-orange shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $loc->alamat_kawasan }}</span>
                        </p>

                        <!-- Hours & Status -->
                        <div class="flex items-center gap-3 mt-2.5 text-xs text-slate-400 font-medium">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $loc->jam_operasional }}</span>
                            </span>
                            <span>•</span>
                            <span class="text-emerald-600 font-bold">
                                {{ $loc->status }}
                            </span>
                        </div>

                        <!-- Subjects Chips -->
                        <div class="flex flex-wrap gap-1.5 mt-3 pt-3 border-t border-slate-100">
                            @foreach($loc->subjects as $sub)
                                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-md"
                                      style="background-color: {{ $sub->warna_bg_soft }}; color: {{ $sub->warna_teks }};">
                                    {{ $sub->nama_mapel }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between">
                        <a href="{{ route('frontend.places.map', ['location' => $loc->id, 'school' => session('user_school_id')]) }}" 
                           class="text-[11px] font-bold text-gymove-orange hover:text-gymove-orange-hover flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            <span>Peta Rute</span>
                        </a>

                        <a href="{{ route('frontend.places.show', ['location' => $loc->id, 'school' => session('user_school_id')]) }}" 
                           class="px-4 py-1.5 rounded-full bg-gymove-soft-blue text-gymove-blue hover:bg-gymove-blue hover:text-white text-xs font-bold transition-all">
                            Detail Lengkap →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
