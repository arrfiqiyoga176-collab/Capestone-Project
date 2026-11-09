@extends('layouts.student')

@section('title', $location->nama_lokasi . ' | EDU PESISIR Kota Bengkulu')
@section('page_title', 'Detail Tempat Belajar')

@section('content')
<div class="space-y-7">

    <!-- Top Navigation / Breadcrumb -->
    <div class="flex items-center justify-between">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('frontend.places') }}" 
           class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-gymove-blue transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Halaman Sebelumnya</span>
        </a>

        <span class="text-xs font-mono font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-full">
            Kode: {{ $location->id_tempat ?? 'TP' }}
        </span>
    </div>

    <!-- Main Place Profile Card -->
    <div class="bg-white rounded-card p-7 shadow-soft-card border border-slate-100">
        
        <div class="flex flex-col lg:flex-row items-start justify-between gap-6 pb-6 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span class="text-xs font-black px-3 py-1 rounded-full bg-gymove-soft-blue text-gymove-blue uppercase tracking-wider font-mono">
                        {{ $location->id_tempat }}
                    </span>
                    <span class="text-xs font-bold px-3 py-1 rounded-full {{ stripos($location->biaya, 'gratis') !== false ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $location->biaya }}
                    </span>
                    <span class="text-xs font-bold px-3 py-1 rounded-full bg-slate-100 text-slate-700">
                        Status: <strong class="text-emerald-600">{{ $location->status }}</strong>
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ $location->nama_lokasi }}
                </h1>
                
                <p class="text-xs sm:text-sm text-slate-500 mt-1.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gymove-orange shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                    <span>{{ $location->alamat_kawasan }}</span>
                </p>
            </div>

            <!-- Buka Rute Button (Internal Web Map) -->
            <div class="shrink-0 flex items-center gap-3">
                <a href="{{ route('frontend.places.map', ['location' => $location->id, 'school' => request('school') ?? session('user_school_id')]) }}" 
                   class="px-6 py-3 rounded-full bg-gymove-orange hover:bg-gymove-orange-hover text-white font-extrabold text-xs shadow-orange-glow transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <span>Buka Rute Perjalanan</span>
                </a>
            </div>
        </div>

        <!-- Description & Key Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-6">
            
            <!-- Left 2 Cols: Description & Topics -->
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Deskripsi Tempat Belajar:</h3>
                    <p class="text-sm text-slate-700 leading-relaxed bg-slate-50 p-5 rounded-2xl border border-slate-100">
                        {{ $location->deskripsi }}
                    </p>
                </div>

                <!-- Mata Pelajaran & Kurikulum Terkait -->
                <div>
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Mata Pelajaran yang Dapat Dipelajari di Lokasi Ini:</h3>
                    <div class="space-y-3">
                        @foreach($location->subjects as $subj)
                            <div class="p-4 rounded-2xl border border-slate-100 flex items-start gap-4 transition-all hover:bg-slate-50/60"
                                 style="border-left: 4px solid {{ $subj->warna_teks }};">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold shrink-0"
                                     style="background-color: {{ $subj->warna_bg_soft }}; color: {{ $subj->warna_teks }};">
                                    {{ $subj->kode_mapel }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-extrabold text-slate-800">{{ $subj->nama_mapel }}</h4>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1">
                                        {{ $subj->pivot->topik_kajian ?? $subj->deskripsi }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Col: Specifications & Operational Hours -->
            <div class="space-y-5">
                <div class="p-5 rounded-2xl bg-gymove-soft-blue border border-indigo-100/60 space-y-4">
                    <h4 class="text-xs font-black uppercase tracking-wider text-gymove-blue">Informasi Kunjungan</h4>
                    
                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Jam Operasional:</span>
                            <strong class="text-slate-800">{{ $location->jam_operasional }}</strong>
                        </div>
                        <div class="h-px bg-slate-200/60"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Biaya Masuk:</span>
                            <strong class="text-slate-800 text-right">{{ $location->biaya }}</strong>
                        </div>
                        <div class="h-px bg-slate-200/60"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Status Operasional:</span>
                            <span class="font-extrabold text-emerald-600">{{ $location->status }}</span>
                        </div>
                        <div class="h-px bg-slate-200/60"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Koordinat GPS:</span>
                            <span class="font-mono text-slate-600">{{ round($location->latitude, 4) }}, {{ round($location->longitude, 4) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Distance from Bengkulu Schools -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-3">Estimasi Jarak dari Sekolah:</h4>
                    <div class="max-h-60 overflow-y-auto space-y-2 pr-1 text-xs">
                        @foreach(array_slice($distances, 0, 8) as $d)
                            <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                                <span class="text-slate-600 truncate mr-2">{{ $d['school'] }}</span>
                                <strong class="text-gymove-orange shrink-0">{{ $d['distance_km'] }} Km</strong>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Related Places -->
    @if(!$relatedPlaces->isEmpty())
        <div>
            <h3 class="text-base font-extrabold text-slate-900 tracking-tight mb-4">Tempat Belajar Lain dengan Topik Serupa</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach($relatedPlaces as $rel)
                    <div class="bg-white rounded-card p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-gymove-blue bg-gymove-soft-blue px-2.5 py-0.5 rounded-full font-mono">
                                {{ $rel->id_tempat }}
                            </span>
                            <h4 class="text-sm font-extrabold text-slate-800 mt-2">
                                <a href="{{ route('frontend.places.show', $rel) }}" class="hover:text-gymove-blue">
                                    {{ $rel->nama_lokasi }}
                                </a>
                            </h4>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                                {{ $rel->alamat_kawasan }}
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-400">{{ $rel->jam_operasional }}</span>
                            <a href="{{ route('frontend.places.show', $rel) }}" class="font-bold text-gymove-blue hover:text-gymove-orange">
                                Detail →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
