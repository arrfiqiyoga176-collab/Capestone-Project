<div class="w-full xl:w-[360px] shrink-0 flex flex-col gap-6">
    <!-- Right Panel Main Card -->
    <div class="bg-white rounded-card p-6 shadow-soft-card border border-slate-100/80 relative transition-all">
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">Peta Spasial Lokasi</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Visualisasi rute & posisi pesisir</p>
            </div>

            <!-- Fullscreen map toggle -->
            <button type="button" 
                    @click="toggleMapExpansion()" 
                    title="Perbesar / Perkecil Ukuran Peta"
                    class="w-8 h-8 rounded-xl bg-gymove-soft-blue text-gymove-blue hover:bg-gymove-blue hover:text-white transition-colors flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                </svg>
            </button>
        </div>

        <!-- 1. LEAFLET MINI-MAP CONTAINER -->
        <div class="mt-4 transition-all">
            <div class="relative rounded-2xl overflow-hidden border border-slate-200/70 shadow-inner">
                <div id="coastal-mini-map" class="w-full h-64 bg-slate-100 z-10"></div>
                
                <!-- Map overlay badges -->
                <div class="absolute bottom-2 left-2 z-20 bg-white/95 backdrop-blur-md px-2.5 py-1 rounded-lg text-[10px] font-bold text-slate-700 shadow-sm border border-slate-200/60 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Pesisir Kota Bengkulu</span>
                </div>
            </div>
            
            <div class="mt-2.5 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                <span class="flex items-center gap-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-gymove-orange"></span>
                    <span>Rute Garis Haversine</span>
                </span>
                <span class="flex items-center gap-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-gymove-blue"></span>
                    <span>Pin Lokasi Terpilih</span>
                </span>
            </div>
        </div>

        <!-- 2. Section: Evaluasi Fasilitas (C3) & Keamanan (C4) -->
        <div class="mt-6 pt-5 border-t border-slate-100">
            <div class="flex items-center justify-between mb-3.5">
                <div>
                    <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Atribut Penilaian Lokasi</h4>
                    <span class="text-[10px] font-medium text-slate-400">Parameter Kriteria C3 & C4</span>
                </div>
                <span class="text-[10px] font-bold text-gymove-blue bg-gymove-soft-blue px-2 py-0.5 rounded-md">Metode SAW</span>
            </div>

            <!-- List of Criteria Highlights for Top Sites -->
            <div class="space-y-3">
                <template x-for="(loc, idx) in allLocations.slice(0, 3)" :key="loc.id">
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                        <div class="flex items-center justify-between">
                            <h5 class="text-xs font-bold text-slate-800 truncate" x-text="loc.nama_lokasi"></h5>
                            <span class="text-[10px] font-mono font-bold text-gymove-blue" x-text="'Skor C3: ' + loc.skor_fasilitas_c3"></span>
                        </div>
                        
                        <div class="space-y-1.5 text-[10px]">
                            <!-- Facility Bar -->
                            <div>
                                <div class="flex justify-between text-slate-500 mb-0.5">
                                    <span>Fasilitas Belajar (C3)</span>
                                    <span class="font-bold text-slate-700" x-text="loc.skor_fasilitas_c3 + '/100'"></span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-gymove-blue h-1.5 rounded-full" :style="'width: ' + loc.skor_fasilitas_c3 + '%'"></div>
                                </div>
                            </div>

                            <!-- Safety Bar -->
                            <div>
                                <div class="flex justify-between text-slate-500 mb-0.5">
                                    <span>Keamanan Siswa (C4)</span>
                                    <span class="font-bold text-slate-700" x-text="loc.skor_keamanan_c4 + '/100'"></span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-emerald-500 h-1.5 rounded-full" :style="'width: ' + loc.skor_keamanan_c4 + '%'"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- 3. Verification Advisory -->
        <div class="mt-5 p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
            <span class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span class="font-medium text-slate-600">8 Lokasi Edukatif Terdata</span>
            </span>
            <span class="font-bold text-slate-400">Kota Bengkulu</span>
        </div>
    </div>
</div>
