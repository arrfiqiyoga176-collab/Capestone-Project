@extends('layouts.app')

@section('title', 'PESISIR-EDU | Portal Siswa - Rekomendasi Lokasi Pembelajaran Luar Ruang Bengkulu')

@section('content')
<div x-data="pesisirApp()" x-init="initApp()" class="flex min-h-screen bg-gymove-canvas">
    
    <!-- 1. LEFT SIDEBAR (Frontend User Navigation) -->
    <aside class="w-64 bg-white flex flex-col justify-between shrink-0 min-h-screen border-r border-slate-100 shadow-[4px_0_30px_rgba(19,41,133,0.02)] z-30">
        <div>
            <!-- Brand Logo Header -->
            <div class="p-6 pb-8 flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#3B28CC] to-[#132985] flex items-center justify-center text-white shadow-md shadow-indigo-950/20">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-1">
                        <span class="text-xl font-extrabold tracking-tight text-slate-900">PesisirEdu<span class="text-gymove-orange">.</span></span>
                    </div>
                    <p class="text-[11px] font-semibold text-slate-400 tracking-wide uppercase">Portal Siswa</p>
                </div>
            </div>

            <!-- Domain Portal Siswa (Eksklusif Ruang Lingkup Pembelajaran) -->
            <div class="px-6 pb-4">
                <div class="p-3 rounded-2xl bg-indigo-50/70 border border-indigo-100/70 flex items-center justify-between">
                    <div class="min-w-0">
                        <span class="text-[10px] font-black uppercase tracking-wider text-gymove-blue block">Portal Pembelajaran</span>
                        <span class="text-xs font-bold text-slate-800">Siswa Bengkulu</span>
                    </div>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-100 shrink-0" title="Sistem Aktif & Terverifikasi"></span>
                </div>
            </div>

            <div class="px-6 pb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                Menu Pembelajaran
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1.5 px-3">
                <a href="{{ route('home') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold bg-gymove-soft-blue text-gymove-blue border-r-4 border-gymove-blue font-bold shadow-sm transition-all">
                    <div class="w-9 h-9 rounded-xl bg-white text-gymove-blue shadow-sm flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <span>Rekomendasi (SAW)</span>
                </a>

                <a href="{{ route('frontend.map') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all group">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                    </div>
                    <span>Peta Spasial Pesisir</span>
                </a>

                <button type="button" 
                   @click="showSawModal = true"
                   class="w-full flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all group text-left">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span>Matriks SAW (X, R, V)</span>
                </button>

                <button type="button" 
                   @click="showGuideModal = true"
                   class="w-full flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all group text-left">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span>Panduan Siswa</span>
                </button>
            </nav>
        </div>

        <!-- Bottom CTA Card (Gymove Signature) -->
        <div class="p-5">
            <div class="bg-gymove-blue text-white rounded-2xl p-4.5 shadow-blue-glow relative overflow-hidden group cursor-pointer transition-all hover:scale-[1.02]"
                 @click="focusSelector()">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-white/10 blur-sm pointer-events-none"></div>
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center text-white shrink-0 group-hover:bg-gymove-orange transition-colors">
                        <svg class="w-6 h-6 text-gymove-orange group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm leading-tight text-white">Simulasi SAW Cepat</h4>
                        <p class="text-[11px] text-white/70 font-medium mt-0.5">Tes Skenario 1 & 2</p>
                    </div>
                </div>
            </div>

            <div class="mt-5 text-[11px] text-slate-400 font-medium leading-relaxed text-center">
                PESISIR-EDU Portal Siswa<br>
                <span class="text-slate-300">© 2026 Kota Bengkulu</span>
                <div class="mt-1.5">
                    <a href="{{ route('admin.login') }}" class="text-[10px] text-slate-400 hover:text-slate-600 transition-colors">Akses Pengelola</a>
                </div>
            </div>
        </div>
    </aside>

    <!-- 2. MAIN BODY (Header + Middle Column + Right Panel) -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Header with Admin Link Button -->
        <header class="h-20 bg-transparent px-8 flex items-center justify-between gap-6 shrink-0 z-20">
            <div class="flex items-center gap-4">
                <button type="button" class="w-10 h-10 rounded-2xl bg-white shadow-sm flex items-center justify-center text-slate-600 hover:text-gymove-blue transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h10M4 18h16" />
                    </svg>
                </button>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Rekomendasi Lokasi Belajar</h1>
                </div>
            </div>

            <!-- Search Bar Capsule -->
            <div class="flex-1 max-w-md mx-4">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" 
                           x-model="searchQuery" 
                           placeholder="Cari pantai, benteng, atau mata pelajaran..." 
                           class="w-full bg-gymove-pill hover:bg-slate-100/90 focus:bg-white text-slate-700 text-sm font-medium pl-11 pr-5 py-2.5 rounded-full border border-transparent focus:border-gymove-blue/30 focus:outline-none transition-all placeholder:text-slate-400 shadow-inner shadow-slate-200/30" />
                </div>
            </div>

            <!-- Right Header Actions + Switch to Admin -->
            <div class="flex items-center gap-4">
                <!-- Educational Action: Guide Modal Trigger -->
                <button type="button" 
                        @click="showGuideModal = true"
                        class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-full bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-all border border-slate-200/80 shadow-sm">
                    <svg class="w-4 h-4 text-gymove-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Panduan Pembelajaran</span>
                </button>

                <div class="flex items-center gap-2.5">
                    <button type="button" 
                            @click="showSawModal = true"
                            title="Matriks Detail Perhitungan SAW"
                            class="w-11 h-11 rounded-2xl bg-gymove-soft-blue text-gymove-blue hover:bg-gymove-blue hover:text-white transition-all flex items-center justify-center relative shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </button>

                    <button type="button" 
                            @click="showGuideModal = true"
                            title="Panduan Sistem SPK"
                            class="w-11 h-11 rounded-2xl bg-gymove-soft-blue text-gymove-blue hover:bg-gymove-blue hover:text-white transition-all flex items-center justify-center relative shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>

                <div class="h-8 w-px bg-slate-200"></div>

                <!-- User Profile -->
                <div class="flex items-center gap-3 pl-1">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop&crop=faces" 
                         alt="Avatar" 
                         class="w-11 h-11 rounded-2xl object-cover ring-2 ring-white shadow-sm" />
                    <div class="text-left hidden sm:block">
                        <h4 class="text-sm font-extrabold text-slate-800 leading-tight">Siswa Pelajar</h4>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700">Siswa Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT + RIGHT PANEL WRAPPER -->
        <main class="flex-1 px-8 pb-10 flex flex-col xl:flex-row gap-7 min-w-0">
            <!-- 3. MIDDLE COLUMN -->
            <div class="flex-1 min-w-0">
                @include('pesisir.partials.control_panel')
                @include('pesisir.partials.recommendation_list')
            </div>

            <!-- 4. RIGHT CONTEXTUAL PANEL -->
            @include('pesisir.partials.right_panel')
        </main>
    </div>

    <!-- Modals (Khusus Visualisasi & Panduan Pengguna) -->
    @include('pesisir.partials.saw_modal')
    @include('pesisir.partials.guide_modal')
