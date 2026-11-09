<div class="bg-white rounded-card p-7 shadow-soft-card border border-slate-100/80 transition-all">
    <!-- Card Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Daftar Rekomendasi Lokasi</h2>
                <!-- Dynamic Scenario Indicator Badge -->
                <span class="text-[11px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider"
                      :class="currentScenario === 'SINGLE_MATCH' 
                          ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' 
                          : 'bg-amber-50 text-amber-800 border border-amber-200'"
                      x-text="currentScenario === 'SINGLE_MATCH' ? 'Skenario 1: Lokasi Tunggal Terpadu' : 'Skenario 2: Paket Lokasi Berdekatan'">
                </span>
            </div>
            <p class="text-xs text-slate-500 font-medium mt-1" x-text="scenarioDescription">
                Evaluasi Multi-Kriteria SAW (C1 Relevansi, C2 Jarak Haversine, C3 Fasilitas, C4 Keamanan).
            </p>
        </div>

        <!-- Right Header: Segmented Pill Tabs & Filter Action -->
        <div class="flex items-center gap-3 flex-wrap">
            <div class="bg-gymove-pill p-1 rounded-full flex items-center gap-1 border border-slate-200/40">
                <button type="button" 
                        @click="filterTab = 'all'"
                        :class="filterTab === 'all' ? 'bg-white text-gymove-blue shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800 font-semibold'"
                        class="px-4 py-1.5 rounded-full text-xs transition-all">
                    Semua
                </button>
                <button type="button" 
                        @click="filterTab = 'single'"
                        :class="filterTab === 'single' ? 'bg-white text-gymove-blue shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800 font-semibold'"
                        class="px-4 py-1.5 rounded-full text-xs transition-all">
                    1 Lokasi Terpadu
                </button>
                <button type="button" 
                        @click="filterTab = 'nearby'"
                        :class="filterTab === 'nearby' ? 'bg-white text-gymove-blue shadow-sm font-bold' : 'text-slate-500 hover:text-slate-800 font-semibold'"
                        class="px-4 py-1.5 rounded-full text-xs transition-all">
                    Lokasi Berdekatan
                </button>
            </div>

            <button type="button" 
                    @click="showSawModal = true"
                    class="bg-gymove-blue hover:bg-gymove-blue-hover text-white rounded-full px-5 py-2 text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
                <span>Filter & Bobot</span>
            </button>
        </div>
    </div>

    <!-- Recommendation Rows Container -->
    <div class="mt-6 space-y-3.5">
        <!-- ======================================================== -->
        <!-- SKENARIO 1 ROWS: SINGLE INTEGRATED LOCATIONS -->
        <!-- ======================================================== -->
        <template x-if="currentScenario === 'SINGLE_MATCH'">
            <div>
                <template x-for="(item, index) in filteredResults" :key="item.location.id">
                    <div>
                        <!-- ROW PERINGKAT #1: HIGHLIGHTED ROYAL BLUE ROW -->
                        <template x-if="item.rank === 1">
                            <div class="bg-gymove-blue text-white rounded-2xl p-4.5 shadow-blue-glow transition-all hover:scale-[1.01] flex flex-col md:flex-row md:items-center justify-between gap-4 my-3 relative overflow-hidden group">
                                <div class="flex items-center gap-4">
                                    <!-- Squircle Rank & SAW Score Badge -->
                                    <div class="w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-sm flex flex-col items-center justify-center shrink-0 border border-white/20">
                                        <span class="text-[11px] font-extrabold uppercase tracking-widest text-gymove-orange">#1</span>
                                        <span class="text-base font-black leading-none text-white mt-0.5" x-text="item.v_score.toFixed(2)"></span>
                                        <span class="text-[9px] text-white/70 font-semibold">SAW</span>
                                    </div>

                                    <!-- Location Info -->
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="text-base font-extrabold text-white tracking-tight" x-text="item.location.nama_lokasi"></h3>
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-400/20 text-emerald-300 border border-emerald-400/30">
                                                ★ Rekomendasi Utama
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-3.5 mt-1 text-xs text-white/80 font-medium flex-wrap">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-gymove-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                </svg>
                                                <span x-text="item.distance_km + ' Km dari Asal'"></span>
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                                <span x-text="'Fasilitas (C3): ' + item.location.fasilitas_skor + '/100'"></span>
                                            </span>
                                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-md bg-emerald-400/20 text-emerald-200 border border-emerald-400/30"
                                                  x-text="'Keamanan (C4): ' + item.location.keamanan_skor + '/100'">
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Center-Right: Matched Subject Tags -->
                                <div class="flex items-center gap-2 flex-wrap">
                                    <template x-for="top in item.matched_topics" :key="top.kode">
                                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/10 backdrop-blur-sm text-white text-xs font-semibold border border-white/15">
                                            <span class="w-2 h-2 rounded-full bg-gymove-orange"></span>
                                            <span x-text="top.kode"></span>
                                        </div>
                                    </template>
                                </div>

                                <!-- Right: Action Button Vibrant Orange & Three Dots -->
                                <div class="flex items-center gap-3 shrink-0">
                                    <button type="button" 
                                            @click="focusLocationOnMap(item.location, item.matched_topics)"
                                            class="bg-gymove-orange hover:bg-gymove-orange-hover text-white font-extrabold rounded-full px-5 py-2.5 text-xs tracking-wide shadow-orange-glow transition-all flex items-center gap-1.5 active:scale-95 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span>Lihat Rute & Peta</span>
                                    </button>

                                    <!-- Three dots dropdown trigger -->
                                    <div class="relative" x-data="{ openDropdown: false }">
                                        <button type="button" 
                                                @click="openDropdown = !openDropdown"
                                                class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/25 text-white flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                        <div x-show="openDropdown" 
                                             @click.away="openDropdown = false" 
                                             x-cloak 
                                             class="absolute right-0 mt-2 w-48 bg-white text-slate-800 rounded-2xl shadow-xl py-2 z-50 border border-slate-100 text-xs font-semibold">
                                            <button type="button" @click="showSawModal = true; openDropdown = false" class="w-full px-4 py-2 text-left hover:bg-slate-50 flex items-center gap-2">
                                                <span>📊 Detail Matriks SAW</span>
                                            </button>
                                            <button type="button" @click="focusLocationOnMap(item.location, item.matched_topics); openDropdown = false" class="w-full px-4 py-2 text-left hover:bg-slate-50 flex items-center gap-2">
                                                <span>📍 Fokus di Peta WebGIS</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- ROW PERINGKAT #2, #3, DST: CLEAN WHITE ROWS -->
                        <template x-if="item.rank > 1">
                            <div class="bg-white hover:bg-slate-50/80 rounded-2xl p-4.5 border border-slate-100 shadow-sm transition-all hover:scale-[1.005] flex flex-col md:flex-row md:items-center justify-between gap-4 my-2.5 group">
                                <div class="flex items-center gap-4">
                                    <!-- Squircle Rank Pastel Badge -->
                                    <div class="w-14 h-14 rounded-2xl bg-gymove-soft-blue text-gymove-blue flex flex-col items-center justify-center shrink-0 group-hover:bg-gymove-blue group-hover:text-white transition-colors">
                                        <span class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400 group-hover:text-gymove-orange" x-text="'#' + item.rank"></span>
                                        <span class="text-base font-black leading-none mt-0.5" x-text="item.v_score.toFixed(2)"></span>
                                        <span class="text-[9px] text-slate-400 group-hover:text-white/70 font-semibold">SAW</span>
                                    </div>

                                    <!-- Location Info -->
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="text-base font-extrabold text-slate-800 tracking-tight" x-text="item.location.nama_lokasi"></h3>
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                                TERPADU
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-3.5 mt-1 text-xs text-slate-500 font-medium flex-wrap">
                                            <span class="flex items-center gap-1 text-slate-600">
                                                <svg class="w-3.5 h-3.5 text-gymove-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                </svg>
                                                <span x-text="item.distance_km + ' Km dari Sekolah'"></span>
                                            </span>
                                            <span class="flex items-center gap-1 text-slate-500">
                                                <svg class="w-3.5 h-3.5 text-gymove-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                                <span x-text="'Fasilitas (C3): ' + item.location.fasilitas_skor + '/100'"></span>
                                            </span>
                                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200"
                                                  x-text="'Keamanan (C4): ' + item.location.keamanan_skor + '/100'">
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Center-Right: Matched Subject Tags -->
                                <div class="flex items-center gap-2 flex-wrap">
                                    <template x-for="top in item.matched_topics" :key="top.kode">
                                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold"
                                             :style="'background-color: ' + top.warna_bg_soft + '; color: ' + top.warna_teks + '; border: 1px solid ' + top.warna_teks + '30;'">
                                            <span class="w-2 h-2 rounded-full" :style="'background-color: ' + top.warna_teks"></span>
                                            <span x-text="top.kode"></span>
                                        </div>
                                    </template>
                                </div>

                                <!-- Right: Action Button Soft Blue & Three Dots -->
                                <div class="flex items-center gap-3 shrink-0">
                                    <button type="button" 
                                            @click="focusLocationOnMap(item.location, item.matched_topics)"
                                            class="bg-gymove-soft-blue hover:bg-gymove-blue text-gymove-blue hover:text-white rounded-full px-5 py-2.5 text-xs font-bold transition-all flex items-center gap-1.5 active:scale-95 cursor-pointer">
                                        <span>▶ Detail & Peta</span>
                                    </button>

                                    <!-- Three dots dropdown -->
                                    <div class="relative" x-data="{ openDropdown: false }">
                                        <button type="button" 
                                                @click="openDropdown = !openDropdown"
                                                class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                        <div x-show="openDropdown" 
                                             @click.away="openDropdown = false" 
                                             x-cloak 
                                             class="absolute right-0 mt-2 w-48 bg-white text-slate-800 rounded-2xl shadow-xl py-2 z-50 border border-slate-100 text-xs font-semibold">
                                            <button type="button" @click="showSawModal = true; openDropdown = false" class="w-full px-4 py-2 text-left hover:bg-slate-50 flex items-center gap-2">
                                                <span>📊 Detail Matriks SAW</span>
                                            </button>
                                            <button type="button" @click="focusLocationOnMap(item.location, item.matched_topics); openDropdown = false" class="w-full px-4 py-2 text-left hover:bg-slate-50 flex items-center gap-2">
                                                <span>📍 Fokus di Peta WebGIS</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </template>

        <!-- ======================================================== -->
        <!-- SKENARIO 2 ROWS: PROXIMITY CLUSTERING (NEARBY LOCATIONS) -->
        <!-- ======================================================== -->
        <template x-if="currentScenario === 'MULTI_LOCATION_NEARBY'">
            <div>
                <!-- Scenario 2 Explanatory Alert -->
                <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200/70 mb-4 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 font-bold text-sm">
                        ⚡
                    </div>
                    <div class="text-xs text-amber-900 leading-relaxed">
                        <strong class="font-extrabold block">Logika Revisi Dosen — Paket Lokasi Pesisir Berdekatan Aktif:</strong>
                        Mata pelajaran terpilih tidak tersedia pada 1 lokasi tunggal yang sama. Sistem mencari lokasi terbaik untuk masing-masing mata pelajaran dan memasangkannya berdasarkan <strong>jarak tempuh paling minimum (Haversine Formula)</strong> untuk kenyamanan rute kunjungan lapangan.
                    </div>
                </div>

                <template x-for="(pkg, index) in filteredResults" :key="index">
                    <div>
                        <!-- ROW PERINGKAT #1 PAKET BERDEKATAN (HIGHLIGHTED ROYAL BLUE ROW) -->
                        <template x-if="pkg.rank === 1">
                            <div class="bg-gymove-blue text-white rounded-2xl p-5 shadow-blue-glow transition-all hover:scale-[1.01] my-3 relative overflow-hidden group">
                                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <!-- Squircle Rank & Score Badge -->
                                        <div class="w-14 h-14 rounded-2xl bg-white/15 backdrop-blur-sm flex flex-col items-center justify-center shrink-0 border border-white/20">
                                            <span class="text-[11px] font-extrabold uppercase tracking-widest text-gymove-orange">#1</span>
                                            <span class="text-base font-black leading-none text-white mt-0.5" x-text="pkg.avg_saw_score.toFixed(2)"></span>
                                            <span class="text-[9px] text-white/70 font-semibold">SAW AVG</span>
                                        </div>

                                        <!-- Package Route Titles -->
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h3 class="text-base font-extrabold text-white tracking-tight" x-text="pkg.package_name"></h3>
                                                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-gymove-orange text-white shadow-sm">
                                                    Rute Terdekat
                                                </span>
                                            </div>

                                            <div class="flex items-center gap-3.5 mt-1.5 text-xs text-white/80 font-medium flex-wrap">
                                                <!-- Inter-location proximity distance badge -->
                                                <span class="flex items-center gap-1 font-bold text-amber-300">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                    </svg>
                                                    <span x-text="'Jarak Antar-Lokasi: ' + pkg.inter_distance_km + ' Km (' + pkg.inter_distance_m + ' Meter)'"></span>
                                                </span>
                                                <span>•</span>
                                                <span class="text-white/90" x-text="pkg.travel_time_est"></span>
                                                <span>•</span>
                                                <span class="text-white/70" x-text="'Total Rute: ' + pkg.total_trip_km + ' Km'"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Button -->
                                    <div class="flex items-center gap-3 shrink-0">
                                        <button type="button" 
                                                @click="focusPackageRoute(pkg)"
                                                class="bg-gymove-orange hover:bg-gymove-orange-hover text-white font-extrabold rounded-full px-5 py-2.5 text-xs tracking-wide shadow-orange-glow transition-all flex items-center gap-1.5 active:scale-95 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                            </svg>
                                            <span>Lihat Rute Sambungan</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Breakdown of 2 points in package -->
                                <div class="mt-4 pt-3.5 border-t border-white/10 grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                    <template x-for="(pt, pIdx) in pkg.points" :key="pIdx">
                                        <div class="bg-white/10 rounded-xl p-3 backdrop-blur-sm border border-white/10 flex items-start gap-2.5">
                                            <span class="w-5 h-5 rounded-full bg-white text-gymove-blue font-extrabold flex items-center justify-center text-[10px] shrink-0 mt-0.5" x-text="pt.order"></span>
                                            <div>
                                                <div class="font-extrabold text-white text-xs" x-text="pt.location.nama_lokasi"></div>
                                                <div class="text-[11px] text-white/80 mt-0.5">
                                                    Materi: <strong class="text-amber-300" x-text="pt.subject.nama_mapel"></strong>
                                                </div>
                                                <div class="text-[10px] text-white/60 italic mt-0.5" x-text="'&quot;' + pt.topic + '&quot;'"></div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- ROW PERINGKAT #2, #3 PAKET BERDEKATAN (CLEAN WHITE ROWS) -->
                        <template x-if="pkg.rank > 1">
                            <div class="bg-white hover:bg-slate-50/80 rounded-2xl p-5 border border-slate-100 shadow-sm transition-all hover:scale-[1.005] my-3">
                                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <!-- Squircle Rank Pastel Badge -->
                                        <div class="w-14 h-14 rounded-2xl bg-gymove-soft-blue text-gymove-blue flex flex-col items-center justify-center shrink-0">
                                            <span class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400" x-text="'#' + pkg.rank"></span>
                                            <span class="text-base font-black leading-none mt-0.5" x-text="pkg.avg_saw_score.toFixed(2)"></span>
                                            <span class="text-[9px] text-slate-400 font-semibold">SAW AVG</span>
                                        </div>

                                        <!-- Package Route Titles -->
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h3 class="text-base font-extrabold text-slate-800 tracking-tight" x-text="pkg.package_name"></h3>
                                                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700">
                                                    Alternatif Rute
                                                </span>
                                            </div>

                                            <div class="flex items-center gap-3.5 mt-1.5 text-xs text-slate-500 font-medium flex-wrap">
                                                <span class="flex items-center gap-1 font-bold text-gymove-orange">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                    </svg>
                                                    <span x-text="'Jarak Antar-Lokasi: ' + pkg.inter_distance_km + ' Km (' + pkg.inter_distance_m + 'm)'"></span>
                                                </span>
                                                <span>•</span>
                                                <span x-text="pkg.travel_time_est"></span>
                                                <span>•</span>
                                                <span x-text="'Total: ' + pkg.total_trip_km + ' Km'"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Button -->
                                    <div class="flex items-center gap-3 shrink-0">
                                        <button type="button" 
                                                @click="focusPackageRoute(pkg)"
                                                class="bg-gymove-soft-blue hover:bg-gymove-blue text-gymove-blue hover:text-white rounded-full px-5 py-2.5 text-xs font-bold transition-all flex items-center gap-1.5 active:scale-95 cursor-pointer">
                                            <span>▶ Detail & Rute Peta</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Breakdown of points -->
                                <div class="mt-4 pt-3.5 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                    <template x-for="(pt, pIdx) in pkg.points" :key="pIdx">
                                        <div class="bg-slate-50 rounded-xl p-3 border border-slate-200/50 flex items-start gap-2.5">
                                            <span class="w-5 h-5 rounded-full bg-gymove-blue text-white font-extrabold flex items-center justify-center text-[10px] shrink-0 mt-0.5" x-text="pt.order"></span>
                                            <div>
                                                <div class="font-bold text-slate-800 text-xs" x-text="pt.location.nama_lokasi"></div>
                                                <div class="text-[11px] text-slate-500 mt-0.5">
                                                    Mata Pelajaran: <strong class="text-gymove-blue" x-text="pt.subject.nama_mapel"></strong>
                                                </div>
                                                <div class="text-[10px] text-slate-400 italic mt-0.5" x-text="'&quot;' + pt.topic + '&quot;'"></div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </template>
    </div>

    <!-- Footer of Card: Count & Soft Squircle Pagination -->
    <div class="mt-8 pt-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="text-xs text-slate-400 font-semibold">
            Menampilkan <span class="font-extrabold text-slate-700" x-text="filteredResults.length"></span> dari rekomendasi terverifikasi pesisir
        </div>

        <!-- Gymove Soft Squircle Pagination -->
        <div class="flex items-center gap-1.5">
            <button type="button" class="w-8 h-8 rounded-xl bg-gymove-soft-blue text-gymove-blue hover:bg-slate-200 flex items-center justify-center font-bold text-xs transition-colors">
                ‹
            </button>
            <button type="button" class="w-8 h-8 rounded-xl bg-gymove-blue text-white shadow-sm flex items-center justify-center font-extrabold text-xs">
                1
            </button>
            <button type="button" class="w-8 h-8 rounded-xl bg-gymove-soft-blue text-slate-600 hover:bg-slate-200 flex items-center justify-center font-bold text-xs transition-colors">
                2
            </button>
            <button type="button" class="w-8 h-8 rounded-xl bg-gymove-soft-blue text-slate-600 hover:bg-slate-200 flex items-center justify-center font-bold text-xs transition-colors">
                3
            </button>
            <button type="button" class="w-8 h-8 rounded-xl bg-gymove-soft-blue text-gymove-blue hover:bg-slate-200 flex items-center justify-center font-bold text-xs transition-colors">
                ›
            </button>
        </div>
    </div>
</div>
