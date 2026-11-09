@extends('layouts.app')

@section('title', 'Peta Spasial WebGIS | PESISIR-EDU Kota Bengkulu')

@section('content')
<div x-data="gisExplorer()" x-init="initMap()" class="relative h-screen w-screen overflow-hidden flex bg-slate-900 font-sans">

    <!-- 1. FLOATING CONTROL SIDEBAR (Left) -->
    <div class="absolute top-4 left-4 z-[1000] w-96 max-h-[calc(100vh-2rem)] flex flex-col bg-white/95 backdrop-blur-md rounded-card shadow-2xl border border-slate-100 overflow-hidden transition-all">
        
        <!-- Header -->
        <div class="p-5 pb-4 border-b border-slate-100 shrink-0">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gymove-blue flex items-center justify-center text-white shadow-md">
                        <svg class="w-5 h-5 text-gymove-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900 leading-tight">WebGIS Pesisir Bengkulu</h2>
                        <span class="text-[10px] font-bold text-gymove-blue tracking-wider uppercase">Peta Lokasi Belajar Luar Ruang</span>
                    </div>
                </div>

                <a href="{{ route('home') }}" 
                   class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors flex items-center gap-1.5"
                   title="Kembali ke Dashboard SPK Rekomendasi">
                    <span>← Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Scrollable Control Body -->
        <div class="flex-1 overflow-y-auto p-5 space-y-5 text-xs">
            
            <!-- Departure Point Selector -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                    Titik Keberangkatan Sekolah:
                </label>
                <select x-model="selectedOrigin" @change="updateOrigin()" class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-gymove-blue">
                    @foreach($departurePresets as $preset)
                    <option value="{{ $preset['id'] }}" data-lat="{{ $preset['lat'] }}" data-lng="{{ $preset['lng'] }}">
                        {{ $preset['name'] }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Subject Multi-Filter -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        Filter Mata Pelajaran:
                    </label>
                    <button type="button" 
                            x-show="selectedSubjects.length > 0"
                            @click="selectedSubjects = []; filterMarkers()" 
                            class="text-[10px] font-bold text-rose-600 hover:underline">
                        Reset Filter
                    </button>
                </div>
                <div class="flex flex-wrap gap-1.5">
                    @foreach($allSubjects as $subj)
                    <button type="button"
                            @click="toggleSubject({{ $subj->id }})"
                            :class="selectedSubjects.includes({{ $subj->id }}) ? 'bg-gymove-blue text-white shadow-sm ring-2 ring-gymove-blue/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                            class="px-2.5 py-1.5 rounded-xl text-[11px] font-bold transition-all flex items-center gap-1">
                        <span>{{ $subj->nama_mapel }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Search Location Filter -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                    Cari Nama Kawasan / Pantai:
                </label>
                <div class="relative">
                    <input type="text" 
                           x-model="searchKeyword" 
                           @input="filterMarkers()"
                           placeholder="Ketik nama pantai atau kawasan..." 
                           class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 text-xs focus:outline-none focus:ring-2 focus:ring-gymove-blue">
                </div>
            </div>

            <!-- Locations Counter & List -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Lokasi Belajar Terverifikasi</span>
                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-bold" x-text="visibleLocations.length + ' Lokasi'"></span>
                </div>

                <div class="space-y-2 max-h-56 overflow-y-auto pr-1">
                    <template x-for="loc in visibleLocations" :key="loc.id">
                        <div @click="zoomToLocation(loc)" 
                             class="p-2.5 rounded-2xl bg-slate-50 hover:bg-gymove-soft-blue/60 border border-slate-100 cursor-pointer transition-all">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h4 class="font-bold text-slate-800" x-text="loc.nama_lokasi"></h4>
                                    <p class="text-[10px] text-slate-400 truncate" x-text="loc.alamat_kawasan"></p>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase shrink-0 bg-indigo-50 text-gymove-blue" x-text="(loc.subjects ? loc.subjects.length : 0) + ' Mapel'"></span>
                            </div>
                            <div class="mt-2 flex items-center justify-between text-[10px] text-slate-500 pt-1.5 border-t border-slate-200/50">
                                <span class="text-gymove-blue font-bold">Fasilitas (C3): <strong x-text="loc.skor_fasilitas_c3 + '/100'"></strong></span>
                                <span class="text-emerald-700 font-bold">Keamanan (C4): <strong x-text="loc.skor_keamanan_c4 + '/100'"></strong></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        <!-- Footer GIS Status Bar (Khusus Siswa) -->
        <div class="p-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 shrink-0">
            <span class="flex items-center gap-1.5 font-bold text-slate-700">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>8 Titik Kawasan Edukatif</span>
            </span>
            <span class="text-[10px] text-slate-400 font-medium">Sistem Pendukung Keputusan SAW</span>
        </div>
    </div>

    <!-- 2. MAP CANVAS -->
    <div id="fullWebGisMap" class="w-full h-full z-0"></div>

    <!-- 3. TOP RIGHT CONTROLS (Layers & Coordinates) -->
    <div class="absolute top-4 right-4 z-[1000] flex flex-col items-end gap-2">
        <!-- Basemap Toggle -->
        <div class="bg-white/95 backdrop-blur-md p-1.5 rounded-2xl shadow-lg border border-slate-100 flex items-center gap-1">
            <button type="button" 
                    @click="setBasemap('osm')" 
                    :class="activeBasemap === 'osm' ? 'bg-gymove-blue text-white' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all">
                Peta Jalan (OSM)
            </button>
            <button type="button" 
                    @click="setBasemap('satellite')" 
                    :class="activeBasemap === 'satellite' ? 'bg-gymove-blue text-white' : 'text-slate-600 hover:bg-slate-100'"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all">
                Satelit (ESRI)
            </button>
        </div>

        <!-- Coordinates Readout -->
        <div class="bg-slate-900/80 backdrop-blur-md text-white px-3.5 py-1.5 rounded-xl shadow-lg border border-white/10 text-[11px] font-mono flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
            <span>Bengkulu: -3.8004, 102.2655</span>
        </div>
    </div>

</div>

<script>
    function gisExplorer() {
        return {
            map: null,
            locations: @json($allLocations),
            departurePresets: @json($departurePresets),
            selectedOrigin: 'sman1',
            selectedSubjects: [],
            searchKeyword: '',
            visibleLocations: [],
            activeBasemap: 'osm',
            tileLayers: {},
            markersGroup: null,
            originMarker: null,

            initMap() {
                this.visibleLocations = [...this.locations];

                this.map = L.map('fullWebGisMap', {
                    zoomControl: false
                }).setView([-3.8004, 102.2655], 13);

                L.control.zoom({ position: 'bottomright' }).addTo(this.map);

                // Tile Layers
                this.tileLayers.osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                });

                this.tileLayers.satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    attribution: 'Tiles &copy; Esri',
                    maxZoom: 18
                });

                this.tileLayers.osm.addTo(this.map);
                this.markersGroup = L.layerGroup().addTo(this.map);

                this.updateOrigin();
                this.renderMarkers();
            },

            setBasemap(type) {
                this.activeBasemap = type;
                if (type === 'osm') {
                    this.map.removeLayer(this.tileLayers.satellite);
                    this.tileLayers.osm.addTo(this.map);
                } else {
                    this.map.removeLayer(this.tileLayers.osm);
                    this.tileLayers.satellite.addTo(this.map);
                }
            },

            updateOrigin() {
                const preset = this.departurePresets.find(p => p.id === this.selectedOrigin) || this.departurePresets[0];
                if (this.originMarker) {
                    this.map.removeLayer(this.originMarker);
                }

                const schoolIcon = L.divIcon({
                    className: 'custom-school-icon',
                    html: `
                        <div style="background-color: #132985; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid #ffffff; box-shadow: 0 4px 15px rgba(19,41,133,0.4); color: white;">
                            <svg style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                        </div>
                    `,
                    iconSize: [36, 36],
                    iconAnchor: [18, 18]
                });

                this.originMarker = L.marker([preset.lat, preset.lng], { icon: schoolIcon }).addTo(this.map);
                this.originMarker.bindPopup(`
                    <div style="font-family: 'Plus Jakarta Sans', sans-serif; padding: 4px;">
                        <span style="font-size: 10px; font-weight: 800; color: #132985; text-transform: uppercase;">Titik Berangkat Sekolah</span>
                        <h4 style="font-size: 13px; font-weight: 800; color: #0f172a; margin: 2px 0;">${preset.name}</h4>
                        <p style="font-size: 11px; color: #64748b; margin: 0;">Titik awal acuan perhitungan jarak Haversine (Kriteria C2 Cost).</p>
                    </div>
                `);
            },

            toggleSubject(id) {
                const numId = Number(id);
                const idx = this.selectedSubjects.indexOf(numId);
                if (idx > -1) {
                    this.selectedSubjects.splice(idx, 1);
                } else {
                    this.selectedSubjects.push(numId);
                }
                this.filterMarkers();
            },

            filterMarkers() {
                this.visibleLocations = this.locations.filter(loc => {
                    // Keyword filter
                    if (this.searchKeyword.trim() !== '') {
                        const kw = this.searchKeyword.toLowerCase();
                        const matchName = loc.nama_lokasi.toLowerCase().includes(kw);
                        const matchAddr = (loc.alamat_kawasan || '').toLowerCase().includes(kw);
                        if (!matchName && !matchAddr) return false;
                    }

                    // Subject filter
                    if (this.selectedSubjects.length > 0) {
                        const locSubjIds = (loc.subjects || []).map(s => Number(s.id));
                        const hasMatch = this.selectedSubjects.some(sId => locSubjIds.includes(sId));
                        if (!hasMatch) return false;
                    }

                    return true;
                });

                this.renderMarkers();
            },

            renderMarkers() {
                this.markersGroup.clearLayers();

                this.visibleLocations.forEach(loc => {
                    const markerHtml = `
                        <div style="background-color: #132985; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid #ffffff; box-shadow: 0 4px 15px rgba(19,41,133,0.35); color: white; cursor: pointer;">
                            <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    `;

                    const locIcon = L.divIcon({
                        className: 'custom-loc-icon',
                        html: markerHtml,
                        iconSize: [34, 34],
                        iconAnchor: [17, 17]
                    });

                    const marker = L.marker([loc.latitude, loc.longitude], { icon: locIcon });
                    
                    const subjectsHtml = (loc.subjects || []).map(s => `
                        <span style="display: inline-block; padding: 2px 8px; font-size: 9px; font-weight: 700; border-radius: 6px; background-color: #EEF2FF; color: #132985; margin: 2px;">
                            ${s.nama_mapel}
                        </span>
                    `).join('');

                    const popupContent = `
                        <div style="font-family: 'Plus Jakarta Sans', sans-serif; min-width: 220px; padding: 4px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
                                <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 9999px; text-transform: uppercase; background-color: #EEF2FF; color: #132985;">
                                    Lokasi Pembelajaran Pesisir
                                </span>
                            </div>
                            <h3 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 2px 0;">${loc.nama_lokasi}</h3>
                            <p style="font-size: 11px; color: #64748b; margin-bottom: 8px;">${loc.alamat_kawasan}</p>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px; font-size: 10px; background: #f8fafc; padding: 6px; border-radius: 8px; margin-bottom: 8px;">
                                <div>Fasilitas (C3): <strong>${loc.skor_fasilitas_c3}/100</strong></div>
                                <div>Keamanan (C4): <strong>${loc.skor_keamanan_c4}/100</strong></div>
                            </div>

                            <p style="font-size: 10px; color: #475569; margin-bottom: 6px; line-height: 1.4;">${loc.deskripsi || ''}</p>

                            <div style="margin-bottom: 4px;">
                                <span style="font-size: 10px; font-weight: 700; color: #475569; display: block; margin-bottom: 2px;">Mata Pelajaran Relevan:</span>
                                <div>${subjectsHtml}</div>
                            </div>
                        </div>
                    `;

                    marker.bindPopup(popupContent);
                    this.markersGroup.addLayer(marker);
                });
            },

            zoomToLocation(loc) {
                this.map.flyTo([loc.latitude, loc.longitude], 15, { duration: 1.2 });
            }
        }
    }
</script>
@endsection