</div>
@endsection

@push('scripts')
<script>
function pesisirApp() {
    return {
        activeTab: 'rekomendasi',
        rightTab: 'map',
        filterTab: 'all',
        searchQuery: '',
        isLoading: false,

        showSawModal: false,
        showGuideModal: false,

        selectedSubjects: @json($selectedSubjectIds),
        allSubjectsData: @json($allSubjects),
        departurePresets: @json($departurePresets),
        selectedPreset: @json($originPreset),
        originLat: {{ $originLat }},
        originLng: {{ $originLng }},
        originName: @json($originName),
        weights: @json($weights),

        currentScenario: @json($recommendationData['scenario']),
        scenarioLabel: @json($recommendationData['scenario_label']),
        scenarioDescription: @json($recommendationData['scenario_description']),
        results: @json($recommendationData['results']),
        sawData: @json($recommendationData['saw_details'] ?? null),
        allLocations: @json($allLocations),

        map: null,
        markersLayer: null,
        routeLayer: null,
        originMarker: null,

        get filteredResults() {
            if (!this.results) return [];
            let list = this.results;

            if (this.filterTab === 'single') {
                if (this.currentScenario !== 'SINGLE_MATCH') return [];
            } else if (this.filterTab === 'nearby') {
                if (this.currentScenario !== 'MULTI_LOCATION_NEARBY') return [];
            }

            if (this.searchQuery.trim() !== '') {
                const q = this.searchQuery.toLowerCase();
                list = list.filter(item => {
                    if (this.currentScenario === 'SINGLE_MATCH') {
                        return item.location.nama_lokasi.toLowerCase().includes(q) ||
                               item.matched_topics.some(t => t.nama.toLowerCase().includes(q) || t.kode.toLowerCase().includes(q));
                    } else {
                        return item.package_name.toLowerCase().includes(q) ||
                               item.points.some(p => p.location.nama_lokasi.toLowerCase().includes(q) || p.subject.nama_mapel.toLowerCase().includes(q));
                    }
                });
            }

            return list;
        },

        initApp() {
            this.$nextTick(() => {
                this.initMap();
                this.renderMapElements();
            });
        },

        initMap() {
            const mapContainer = document.getElementById('coastal-mini-map');
            if (!mapContainer || this.map) return;

            this.map = L.map('coastal-mini-map', {
                zoomControl: true,
                attributionControl: false
            }).setView([-3.8100, 102.2600], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
            }).addTo(this.map);

            this.markersLayer = L.layerGroup().addTo(this.map);
            this.routeLayer = L.layerGroup().addTo(this.map);

            this.map.on('click', (e) => {
                if (this.selectedPreset === 'custom') {
                    this.originLat = e.latlng.lat;
                    this.originLng = e.latlng.lng;
                    this.originName = 'Titik Pilihan Peta';
                    this.updateOriginMarker();
                    this.triggerCalculation();
                }
            });
        },

        invalidateMap() {
            if (this.map) this.map.invalidateSize();
        },

        toggleMapExpansion() {
            const mapContainer = document.getElementById('coastal-mini-map');
            if (!mapContainer) return;

            if (mapContainer.classList.contains('h-64')) {
                mapContainer.classList.remove('h-64');
                mapContainer.classList.add('h-[480px]');
            } else {
                mapContainer.classList.remove('h-[480px]');
                mapContainer.classList.add('h-64');
            }
            setTimeout(() => this.invalidateMap(), 250);
        },

        renderMapElements() {
            if (!this.map || !this.markersLayer) return;

            this.markersLayer.clearLayers();
            this.routeLayer.clearLayers();

            this.updateOriginMarker();

            this.allLocations.forEach(loc => {
                const isSelectedScenario1 = this.currentScenario === 'SINGLE_MATCH' && 
                    this.results.some(r => r.location && r.location.id === loc.id);

                const iconHtml = `
                    <div class="relative cursor-pointer transition-transform hover:scale-125">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white font-extrabold shadow-md border-2 border-white ${isSelectedScenario1 ? 'bg-[#132985] ring-2 ring-[#FF8A2B]' : 'bg-[#3B28CC]'}">
                            <span class="text-xs">🌊</span>
                        </div>
                    </div>
                `;

                const customIcon = L.divIcon({
                    html: iconHtml,
                    className: 'custom-coastal-icon',
                    iconSize: [32, 32],
                    iconAnchor: [16, 16]
                });

                const marker = L.marker([loc.latitude, loc.longitude], { icon: customIcon });

                const popupHtml = `
                    <div class="p-2 text-xs leading-relaxed max-w-[220px]">
                        <h4 class="font-extrabold text-sm text-[#132985]">${loc.nama_lokasi}</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">${loc.alamat_kawasan}</p>
                        <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                            <span>Fasilitas (C3): <strong>${loc.skor_fasilitas_c3}/100</strong></span>
                            <span class="font-bold text-emerald-600">Keamanan (C4): ${loc.skor_keamanan_c4}/100</span>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupHtml);
                this.markersLayer.addLayer(marker);
            });

            if (this.currentScenario === 'MULTI_LOCATION_NEARBY' && this.results.length > 0) {
                const bestPkg = this.results[0];
                if (bestPkg && bestPkg.route_coords && bestPkg.route_coords.length >= 2) {
                    const polyline = L.polyline(bestPkg.route_coords, {
                        color: '#FF8A2B',
                        weight: 4,
                        opacity: 0.9,
                        dashArray: '8, 8',
                    });
                    this.routeLayer.addLayer(polyline);
                    this.map.fitBounds(polyline.getBounds(), { padding: [40, 40] });
                }
            } else if (this.currentScenario === 'SINGLE_MATCH' && this.results.length > 0) {
                const best = this.results[0];
                if (best && best.location) {
                    const line = L.polyline([
                        [this.originLat, this.originLng],
                        [best.location.latitude, best.location.longitude]
                    ], {
                        color: '#132985',
                        weight: 3,
                        dashArray: '5, 5',
                        opacity: 0.7
                    });
                    this.routeLayer.addLayer(line);
                }
            }
        },

        updateOriginMarker() {
            if (this.originMarker) {
                this.markersLayer.removeLayer(this.originMarker);
            }

            const schoolIcon = L.divIcon({
                html: `
                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shadow-lg border-2 border-white ring-2 ring-emerald-300">
                        🏫
                    </div>
                `,
                className: 'school-marker',
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });

            this.originMarker = L.marker([this.originLat, this.originLng], { icon: schoolIcon });
            this.originMarker.bindPopup(`
                <div class="p-2 text-xs">
                    <strong class="font-extrabold text-slate-800 text-sm">${this.originName}</strong>
                    <p class="text-slate-500 text-[11px] mt-0.5">Titik Keberangkatan Siswa</p>
                </div>
            `);
            this.markersLayer.addLayer(this.originMarker);
        },

        focusLocationOnMap(loc, topics) {
            this.rightTab = 'map';
            this.switchRightTab('map');
            if (!this.map) return;

            this.map.flyTo([loc.latitude, loc.longitude], 15, { duration: 1.2 });
            this.routeLayer.clearLayers();

            const line = L.polyline([
                [this.originLat, this.originLng],
                [loc.latitude, loc.longitude]
            ], {
                color: '#FF8A2B',
                weight: 4,
                opacity: 0.85
            });
            this.routeLayer.addLayer(line);
        },

        focusPackageRoute(pkg) {
            this.rightTab = 'map';
            this.switchRightTab('map');
            if (!this.map || !pkg.route_coords) return;

            this.routeLayer.clearLayers();
            const polyline = L.polyline(pkg.route_coords, {
                color: '#FF8A2B',
                weight: 5,
                opacity: 0.95,
                dashArray: '8, 8'
            });
            this.routeLayer.addLayer(polyline);

            const originConnect = L.polyline([
                [this.originLat, this.originLng],
                pkg.route_coords[0]
            ], {
                color: '#132985',
                weight: 3,
                opacity: 0.6,
                dashArray: '4, 4'
            });
            this.routeLayer.addLayer(originConnect);

            this.map.fitBounds(polyline.getBounds(), { padding: [50, 50] });
        },

        switchRightTab(tab) {
            this.rightTab = tab;
            if (tab === 'map') setTimeout(() => this.invalidateMap(), 200);
        },

        focusMap() {
            this.rightTab = 'map';
            setTimeout(() => this.invalidateMap(), 200);
            const mapEl = document.getElementById('coastal-mini-map');
            if (mapEl) mapEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        },

        focusSelector() {
            const el = document.getElementById('control-panel-container');
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        },

        toggleSubject(id) {
            id = Number(id);
            if (this.selectedSubjects.map(Number).includes(id)) {
                if (this.selectedSubjects.length > 1) {
                    this.selectedSubjects = this.selectedSubjects.filter(s => Number(s) !== id);
                    this.triggerCalculation();
                } else {
                    alert('Minimal 1 mata pelajaran harus dipilih.');
                }
            } else {
                this.selectedSubjects = [...this.selectedSubjects, id];
                this.triggerCalculation();
            }
        },

        applyPreset(kodes) {
            const ids = this.allSubjectsData
                .filter(s => kodes.includes(s.kode_mapel))
                .map(s => Number(s.id));
            if (ids.length > 0) {
                this.selectedSubjects = [...ids];
                this.triggerCalculation();
            }
        },

        handlePresetChange() {
            if (this.selectedPreset === 'custom') {
                alert('Silakan klik di area peta untuk menentukan koordinat kustom.');
                this.switchRightTab('map');
                return;
            }
            const preset = this.departurePresets.find(p => p.id === this.selectedPreset);
            if (preset) {
                this.originLat = preset.lat;
                this.originLng = preset.lng;
                this.originName = preset.name;
                this.updateOriginMarker();
                this.triggerCalculation();
            }
        },

        resetWeights() {
            this.weights = { c1: 0.35, c2: 0.25, c3: 0.20, c4: 0.20 };
            this.triggerCalculation();
        },

        async triggerCalculation() {
            this.isLoading = true;
            try {
                const response = await fetch('{{ route("api.calculate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        subject_ids: this.selectedSubjects.map(Number),
                        origin_lat: this.originLat,
                        origin_lng: this.originLng,
                        origin_name: this.originName,
                        w_c1: this.weights.c1,
                        w_c2: this.weights.c2,
                        w_c3: this.weights.c3,
                        w_c4: this.weights.c4
                    })
                });

                const res = await response.json();
                if (res.success) {
                    this.currentScenario = res.data.scenario;
                    this.scenarioLabel = res.data.scenario_label;
                    this.scenarioDescription = res.data.scenario_description;
                    this.results = res.data.results;
                    this.sawData = res.data.saw_details;
                    this.renderMapElements();
                }
            } catch (err) {
                console.error(err);
            } finally {
                this.isLoading = false;
            }
        }
    };
}
</script>
@endpush
