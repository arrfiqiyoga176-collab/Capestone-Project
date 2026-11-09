<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title', 'Admin Backend | PESISIR-EDU Kota Bengkulu')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Tailwind CSS with Gymove Palette -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'gymove-canvas': '#F4F7F9',
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
                        'card': '28px',
                    },
                    boxShadow: {
                        'soft-card': '0 10px 40px rgba(19, 41, 133, 0.05)',
                        'blue-glow': '0 10px 25px rgba(19, 41, 133, 0.28)',
                        'orange-glow': '0 8px 20px rgba(255, 138, 43, 0.35)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <!-- Leaflet.js -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #F4F7F9; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 9999px; }
    </style>

    @stack('styles')
</head>
<body class="bg-gymove-canvas font-sans text-slate-700 antialiased min-h-screen selection:bg-gymove-blue selection:text-white">

<div class="flex min-h-screen">
    <!-- 1. ADMIN SIDEBAR (w-64) -->
    <aside class="w-64 bg-white flex flex-col justify-between shrink-0 h-screen sticky top-0 overflow-y-auto border-r border-slate-100 shadow-[4px_0_30px_rgba(19,41,133,0.02)] z-30">
        <div>
            <!-- Brand Logo -->
            <div class="p-6 pb-6 flex items-center gap-3.5 border-b border-slate-100 bg-white">
                <img src="{{ asset('images/logo.png') }}" alt="Logo EDU PESISIR" class="w-12 h-12 rounded-2xl object-contain p-1 bg-white shadow-sm ring-1 ring-slate-100 shrink-0">
                <div>
                    <h2 class="text-base font-black text-gymove-blue tracking-tight leading-none flex items-center gap-1">
                        <span>EDU PESISIR</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-gymove-orange"></span>
                    </h2>
                    <p class="text-[10px] font-bold text-slate-400 tracking-wider mt-1 uppercase">Panel Administrator</p>
                </div>
            </div>

            <!-- Logged In Status Capsule -->
            <div class="px-5 pt-4 pb-2">
                <div class="p-3 rounded-2xl bg-gymove-soft-blue border border-indigo-100 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-gymove-blue">Sesi Admin Aktif</span>
                        </div>
                        <div class="text-xs font-bold text-slate-800 truncate mt-0.5">
                            {{ auth()->user()->name ?? 'Administrator' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-5 pt-3 pb-2">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block">Main Menu</span>
            </div>

            <nav class="space-y-1.5 px-3">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-gymove-blue text-white shadow-blue-glow' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span>Overview Admin</span>
                </a>

                <!-- Kelola Lokasi Pesisir (CRUD) -->
                <a href="{{ route('admin.locations') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.locations*') ? 'bg-gymove-blue text-white shadow-blue-glow' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Kelola Lokasi (CRUD)</span>
                </a>

                <!-- Kelola Mata Pelajaran -->
                <a href="{{ route('admin.subjects') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('admin.subjects*') ? 'bg-gymove-blue text-white shadow-blue-glow' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Kelola Mapel (CRUD)</span>
                </a>

            </nav>
        </div>

        <div class="p-5">
            <div class="p-4 rounded-2xl bg-slate-900 text-white shadow-md">
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span class="text-xs font-bold text-slate-200">Database MySQL Aktif</span>
                </div>
                <p class="text-[11px] text-slate-400">Database: <code class="text-amber-300 font-mono">pesisir_edu</code></p>
                <p class="text-[11px] text-slate-400 mt-0.5">Port Server: <code class="text-cyan-300 font-mono">8000</code></p>
            </div>
            <div class="mt-4 text-[11px] text-slate-400 font-medium text-center">
                PESISIR-EDU Backend Admin<br>© 2026 Kota Bengkulu
            </div>
        </div>
    </aside>

    <!-- 2. MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Admin Header -->
        <header class="h-20 bg-transparent px-8 flex items-center justify-between gap-6 shrink-0">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">@yield('page_title', 'Admin Panel')</h1>
                <p class="text-xs text-slate-400 font-medium mt-0.5">@yield('page_subtitle', 'Pengelolaan data lokasi dan parameter sistem')</p>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" 
                   class="px-4 py-2.5 rounded-full bg-white hover:bg-slate-50 text-gymove-blue text-xs font-bold transition-all border border-slate-200 shadow-sm flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-gymove-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>Buka Portal Siswa</span>
                </a>

                <div class="h-8 w-px bg-slate-200"></div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-slate-900 to-gymove-blue text-white font-extrabold flex items-center justify-center text-sm shadow-sm">
                        {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
                    </div>
                    <div class="text-left">
                        <h4 class="text-xs font-extrabold text-slate-800">{{ auth()->user()->name ?? 'Administrator' }}</h4>
                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Terotentikasi
                        </span>
                    </div>
                </div>

                <!-- Logout Button -->
                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-3.5 py-2.5 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition-all border border-rose-200 shadow-sm flex items-center gap-1.5"
                            title="Keluar dari sesi administrator">
                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mx-8 mb-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-700">✕</button>
            </div>
        @endif

        @if($errors->any())
            <div class="mx-8 mb-4 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-medium">
                <strong class="font-bold block mb-1">Terjadi kesalahan input:</strong>
                <ul class="list-disc ml-4 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Admin Body Content -->
        <main class="flex-1 px-8 pb-10 min-w-0">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
