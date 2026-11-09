<!DOCTYPE html>
<html lang="id" class="h-full w-full overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Peta Rute & Panduan Navigasi: {{ $location->nama_lokasi }} | EDU PESISIR</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Tailwind CSS (CDN with Custom Gymove Colors) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'gymove-blue': '#132985',
                        'gymove-blue-hover': '#0d1e63',
                        'gymove-orange': '#FF8A2B',
                        'gymove-orange-hover': '#f07818',
                        'gymove-soft-blue': '#EEF2FF',
                        'gymove-soft-blue-hover': '#E0E7FF',
                        'gymove-pill': '#F4F5F9',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    },
                    borderRadius: {
                        'card': '24px',
                    },
                    boxShadow: {
                        'soft-card': '0 10px 40px rgba(19, 41, 133, 0.08)',
                        'floating': '0 12px 36px rgba(15, 23, 42, 0.22)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: #0f172a;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        #routeMap {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        /* Marker Origin (School) */
        .custom-school-marker {
            background: #1e3a8a;
            color: #ffffff;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 18px rgba(30, 58, 138, 0.6), 0 0 0 3px #ffffff;
            border: 2px solid #3b82f6;
            transition: transform 0.2s ease;
        }
        .custom-school-marker:hover {
            transform: scale(1.1);
        }

        /* Marker Destination (Educational Place) */
        .custom-dest-marker {
            background: #ea580c;
            color: #ffffff;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 22px rgba(234, 88, 12, 0.6), 0 0 0 3px #ffffff;
            border: 2px solid #fdba74;
            animation: pulse-destination 2s infinite ease-in-out;
        }

        @keyframes pulse-destination {
            0% { box-shadow: 0 0 0 0 rgba(234, 88, 12, 0.7), 0 0 0 3px #ffffff; }
            70% { box-shadow: 0 0 0 14px rgba(234, 88, 12, 0), 0 0 0 3px #ffffff; }
            100% { box-shadow: 0 0 0 0 rgba(234, 88, 12, 0), 0 0 0 3px #ffffff; }
        }

        /* Live Device GPS Beacon Marker */
        .custom-gps-marker {
            position: relative;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gps-pulse-ring {
            position: absolute;
            width: 52px;
            height: 52px;
            border-radius: 9999px;
            background: rgba(37, 99, 235, 0.28);
            animation: gps-wave 2s infinite ease-out;
            pointer-events: none;
        }

        .gps-dot {
            width: 22px;
            height: 22px;
            background: #2563eb;
            border: 3.5px solid #ffffff;
            border-radius: 9999px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            z-index: 2;
        }

        @keyframes gps-wave {
            0% { transform: scale(0.4); opacity: 1; }
            100% { transform: scale(1.7); opacity: 0; }
        }

        /* Clean Leaflet Popup */
        .leaflet-popup-content-wrapper {
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
            padding: 4px;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .leaflet-popup-content {
            margin: 12px 14px;
            line-height: 1.45;
        }

        /* Hide default Leaflet top-left zoom to place custom Google Maps controls */
        .leaflet-top.leaflet-left {
            display: none !important;
        }

        /* Custom Scrollbar for steps drawer */
        .custom-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    @php
        $schoolsPayload = $schools->map(fn($s) => [
            'id' => $s->id,
            'name' => $s->nama_sekolah,
            'lat' => (float)$s->latitude,
            'lng' => (float)$s->longitude,
            'jenjang' => $s->id_jenjang,
            'alamat' => $s->alamat
        ]);
    @endphp

    <!-- Pass Navigation Data cleanly from Blade to JS without HTML attribute quote corruption -->
    <script>
        window.MAP_NAVIGATION_DATA = {
            schools: @json($schoolsPayload),
            selectedSchoolId: {{ (int)($selectedSchool->id ?? 1) }},
            place: {
                id: {{ $location->id }},
                name: @json($location->nama_lokasi),
                address: @json($location->alamat_kawasan),
                lat: {{ (float)$location->latitude }},
                lng: {{ (float)$location->longitude }},
                hours: @json($location->jam_operasional),
                fee: @json($location->biaya),
                status: @json($location->status)
            },
            distanceKm: {{ (float)($distanceKm ?? 0) }},
            currentOriginLat: {{ (float)($selectedSchool->latitude ?? -3.7928) }},
            currentOriginLng: {{ (float)($selectedSchool->longitude ?? 102.2608) }},
            currentOriginName: @json($selectedSchool->nama_sekolah ?? 'Sekolah Asal')
        };

        document.addEventListener('alpine:init', () => {
            Alpine.data('routeNavigation', () => ({
                panelOpen: true,
                schools: window.MAP_NAVIGATION_DATA.schools || [],
                selectedSchoolId: window.MAP_NAVIGATION_DATA.selectedSchoolId,
                place: window.MAP_NAVIGATION_DATA.place,
                currentDistanceKm: window.MAP_NAVIGATION_DATA.distanceKm,
                travelTimeDrive: '±10 menit',
                travelTimeMotor: '±8 menit',
                travelTimeWalk: '±35 menit',
                activeMode: 'drive',

                currentOriginLat: window.MAP_NAVIGATION_DATA.currentOriginLat,
                currentOriginLng: window.MAP_NAVIGATION_DATA.currentOriginLng,
                currentOriginName: window.MAP_NAVIGATION_DATA.currentOriginName,

                isNavigating: false,
                isGpsConnected: false,
                gpsStatusMessage: 'Panduan rute siap.',
                gpsErrorMessage: '',
                remainingDistanceText: '',
                activeMapMode: 'satellite',
                showStepsList: false,
                navigationSteps: [],
                isLoadingRoute: true,
                useMyCurrentLocation: false,

                init() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const urlSchool = urlParams.get('school');
                    if (!urlSchool) {
                        try {
                            const storedSchool = localStorage.getItem('user_school_id');
                            if (storedSchool && storedSchool != this.selectedSchoolId) {
                                const sch = this.schools.find(s => s.id == storedSchool);
                                if (sch) {
                                    this.selectedSchoolId = Number(storedSchool);
                                    this.onSchoolChange();
                                }
                            }
                        } catch(e) {}
                    }
                },

                onSchoolChange() {
                    const sch = this.schools.find(s => s.id == this.selectedSchoolId);
                    if (!sch) return;
                    
                    this.useMyCurrentLocation = false;
                    this.currentOriginLat = sch.lat;
                    this.currentOriginLng = sch.lng;
                    this.currentOriginName = sch.name;
                    this.stopNavigation();
                    this.isLoadingRoute = true;

                    if (window.history && window.history.replaceState) {
                        const url = new URL(window.location);
                        url.searchParams.set('school', this.selectedSchoolId);
                        window.history.replaceState(null, '', url);
                    }
                    try { localStorage.setItem('user_school_id', this.selectedSchoolId); } catch(e) {}

                    if (window.fetchRoadRoute) {
                        window.fetchRoadRoute(sch.lat, sch.lng, sch.name, sch.alamat, false);
                    }
                },

                useDeviceLocationAsOrigin() {
                    this.useMyCurrentLocation = true;
                    this.isLoadingRoute = true;
                    this.stopNavigation();
                    if (window.setDeviceLocationAsOrigin) {
                        window.setDeviceLocationAsOrigin();
                    }
                },

                startNavigation() {
                    this.isNavigating = true;
                    this.gpsErrorMessage = '';

                    if (this.useMyCurrentLocation) {
                        this.gpsStatusMessage = 'Mengakses sensor GPS perangkat Anda...';
                        if (window.startGpsNavigation) {
                            window.startGpsNavigation();
                        }
                    } else {
                        this.gpsStatusMessage = 'Panduan navigasi rute aktif dari ' + this.currentOriginName;
                        this.showStepsList = true;
                        if (window.startSchoolNavigation) {
                            window.startSchoolNavigation();
                        }
                    }
                },

                stopNavigation() {
                    this.isNavigating = false;
                    this.isGpsConnected = false;
                    this.gpsStatusMessage = 'Navigasi dihentikan.';
                    if (window.stopGpsNavigation) {
                        window.stopGpsNavigation();
                    }
                },

                setMapLayer(mode) {
                    this.activeMapMode = mode;
                    if (window.switchLayer) {
                        window.switchLayer(mode);
                    }
                },

                focusRoute() {
                    if (window.resetView) window.resetView();
                },

                centerOnUserOrSchool() {
                    if (window.centerCurrentOrigin) window.centerCurrentOrigin();
                },

                zoomIn() {
                    if (window.mapZoomIn) window.mapZoomIn();
                },

                zoomOut() {
                    if (window.mapZoomOut) window.mapZoomOut();
                },

                toggleFullscreen() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().catch(() => {});
                    } else {
                        if (document.exitFullscreen) {
                            document.exitFullscreen().catch(() => {});
                        }
                    }
                }
            }));
        });
    </script>
</head>
<body class="h-full w-full overflow-hidden select-none"
      x-data="routeNavigation"
      @route-loaded.window="
        currentDistanceKm = $event.detail.roadKm;
        travelTimeDrive = $event.detail.driveTime;
        travelTimeMotor = $event.detail.motorTime;
        travelTimeWalk = $event.detail.walkTime;
        navigationSteps = $event.detail.steps;
        remainingDistanceText = $event.detail.roadKm + ' Km';
        isLoadingRoute = false;
      "
      @origin-changed.window="
        currentOriginLat = $event.detail.lat;
        currentOriginLng = $event.detail.lng;
        currentOriginName = $event.detail.name;
      "
      @gps-status-update.window="
        isGpsConnected = $event.detail.connected;
        gpsStatusMessage = $event.detail.message;
        if ($event.detail.remaining) {
            remainingDistanceText = $event.detail.remaining;
        }
      "
      @gps-error.window="
        gpsErrorMessage = $event.detail.error;
        gpsStatusMessage = 'Koneksi GPS terganggu.';
        isLoadingRoute = false;
      ">

    <!-- ======================================================== -->
    <!-- 1. FULL SCREEN LEAFLET MAP CONTAINER -->
    <!-- ======================================================== -->
    <div id="routeMap"></div>

    <!-- ======================================================== -->
    <!-- 2. FLOATING GOOGLE MAPS STYLE ROUTE & DIRECTIONS PANEL -->
    <!-- ======================================================== -->
    <div class="fixed top-4 left-4 z-[500] pointer-events-auto flex flex-col items-start transition-all duration-300">
        
        <!-- Toggle Pill when Panel is Minimized -->
        <div x-cloak
             x-show="!panelOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             class="bg-white/95 backdrop-blur-md shadow-floating rounded-2xl p-2 border border-slate-200 flex items-center gap-2">
            <button type="button" 
                    @click="panelOpen = true" 
                    title="Buka Panel Rute"
                    class="p-2.5 rounded-xl bg-gymove-blue text-white hover:bg-gymove-blue-hover transition-colors flex items-center gap-2 cursor-pointer shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-xs font-bold pr-1">Buka Rute & Petunjuk</span>
            </button>

            <!-- Single Exit Button when Collapsed -->
            <a :href="'/tempat-belajar/' + place.id + '?school=' + selectedSchoolId"
               href="{{ route('frontend.places.show', ['location' => $location->id, 'school' => $selectedSchool->id ?? request('school')]) }}" 
               onclick="if (document.referrer && !document.referrer.includes('/peta/')) { window.history.back(); return false; }"
               title="Keluar / Kembali"
               class="p-2.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
        </div>

        <!-- Expanded Google Maps Drawer Panel -->
        <div x-cloak
             x-show="panelOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-x-6 scale-95"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             class="w-[390px] max-w-[calc(100vw-2rem)] max-h-[calc(100vh-2rem)] bg-white/95 backdrop-blur-md rounded-2xl shadow-floating border border-slate-200/90 flex flex-col overflow-hidden">
            
            <!-- Header Section with Single Back/Exit Button & Close Toggle -->
            <div class="p-4 bg-slate-50/80 border-b border-slate-200/80 flex items-center justify-between gap-2 shrink-0">
                <div class="flex items-center gap-2.5 min-w-0">
                    <!-- Single Exit Button -->
                    <a :href="'/tempat-belajar/' + place.id + '?school=' + selectedSchoolId"
                       href="{{ route('frontend.places.show', ['location' => $location->id, 'school' => $selectedSchool->id ?? request('school')]) }}" 
                       onclick="if (document.referrer && !document.referrer.includes('/peta/')) { window.history.back(); return false; }"
                       title="Keluar / Kembali"
                       class="w-8 h-8 rounded-xl bg-white border border-slate-200 hover:border-slate-300 text-slate-700 hover:text-gymove-blue flex items-center justify-center transition-colors shadow-xs shrink-0 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div class="min-w-0">
                        <h2 class="text-sm font-black text-slate-900 truncate leading-tight">
                            {{ $location->nama_lokasi }}
                        </h2>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">
                            Panduan Rute Jalan
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-1.5 shrink-0">
                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ stripos($location->biaya, 'gratis') !== false ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $location->biaya }}
                    </span>
                    <button type="button" 
                            @click="panelOpen = false" 
                            title="Sembunyikan Panel (Lihat Peta Penuh)"
                            class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200 flex items-center justify-center transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Scrollable Body Content -->
            <div class="p-4 space-y-4 overflow-y-auto custom-scroll flex-1 text-slate-700 text-xs">
                
                <!-- Google Maps Style Route Connector (Origin -> Destination) -->
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200/90 relative">
                    <!-- Connector line graphic -->
                    <div class="absolute left-6 top-8 bottom-8 w-0.5 bg-slate-300"></div>

                    <!-- Origin Item (Sekolah Asal atau Posisi GPS Perangkat) -->
                    <div class="flex items-start gap-3 relative z-10 mb-3">
                        <div class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center shrink-0 ring-4 ring-slate-50 mt-1 shadow-xs">
                            <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <label class="block text-[10px] font-black uppercase tracking-wider text-blue-700 mb-1">
                                Titik Keberangkatan (Asal)
                            </label>

                            <!-- When using device GPS as origin -->
                            <div x-show="useMyCurrentLocation" class="bg-blue-50 border border-blue-200 p-2.5 rounded-lg flex items-center justify-between">
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-black text-blue-900 truncate flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                                        <span>Posisi Perangkat Anda (GPS)</span>
                                    </div>
                                    <div class="text-[10px] text-blue-700 mt-0.5" x-text="gpsStatusMessage"></div>
                                </div>
                                <button type="button" 
                                        @click="useMyCurrentLocation = false; onSchoolChange()"
                                        class="text-[10px] font-bold text-blue-700 hover:text-blue-900 underline ml-2 shrink-0 cursor-pointer">
                                    Ganti Sekolah
                                </button>
                            </div>

                            <!-- When using school dropdown as origin -->
                            <div x-show="!useMyCurrentLocation">
                                @php
                                    $groupedSchools = $schools->groupBy(fn($s) => match($s->id_jenjang) {
                                        'JJ001' => 'Jenjang SD / MI (149 Sekolah)',
                                        'JJ002' => 'Jenjang SMP / MTs (58 Sekolah)',
                                        'JJ003' => 'Jenjang SMA / SMK / MA (60 Sekolah)',
                                        default => 'Sekolah Lainnya'
                                    });
                                @endphp
                                <select x-model="selectedSchoolId" 
                                        @change="onSchoolChange()"
                                        class="w-full bg-white text-slate-900 text-xs font-semibold p-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600 shadow-xs cursor-pointer truncate">
                                    @foreach($groupedSchools as $groupLabel => $groupList)
                                        <optgroup label="{{ $groupLabel }}">
                                            @foreach($groupList as $sch)
                                                <option value="{{ $sch->id }}" {{ ($selectedSchool && $selectedSchool->id == $sch->id) ? 'selected' : '' }}>
                                                    {{ $sch->nama_sekolah }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>

                                <!-- Use Device Location Quick Button (Only sets origin, does NOT start navigation) -->
                                <button type="button" 
                                        @click="useDeviceLocationAsOrigin()" 
                                        :disabled="isLoadingRoute"
                                        class="mt-1.5 py-1 px-2.5 rounded-md bg-white hover:bg-blue-50 text-blue-700 hover:text-blue-800 text-[11px] font-bold border border-slate-200 flex items-center gap-1.5 transition-colors cursor-pointer shadow-2xs disabled:opacity-50">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span x-text="isLoadingRoute ? 'Mencari Lokasi GPS...' : 'Gunakan Posisi Perangkat (GPS)'"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Destination Item (Tempat Belajar Tujuan) -->
                    <div class="flex items-start gap-3 relative z-10">
                        <div class="w-5 h-5 rounded-full bg-orange-600 text-white flex items-center justify-center shrink-0 ring-4 ring-slate-50 mt-0.5 shadow-xs">
                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="block text-[10px] font-black uppercase tracking-wider text-orange-700">
                                Titik Belajar Tujuan
                            </span>
                            <div class="font-extrabold text-slate-900 text-xs truncate mt-0.5">
                                {{ $location->nama_lokasi }}
                            </div>
                            <p class="text-[10px] text-slate-500 leading-snug mt-0.5 line-clamp-2">
                                {{ $location->alamat_kawasan }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Transport Mode Selector (Google Maps Style Tabs) -->
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-1.5">
                        Moda Perjalanan & Estimasi
                    </span>
                    <div class="grid grid-cols-3 gap-2">
                        <!-- Car Mode -->
                        <button type="button" 
                                @click="activeMode = 'drive'" 
                                :class="activeMode === 'drive' ? 'bg-blue-600 text-white shadow-sm ring-2 ring-blue-600/30' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                                class="p-2.5 rounded-xl flex flex-col items-center justify-center transition-all cursor-pointer">
                            <svg class="w-4 h-4 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                            </svg>
                            <span class="text-[10px] font-bold uppercase tracking-tight">Mobil</span>
                            <span class="text-[11px] font-black mt-0.5 font-mono" x-text="travelTimeDrive"></span>
                        </button>

                        <!-- Motorcycle Mode -->
                        <button type="button" 
                                @click="activeMode = 'motor'" 
                                :class="activeMode === 'motor' ? 'bg-blue-600 text-white shadow-sm ring-2 ring-blue-600/30' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                                class="p-2.5 rounded-xl flex flex-col items-center justify-center transition-all cursor-pointer">
                            <svg class="w-4 h-4 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="text-[10px] font-bold uppercase tracking-tight">Motor</span>
                            <span class="text-[11px] font-black mt-0.5 font-mono" x-text="travelTimeMotor"></span>
                        </button>

                        <!-- Walk Mode -->
                        <button type="button" 
                                @click="activeMode = 'walk'" 
                                :class="activeMode === 'walk' ? 'bg-blue-600 text-white shadow-sm ring-2 ring-blue-600/30' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'"
                                class="p-2.5 rounded-xl flex flex-col items-center justify-center transition-all cursor-pointer">
                            <svg class="w-4 h-4 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6z" />
                            </svg>
                            <span class="text-[10px] font-bold uppercase tracking-tight">Jalan</span>
                            <span class="text-[11px] font-black mt-0.5 font-mono" x-text="travelTimeWalk"></span>
                        </button>
                    </div>
                </div>

                <!-- Distance Banner -->
                <div class="p-3 bg-gradient-to-r from-slate-900 to-slate-800 rounded-xl text-white flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Jarak Rute Jalan Raya</span>
                        <div class="text-xl font-black font-mono tracking-tight text-amber-300" x-text="currentDistanceKm + ' Km'"></div>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-1 rounded-md bg-white/10 text-slate-200 border border-white/10">
                        Jalan Raya Asli
                    </span>
                </div>

                <!-- Primary Action: Start Route Guidance / Navigation -->
                <div class="pt-1">
                    <div x-show="!isNavigating">
                        <button type="button" 
                                @click="startNavigation()" 
                                :disabled="isLoadingRoute"
                                class="w-full py-3 px-4 rounded-xl bg-gymove-blue hover:bg-gymove-blue-hover text-white font-black text-xs transition-all shadow-md active:scale-98 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50">
                            <span x-show="isLoadingRoute" class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span>Memuat Jaringan Jalan...</span>
                            </span>
                            <span x-show="!isLoadingRoute" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                <span x-text="useMyCurrentLocation ? 'Mulai Navigasi (Ikuti Posisi GPS)' : 'Mulai Panduan Navigasi Rute'"></span>
                            </span>
                        </button>
                    </div>

                    <div x-show="isNavigating">
                        <button type="button" 
                                @click="stopNavigation()" 
                                class="w-full py-3 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-black text-xs transition-all shadow-md active:scale-98 flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                            </svg>
                            <span>Hentikan Navigasi</span>
                        </button>
                    </div>

                    <div class="mt-2 text-[11px] text-slate-500 flex items-center gap-1.5 justify-center text-center">
                        <span class="w-2 h-2 rounded-full shrink-0" :class="isNavigating ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300'"></span>
                        <span class="truncate" x-text="isNavigating ? (useMyCurrentLocation ? 'Navigasi aktif • Peta mengikuti pergerakan GPS Anda' : 'Panduan aktif • Titik asal: ' + currentOriginName) : (useMyCurrentLocation ? 'Sensor GPS siap membaca posisi perangkat Anda' : 'Rute dihitung dari titik keberangkatan yang Anda pilih')"></span>
                    </div>
                </div>



                <!-- Turn-by-Turn Directions List (Accordion) -->
                <div class="border-t border-slate-200/80 pt-3" x-show="navigationSteps.length > 0">
                    <button type="button" 
                            @click="showStepsList = !showStepsList" 
                            class="w-full py-2 px-3 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition-all flex items-center justify-between cursor-pointer">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            <span>Petunjuk Belokan Jalan (<span x-text="navigationSteps.length"></span>)</span>
                        </span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="showStepsList ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="showStepsList" x-transition class="mt-2 space-y-1.5 max-h-48 overflow-y-auto custom-scroll pr-1">
                        <template x-for="(st, idx) in navigationSteps" :key="idx">
                            <div class="p-2 rounded-lg bg-slate-50 border border-slate-200/60 flex items-start gap-2">
                                <span class="font-bold text-blue-700 shrink-0 text-[11px]" x-text="(idx + 1) + '.'"></span>
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-slate-800 text-[11px]" x-text="st.instruction"></div>
                                    <div class="text-[10px] text-slate-500 mt-0.5" x-text="st.distance + ' m • ' + st.duration"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Subjects Badges -->
                <div class="border-t border-slate-200/80 pt-3">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-1.5">
                        Mata Pelajaran yang Didukung:
                    </span>
                    <div class="flex flex-wrap gap-1">
                        @foreach($location->subjects as $subj)
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md"
                                  style="background-color: {{ $subj->warna_bg_soft }}; color: {{ $subj->warna_teks }};">
                                {{ $subj->nama_mapel }}
                            </span>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- ======================================================== -->
    <!-- 3. FLOATING MAP CONTROLS (BOTTOM-LEFT & BOTTOM-RIGHT) -->
    <!-- ======================================================== -->

    <!-- Bottom-Left: Google Maps Layer Mode Switcher -->
    <div class="fixed bottom-6 left-6 z-[450] pointer-events-auto">
        <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-floating border border-slate-200 p-1.5 flex items-center gap-1 text-xs font-bold text-slate-700">
            <button type="button" 
                    @click="setMapLayer('satellite')" 
                    :class="activeMapMode === 'satellite' ? 'bg-gymove-blue text-white shadow-xs' : 'hover:bg-slate-100 text-slate-700'"
                    class="px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Satelit</span>
            </button>

            <button type="button" 
                    @click="setMapLayer('street')" 
                    :class="activeMapMode === 'street' ? 'bg-gymove-blue text-white shadow-xs' : 'hover:bg-slate-100 text-slate-700'"
                    class="px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                </svg>
                <span>Jalan (OSM)</span>
            </button>

            <button type="button" 
                    @click="setMapLayer('esri')" 
                    :class="activeMapMode === 'esri' ? 'bg-gymove-blue text-white shadow-xs' : 'hover:bg-slate-100 text-slate-700'"
                    class="px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span>Esri</span>
            </button>
        </div>
    </div>

    <!-- Bottom-Right: Google Maps Controls (GPS Locate, Bounds Fit, Zoom, Fullscreen) -->
    <div class="fixed bottom-6 right-6 z-[450] pointer-events-auto flex flex-col items-end gap-2.5">
        
        <!-- Center on User Location / School Button -->
        <button type="button" 
                @click="centerOnUserOrSchool()" 
                title="Pusatkan ke Posisi Asal / Perangkat"
                class="w-11 h-11 rounded-2xl bg-white/95 backdrop-blur-md text-slate-700 hover:text-blue-600 hover:bg-white shadow-floating border border-slate-200 flex items-center justify-center transition-all cursor-pointer active:scale-95">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3A8.994 8.994 0 0013 3.06V1h-2v2.06A8.994 8.994 0 003.06 11H1v2h2.06A8.994 8.994 0 0011 20.94V23h2v-2.06A8.994 8.994 0 0020.94 13H23v-2h-2.06zM12 19c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z" />
            </svg>
        </button>

        <!-- Fit Bounds Route Button -->
        <button type="button" 
                @click="focusRoute()" 
                title="Fokuskan ke Seluruh Rute"
                class="w-11 h-11 rounded-2xl bg-white/95 backdrop-blur-md text-slate-700 hover:text-blue-600 hover:bg-white shadow-floating border border-slate-200 flex items-center justify-center transition-all cursor-pointer active:scale-95">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
            </svg>
        </button>

        <!-- Zoom Control Buttons (+ / -) -->
        <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-floating border border-slate-200 flex flex-col overflow-hidden">
            <button type="button" 
                    @click="zoomIn()" 
                    title="Perbesar Peta"
                    class="w-11 h-10 text-slate-700 hover:bg-slate-100 hover:text-slate-900 flex items-center justify-center transition-colors cursor-pointer border-b border-slate-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
            </button>
            <button type="button" 
                    @click="zoomOut()" 
                    title="Perkecil Peta"
                    class="w-11 h-10 text-slate-700 hover:bg-slate-100 hover:text-slate-900 flex items-center justify-center transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4" />
                </svg>
            </button>
        </div>

        <!-- Fullscreen Browser Button -->
        <button type="button" 
                @click="toggleFullscreen()" 
                title="Layar Penuh (Fullscreen)"
                class="w-11 h-11 rounded-2xl bg-white/95 backdrop-blur-md text-slate-700 hover:text-blue-600 hover:bg-white shadow-floating border border-slate-200 flex items-center justify-center transition-all cursor-pointer active:scale-95">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3h7m0 0v7m0-7L10 14M10 21H3m0 0v-7m0 7l11-11" />
            </svg>
        </button>

    </div>

    <!-- ======================================================== -->
    <!-- 4. LEAFLET SCRIPT & ROUTING LOGIC -->
    <!-- ======================================================== -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const placeLat = {{ (float)$location->latitude }};
            const placeLng = {{ (float)$location->longitude }};
            const placeName = @json($location->nama_lokasi);
            const placeAddr = @json($location->alamat_kawasan);

            let schoolLat = {{ (float)($selectedSchool->latitude ?? -3.7928) }};
            let schoolLng = {{ (float)($selectedSchool->longitude ?? 102.2608) }};
            let schoolName = @json($selectedSchool->nama_sekolah ?? 'Sekolah Asal');

            // Current navigation origin
            let currentOriginLat = schoolLat;
            let currentOriginLng = schoolLng;
            let currentOriginName = schoolName;

            // 1. Initialize Fullscreen Leaflet Map
            const map = L.map('routeMap', {
                zoomControl: false,
                scrollWheelZoom: true,
                maxZoom: 20
            }).setView([placeLat, placeLng], 14);

            // 2. Tile Layers (Google Hybrid Satellite, OSM Streets, Esri)
            const satelliteHybrid = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                attribution: '&copy; Citra Satelit',
                maxZoom: 20
            });

            const esriSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri',
                maxZoom: 19
            });

            const osmStreet = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19
            });

            satelliteHybrid.addTo(map);
            let currentLayer = satelliteHybrid;

            window.switchLayer = function(mode) {
                if (currentLayer) map.removeLayer(currentLayer);
                if (mode === 'satellite') {
                    satelliteHybrid.addTo(map);
                    currentLayer = satelliteHybrid;
                } else if (mode === 'esri') {
                    esriSatellite.addTo(map);
                    currentLayer = esriSatellite;
                } else if (mode === 'street') {
                    osmStreet.addTo(map);
                    currentLayer = osmStreet;
                }
            };

            // Custom Clean SVG Marker Icons (Zero Childish Emojis)
            const schoolIcon = L.divIcon({
                className: 'custom-school-marker',
                html: `
                    <svg style="width:20px;height:20px;fill:none;stroke:#ffffff;stroke-width:2.2;" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                `,
                iconSize: [40, 40],
                iconAnchor: [20, 20],
                popupAnchor: [0, -20]
            });

            const destIcon = L.divIcon({
                className: 'custom-dest-marker',
                html: `
                    <svg style="width:22px;height:22px;fill:none;stroke:#ffffff;stroke-width:2.4;" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                `,
                iconSize: [44, 44],
                iconAnchor: [22, 22],
                popupAnchor: [0, -22]
            });

            const gpsUserIcon = L.divIcon({
                className: 'custom-gps-marker',
                html: `
                    <div class="gps-pulse-ring"></div>
                    <div class="gps-dot">
                        <svg style="width:12px;height:12px;fill:currentColor;" viewBox="0 0 24 24">
                            <path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71z"/>
                        </svg>
                    </div>
                `,
                iconSize: [36, 36],
                iconAnchor: [18, 18],
                popupAnchor: [0, -18]
            });

            // Destination Marker (Fixed)
            const destMarker = L.marker([placeLat, placeLng], { icon: destIcon }).addTo(map);
            function updateDestPopup() {
                destMarker.bindPopup(`
                    <div style="font-family: inherit; font-size: 12px; max-width: 240px;">
                        <div style="font-size: 10px; font-weight: 800; color: #ea580c; text-transform: uppercase; letter-spacing: 0.5px;">Tempat Belajar Tujuan</div>
                        <strong style="font-size: 14px; color: #0f172a; display: block; margin-top: 2px;">${placeName}</strong>
                        <p style="margin-top: 4px; color: #64748b; font-size: 11px; line-height: 1.35;">${placeAddr}</p>
                    </div>
                `);
            }
            updateDestPopup();

            // School Marker (Departure)
            let schoolMarker = L.marker([schoolLat, schoolLng], { icon: schoolIcon }).addTo(map);
            function bindSchoolPopup(marker, name, addr) {
                marker.bindPopup(`
                    <div style="font-family: inherit; font-size: 12px; max-width: 240px;">
                        <div style="font-size: 10px; font-weight: 800; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px;">Titik Keberangkatan (Asal)</div>
                        <strong style="font-size: 14px; color: #0f172a; display: block; margin-top: 2px;">${name}</strong>
                        ${addr ? `<p style="margin-top: 4px; color: #64748b; font-size: 11px;">${addr}</p>` : ''}
                    </div>
                `);
            }
            bindSchoolPopup(schoolMarker, schoolName, '');

            // User GPS Live Marker & Polylines
            let userGpsMarker = null;
            let routeGlow = null;
            let routePolyline = null;

            let roadCoordinates = [];
            let totalRoadKm = 0;
            let watchPositionId = null;
            let lastRouteFetchTime = 0;

            // Fetch Real Road Route via OSRM
            window.fetchRoadRoute = async function(origLat, origLng, origName, origAddr, isGpsUser) {
                if (origLat !== undefined && origLat !== null && origLat !== '') currentOriginLat = Number(origLat);
                if (origLng !== undefined && origLng !== null && origLng !== '') currentOriginLng = Number(origLng);
                if (origName) currentOriginName = origName;

                updateDestPopup();

                if (!isGpsUser) {
                    if (watchPositionId) {
                        navigator.geolocation.clearWatch(watchPositionId);
                        watchPositionId = null;
                    }
                    if (userGpsMarker) {
                        map.removeLayer(userGpsMarker);
                        userGpsMarker = null;
                    }
                    if (schoolMarker) map.removeLayer(schoolMarker);
                    schoolMarker = L.marker([currentOriginLat, currentOriginLng], { icon: schoolIcon }).addTo(map);
                    bindSchoolPopup(schoolMarker, currentOriginName, origAddr);
                }

                if (routeGlow) map.removeLayer(routeGlow);
                if (routePolyline) map.removeLayer(routePolyline);

                const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${currentOriginLng},${currentOriginLat};${placeLng},${placeLat}?overview=full&geometries=geojson&steps=true`;

                try {
                    const response = await fetch(osrmUrl);
                    const data = await response.json();

                    if (data.code === 'Ok' && data.routes && data.routes.length > 0) {
                        const primaryRoute = data.routes[0];
                        const geoCoords = primaryRoute.geometry.coordinates;

                        roadCoordinates = geoCoords.map(c => [c[1], c[0]]);

                        // Outer white glow line
                        routeGlow = L.polyline(roadCoordinates, {
                            color: '#ffffff',
                            weight: 8,
                            opacity: 0.85,
                            lineJoin: 'round'
                        }).addTo(map);

                        // Inner vibrant road polyline (Blue)
                        routePolyline = L.polyline(roadCoordinates, {
                            color: '#0284c7',
                            weight: 5.5,
                            opacity: 0.95,
                            lineJoin: 'round'
                        }).addTo(map);

                        totalRoadKm = Math.round((primaryRoute.distance / 1000) * 100) / 100;
                        const driveMinutes = Math.max(2, Math.round(primaryRoute.duration / 60));
                        const motorMinutes = Math.max(2, Math.round((primaryRoute.duration * 0.75) / 60));
                        const walkMinutes = Math.round((totalRoadKm / 4.5) * 60);

                        let steps = [];
                        if (primaryRoute.legs && primaryRoute.legs[0] && primaryRoute.legs[0].steps) {
                            steps = primaryRoute.legs[0].steps.map(s => ({
                                instruction: s.maneuver.type === 'depart' ? 'Mulai perjalanan' :
                                             s.maneuver.type === 'arrive' ? `Tiba di tujuan: ${placeName}` :
                                             (s.name ? `Lanjut ke ${s.name}` : `Belok ${s.maneuver.modifier || 'ke jalan berikutnya'}`),
                                distance: Math.round(s.distance),
                                duration: Math.max(1, Math.round(s.duration / 60)) + ' mnt'
                            }));
                        }

                        window.dispatchEvent(new CustomEvent('route-loaded', {
                            detail: {
                                roadKm: totalRoadKm,
                                driveTime: '±' + driveMinutes + ' menit',
                                motorTime: '±' + motorMinutes + ' menit',
                                walkTime: '±' + walkMinutes + ' menit',
                                steps: steps
                            }
                        }));

                        // Always fit route bounds so user clearly sees the path
                        map.fitBounds(routePolyline.getBounds(), { padding: [80, 80] });
                    } else {
                        throw new Error('OSRM No route');
                    }
                } catch (err) {
                    console.warn('OSRM fallback to direct line:', err);
                    roadCoordinates = [
                        [currentOriginLat, currentOriginLng],
                        [placeLat, placeLng]
                    ];

                    routeGlow = L.polyline(roadCoordinates, { color: '#ffffff', weight: 8, opacity: 0.6 }).addTo(map);
                    routePolyline = L.polyline(roadCoordinates, { color: '#0284c7', weight: 5, opacity: 0.95, dashArray: '10, 8' }).addTo(map);
                    
                    map.fitBounds([ [currentOriginLat, currentOriginLng], [placeLat, placeLng] ], { padding: [80, 80] });

                    const fallbackKm = Math.round(calculateHaversineKm(currentOriginLat, currentOriginLng, placeLat, placeLng) * 10) / 10;
                    const driveMin = Math.max(2, Math.round((fallbackKm / 35) * 60));
                    const motorMin = Math.max(2, Math.round((fallbackKm / 40) * 60));
                    const walkMin = Math.max(5, Math.round((fallbackKm / 4.5) * 60));

                    const fallbackSteps = [
                        { instruction: 'Mulai perjalanan dari ' + currentOriginName, distance: Math.round(fallbackKm * 1000 * 0.4), duration: Math.round(driveMin * 0.4) + ' mnt' },
                        { instruction: 'Ikuti jalur utama menuju kawasan ' + placeName, distance: Math.round(fallbackKm * 1000 * 0.6), duration: Math.round(driveMin * 0.6) + ' mnt' },
                        { instruction: 'Tiba di tempat belajar tujuan: ' + placeName, distance: 0, duration: '0 mnt' }
                    ];

                    window.dispatchEvent(new CustomEvent('route-loaded', {
                        detail: {
                            roadKm: fallbackKm,
                            driveTime: '±' + driveMin + ' menit',
                            motorTime: '±' + motorMin + ' menit',
                            walkTime: '±' + walkMin + ' menit',
                            steps: fallbackSteps
                        }
                    }));
                }
            };

            // 1. One-Shot Detect Device GPS as Origin (DOES NOT start live navigation tracking)
            window.setDeviceLocationAsOrigin = function() {
                if (!('geolocation' in navigator)) {
                    alert('Browser atau perangkat Anda tidak mendukung sensor GPS Geolocation.');
                    window.dispatchEvent(new CustomEvent('gps-error', {
                        detail: { error: 'Sensor GPS tidak didukung.' }
                    }));
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const uLat = position.coords.latitude;
                        const uLng = position.coords.longitude;
                        const accuracy = Math.round(position.coords.accuracy);

                        currentOriginLat = uLat;
                        currentOriginLng = uLng;
                        currentOriginName = 'Lokasi Perangkat Anda';

                        window.dispatchEvent(new CustomEvent('origin-changed', {
                            detail: {
                                lat: uLat,
                                lng: uLng,
                                name: 'Lokasi Perangkat Anda (GPS)'
                            }
                        }));

                        // Remove school marker from map
                        if (schoolMarker) {
                            map.removeLayer(schoolMarker);
                        }

                        // Add or move device location marker
                        if (!userGpsMarker) {
                            userGpsMarker = L.marker([uLat, uLng], { icon: gpsUserIcon, zIndexOffset: 2000 }).addTo(map);
                            userGpsMarker.bindPopup(`
                                <div style="font-family: inherit; font-size: 12px; max-width: 240px;">
                                    <strong style="color:#0f172a;">Lokasi Perangkat Anda Saat Ini</strong><br>
                                    <span style="font-size:11px; color:#64748b;">Akurasi sinyal: ±${accuracy} meter</span>
                                </div>
                            `);
                        } else {
                            userGpsMarker.setLatLng([uLat, uLng]);
                        }

                        // Recalculate route from device position
                        window.fetchRoadRoute(uLat, uLng, 'Lokasi Perangkat Anda', 'Posisi GPS Saat Ini', true);

                        window.dispatchEvent(new CustomEvent('gps-status-update', {
                            detail: {
                                connected: true,
                                message: `Lokasi perangkat terdeteksi (Akurasi: ±${accuracy}m)`
                            }
                        }));
                    },
                    function(err) {
                        let errMsg = 'Tidak dapat mendeteksi lokasi GPS perangkat.';
                        if (err.code === 1) errMsg = 'Akses lokasi ditolak. Silakan izinkan akses lokasi (GPS) di browser Anda.';
                        if (err.code === 2) errMsg = 'Posisi perangkat tidak ditemukan.';
                        if (err.code === 3) errMsg = 'Waktu pencarian sinyal GPS habis.';
                        alert(errMsg);
                        window.dispatchEvent(new CustomEvent('gps-error', {
                            detail: { error: errMsg }
                        }));
                    },
                    {
                        enableHighAccuracy: true,
                        maximumAge: 5000,
                        timeout: 10000
                    }
                );
            };

            // 2. Continuous Real-Time Geolocation Tracking (Only starts when user clicks 'Mulai Navigasi' with GPS)
            window.startGpsNavigation = function () {
                if (!('geolocation' in navigator)) {
                    window.dispatchEvent(new CustomEvent('gps-error', {
                        detail: { error: 'Browser atau perangkat Anda tidak mendukung sensor GPS Geolocation.' }
                    }));
                    return;
                }

                if (watchPositionId) {
                    navigator.geolocation.clearWatch(watchPositionId);
                }

                window.dispatchEvent(new CustomEvent('gps-status-update', {
                    detail: {
                        connected: false,
                        message: 'Menghubungkan ke sensor GPS perangkat...'
                    }
                }));

                watchPositionId = navigator.geolocation.watchPosition(
                    function (position) {
                        const uLat = position.coords.latitude;
                        const uLng = position.coords.longitude;
                        const accuracy = Math.round(position.coords.accuracy);

                        currentOriginLat = uLat;
                        currentOriginLng = uLng;

                        window.dispatchEvent(new CustomEvent('origin-changed', {
                            detail: {
                                lat: uLat,
                                lng: uLng,
                                name: 'Lokasi Perangkat Anda (GPS)'
                            }
                        }));

                        if (schoolMarker) {
                            map.removeLayer(schoolMarker);
                        }

                        if (!userGpsMarker) {
                            userGpsMarker = L.marker([uLat, uLng], { icon: gpsUserIcon, zIndexOffset: 2000 }).addTo(map);
                            userGpsMarker.bindPopup(`
                                <div style="font-family: inherit; font-size: 12px; max-width: 240px;">
                                    <strong style="color:#0f172a;">Posisi Perangkat Anda Saat Ini</strong><br>
                                    <span style="font-size:11px; color:#64748b;">Akurasi sinyal: ±${accuracy} meter</span>
                                </div>
                            `);
                            
                            map.setView([uLat, uLng], 17, { animate: true });
                            window.fetchRoadRoute(uLat, uLng, 'Lokasi GPS Perangkat', 'Posisi Saya Saat Ini', true);
                        } else {
                            userGpsMarker.setLatLng([uLat, uLng]);
                            map.panTo([uLat, uLng], { animate: true, duration: 0.5 });

                            const now = Date.now();
                            if (now - lastRouteFetchTime > 12000) {
                                lastRouteFetchTime = now;
                                window.fetchRoadRoute(uLat, uLng, 'Lokasi GPS Perangkat', '', true);
                            }
                        }

                        const dKm = calculateHaversineKm(uLat, uLng, placeLat, placeLng);
                        const remainingStr = dKm < 1 ? Math.round(dKm * 1000) + ' meter' : dKm.toFixed(1) + ' Km';

                        window.dispatchEvent(new CustomEvent('gps-status-update', {
                            detail: {
                                connected: true,
                                message: `GPS Terhubung (Akurasi: ±${accuracy}m) • Mengikuti Posisi Anda`,
                                remaining: remainingStr
                            }
                        }));
                    },
                    function (err) {
                        let errMsg = 'Tidak dapat mendeteksi lokasi GPS.';
                        if (err.code === 1) errMsg = 'Akses lokasi ditolak. Silakan izinkan akses lokasi (GPS) di browser Anda.';
                        if (err.code === 2) errMsg = 'Posisi tidak dapat ditemukan (periksa sinyal GPS perangkat).';
                        if (err.code === 3) errMsg = 'Waktu pencarian sinyal GPS habis (timeout).';

                        window.dispatchEvent(new CustomEvent('gps-error', {
                            detail: { error: errMsg }
                        }));
                    },
                    {
                        enableHighAccuracy: true,
                        maximumAge: 1000,
                        timeout: 12000
                    }
                );
            };

            // 3. Navigation along selected school route (Keeps departure origin locked to the user's selected school)
            window.startSchoolNavigation = function () {
                if (watchPositionId) {
                    navigator.geolocation.clearWatch(watchPositionId);
                    watchPositionId = null;
                }
                if (userGpsMarker) {
                    map.removeLayer(userGpsMarker);
                    userGpsMarker = null;
                }
                if (routePolyline) {
                    map.fitBounds(routePolyline.getBounds(), { padding: [60, 60] });
                } else if (schoolMarker) {
                    map.setView(schoolMarker.getLatLng(), 16, { animate: true });
                }
                if (schoolMarker) {
                    schoolMarker.openPopup();
                }
                window.dispatchEvent(new CustomEvent('gps-status-update', {
                    detail: {
                        connected: true,
                        message: `Panduan navigasi rute aktif dari ${currentOriginName}`,
                        remaining: totalRoadKm > 0 ? (totalRoadKm + ' Km') : ''
                    }
                }));
            };

            window.stopGpsNavigation = function () {
                if (watchPositionId) {
                    navigator.geolocation.clearWatch(watchPositionId);
                    watchPositionId = null;
                }
                if (userGpsMarker) {
                    map.removeLayer(userGpsMarker);
                    userGpsMarker = null;
                }
                window.resetView();
            };

            function calculateHaversineKm(lat1, lon1, lat2, lon2) {
                const R = 6371;
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                          Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                          Math.sin(dLon/2) * Math.sin(dLon/2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                return R * c;
            }

            window.resetView = function () {
                if (routePolyline) {
                    map.fitBounds(routePolyline.getBounds(), { padding: [80, 80] });
                } else if (schoolMarker && destMarker) {
                    const group = new L.featureGroup([schoolMarker, destMarker]);
                    map.fitBounds(group.getBounds(), { padding: [80, 80] });
                }
            };

            window.centerCurrentOrigin = function () {
                if (userGpsMarker) {
                    map.setView(userGpsMarker.getLatLng(), 17, { animate: true });
                } else if (schoolMarker) {
                    map.setView(schoolMarker.getLatLng(), 16, { animate: true });
                } else {
                    map.setView([placeLat, placeLng], 15, { animate: true });
                }
            };

            window.mapZoomIn = function () {
                map.zoomIn();
            };

            window.mapZoomOut = function () {
                map.zoomOut();
            };

            // Initial Route Calculation on Page Load
            window.fetchRoadRoute(schoolLat, schoolLng, schoolName, '', false);

            // Responsive Map resize handling
            setTimeout(() => { map.invalidateSize(); }, 200);
            setTimeout(() => { map.invalidateSize(); }, 600);
            window.addEventListener('resize', () => { map.invalidateSize(); });
        });
    </script>
</body>
</html>
