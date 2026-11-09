@extends('layouts.app')

@section('title', 'PESISIR-EDU | Rekomendasi Lokasi Pembelajaran Pesisir Kota Bengkulu (SAW & WebGIS)')

@section('content')
<div x-data="pesisirApp()" x-init="initApp()" class="flex min-h-screen bg-gymove-canvas">
    
    <!-- 1. LEFT SIDEBAR (Gymove 3-Column Layout) -->
    @include('pesisir.partials.sidebar')

    <!-- MAIN BODY (Header + Middle Column + Right Panel) -->
    <div class="flex-1 flex flex-col min-w-0">
        
        <!-- 2. TOP HEADER BAR -->
        @include('pesisir.partials.header')

        <!-- MAIN CONTENT + RIGHT PANEL WRAPPER -->
        <main class="flex-1 px-8 pb-10 flex flex-col xl:flex-row gap-7 min-w-0">
            
            <!-- 3. MIDDLE COLUMN: Control Card & Signature Recommendation List -->
            <div class="flex-1 min-w-0">
                <!-- Control Panel: Multi-Subject Selector & Weights -->
                @include('pesisir.partials.control_panel')

                <!-- Recommendation Rows (Gymove Style) -->
                @include('pesisir.partials.recommendation_list')
            </div>

            <!-- 4. RIGHT CONTEXTUAL PANEL: WebGIS Mini-Map & Parameter SPK SAW -->
            @include('pesisir.partials.right_panel')

        </main>
    </div>

    <!-- Modals -->
    @include('pesisir.partials.saw_modal')
    @include('pesisir.partials.create_location_modal')
    @include('pesisir.partials.guide_modal')
</div>
@endsection

@push('scripts')
<script>
function pesisirApp() {
    return {
        // App states
        activeTab: 'rekomendasi',
        rightTab: 'map',
        filterTab: 'all',
        searchQuery: '',
        isLoading: false,

        // Modal states
        showSawModal: false,
        showGuideModal: false,

        // Data from Laravel backend
        selectedSubjects: @json($selectedSubjectIds),
        allSubjectsData: @json($allSubjects),
        departurePresets: @json($departurePresets),
        selectedPreset: @json($originPreset),
        originLat: {{ $originLat }},
        originLng: {{ $originLng }},
        originName: @json($originName),
        weights: @json($weights),

        // Recommendation Results from Backend
        currentScenario: @json($recommendationData['scenario']),
        scenarioLabel: @json($recommendationData['scenario_label']),
        scenarioDescription: @json($recommendationData['scenario_description']),
        results: @json($recommendationData['results']),
        sawData: @json($recommendationData['saw_details'] ?? null),
        allLocations: @json($allLocations),

        // Leaflet references
        map: null,
        markersLayer: null,
        routeLayer: null,
        originMarker: null,

        // Filtered Results for Search
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

        // Init App & Leaflet WebGIS
        initApp() {
            this.$nextTick(() => {
                this.initMap();
                this.renderMapElements();
            });
        },

        initMap() {
            const mapContainer = document.getElementById('coastal-mini-map');
            if (!mapContainer || this.map) return;

            // Centered on coastal Bengkulu
            this.map = L.map('coastal-mini-map', {
                zoomControl: true,
                attributionControl: false
            }).setView([-3.8100, 102.2600], 12);

            // Free high-quality OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
            }).addTo(this.map);

            this.markersLayer = L.layerGroup().addTo(this.map);
            this.routeLayer = L.layerGroup().addTo(this.map);

            // Map click listener to set custom origin
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
            if (this.map) {
                this.map.invalidateSize();
            }
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

            // 1. Origin School Marker
            this.updateOriginMarker();

            // 2. Coastal Location Markers
            this.allLocations.forEach(loc => {
                const isSelectedScenario1 = this.currentScenario === 'SINGLE_MATCH' && 
                    this.results.some(r => r.location.id === loc.id);

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
                            <span>Fasilitas (C3): <strong>${loc.fasilitas_skor}/100</strong></span>
                            <span class="font-bold text-emerald-600">Keamanan (C4): <strong>${loc.keamanan_skor}/100</strong></span>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupHtml);
                this.markersLayer.addLayer(marker);
            });

            // 3. If Scenario 2 (Nearby locations), draw connecting polyline in Orange & Blue
            if (this.currentScenario === 'MULTI_LOCATION_NEARBY' && this.results.length > 0) {
                const bestPkg = this.results[0];
                if (bestPkg && bestPkg.route_coords && bestPkg.route_coords.length >= 2) {
                    // Draw connecting line between nearby locations
                    const polyline = L.polyline(bestPkg.route_coords, {
                        color: '#FF8A2B',
                        weight: 4,
                        opacity: 0.9,
                        dashArray: '8, 8',
                    });
                    this.routeLayer.addLayer(polyline);

                    // Fit map bounds to show route
                    this.map.fitBounds(polyline.getBounds(), { padding: [40, 40] });
                }
            } else if (this.currentScenario === 'SINGLE_MATCH' && this.results.length > 0) {
                // Focus on rank 1 location
                const best = this.results[0];
                if (best) {
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

            // Draw line from school to this location
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

            // Connect from origin to first point
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
            if (tab === 'map') {
                setTimeout(() => this.invalidateMap(), 200);
            }
        },

        focusMap() {
            this.rightTab = 'map';
            setTimeout(() => this.invalidateMap(), 200);
            const mapEl = document.getElementById('coastal-mini-map');
            if (mapEl) {
                mapEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        },

        focusSelector() {
            const el = document.getElementById('control-panel-container');
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        },

        // Multi-Subject Selection
        toggleSubject(id) {
            if (this.selectedSubjects.includes(id)) {
                if (this.selectedSubjects.length > 1) {
                    this.selectedSubjects = this.selectedSubjects.filter(s => s !== id);
                    this.triggerCalculation();
                } else {
                    alert('Minimal 1 mata pelajaran harus dipilih.');
                }
            } else {
                this.selectedSubjects.push(id);
                this.triggerCalculation();
            }
        },

        applyPreset(kodes) {
            const ids = this.allSubjectsData
                .filter(s => kodes.includes(s.kode_mapel))
                .map(s => s.id);
            if (ids.length > 0) {
                this.selectedSubjects = ids;
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

        // Trigger Laravel Calculation API
        async triggerCalculation() {
            this.isLoading = true;
            try {
                const response = await fetch('{{ route("api.calculate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        subject_ids: this.selectedSubjects,
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

                    // Update Leaflet map
                    this.renderMapElements();
                } else {
                    alert(res.message || 'Gagal memproses perhitungan SPK.');
                }
            } catch (err) {
                console.error(err);
            } finally {
                this.isLoading = false;
            }
        },

    }
}
</script>
@endpush
