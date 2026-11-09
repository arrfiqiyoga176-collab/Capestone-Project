<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title', 'EDU PESISIR | Rekomendasi Tempat Belajar Edukatif Kota Bengkulu')</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN with Custom Gymove & Coastal Theme) -->
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
                        'soft-hover': '0 14px 45px rgba(19, 41, 133, 0.08)',
                        'blue-glow': '0 10px 25px rgba(19, 41, 133, 0.28)',
                        'orange-glow': '0 8px 20px rgba(255, 138, 43, 0.35)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #F4F7F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gymove-canvas font-sans text-slate-700 antialiased min-h-screen selection:bg-gymove-blue selection:text-white"
      x-data="{ sidebarOpen: true, mobileMenuOpen: false }">

    <div class="flex min-h-screen">
        
        <!-- ============================================== -->
        <!-- 1. LEFT SIDEBAR NAVIGATION (COLLAPSIBLE) -->
        <!-- ============================================== -->
        <aside class="transition-all duration-300 ease-in-out bg-white border-r border-slate-100 flex flex-col justify-between shrink-0 z-30 sticky top-0 h-screen overflow-y-auto"
               :class="sidebarOpen ? 'w-64' : 'w-20'">
            
            <div>
                <!-- Brand Logo & Header -->
                <div class="h-20 flex items-center px-4 border-b border-slate-100 gap-3 overflow-hidden bg-white">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo EDU PESISIR" class="w-12 h-12 rounded-2xl object-contain p-1 bg-white shadow-sm ring-1 ring-slate-100 shrink-0">
                        <div x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap overflow-hidden">
                            <h2 class="text-base font-black text-gymove-blue tracking-tight leading-none flex items-center gap-1">
                                <span>EDU PESISIR</span>
                                <span class="w-1.5 h-1.5 rounded-full bg-gymove-orange"></span>
                            </h2>
                            <p class="text-[10px] font-bold text-slate-400 tracking-wider mt-1 uppercase">Portal Rekomendasi</p>
                        </div>
                    </a>
                </div>

                <!-- Main Menu Section Header -->
                <div class="px-5 pt-5 pb-2" x-show="sidebarOpen" x-transition.opacity>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block">Main Menu</span>
                </div>

                <!-- Main Nav Links -->
                <nav class="p-3 space-y-1.5">
                    <!-- 1. Beranda Utama -->
                    <a href="{{ route('home') }}" 
                       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('home') ? 'bg-gymove-blue text-white shadow-blue-glow' : 'text-slate-500 hover:bg-slate-100 hover:text-gymove-blue' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Beranda Utama</span>
                    </a>

                    <!-- 2. Halaman Hasil Rekomendasi -->
                    <a href="{{ route('frontend.recommendations') }}" 
                       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('frontend.recommendations') ? 'bg-gymove-blue text-white shadow-blue-glow' : 'text-slate-500 hover:bg-slate-100 hover:text-gymove-blue' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Rekomendasi Belajar</span>
                    </a>

                    <!-- 3. Direktori Tempat Belajar (50 Lokasi) -->
                    <a href="{{ route('frontend.places') }}" 
                       class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ request()->routeIs('frontend.places*') ? 'bg-gymove-blue text-white shadow-blue-glow' : 'text-slate-500 hover:bg-slate-100 hover:text-gymove-blue' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Direktori Tempat Belajar</span>
                    </a>
                </nav>

                <!-- Gymove Signature Widget Card in Sidebar -->
                <div class="p-3" x-show="sidebarOpen" x-transition.opacity>
                    <div class="bg-gradient-to-br from-gymove-blue to-[#1e3bb3] rounded-2xl p-4 text-white shadow-blue-glow text-left space-y-2">
                        <div class="w-8 h-8 rounded-xl bg-white/15 flex items-center justify-center text-white">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                        </div>
                        <h4 class="text-xs font-extrabold leading-tight">267 Sekolah & 50 Tempat</h4>
                        <p class="text-[10px] text-white/70 leading-relaxed">Terhubung dengan 29 mata pelajaran jenjang SD, SMP, & SMA.</p>
                        <a href="{{ route('frontend.recommendations') }}" class="inline-block mt-1 text-[10px] font-bold bg-white text-gymove-blue px-3 py-1.5 rounded-full hover:bg-amber-300 hover:text-slate-900 transition-colors">
                            Cari Rekomendasi →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom Section: Admin Access -->
            <div class="p-3 border-t border-slate-100 bg-white">
                <a href="{{ route('admin.login') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-[11px] font-bold text-slate-400 hover:text-gymove-orange hover:bg-amber-50/60 transition-all">
                    <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Login Pengelola (Admin)</span>
                </a>
            </div>
        </aside>

        <!-- ============================================== -->
        <!-- 2. MAIN CONTENT AREA -->
        <!-- ============================================== -->
        <div class="flex-1 flex flex-col min-w-0">
            
            <!-- TOP NAVBAR HEADER -->
            <header class="h-20 bg-white/80 backdrop-blur-md px-6 flex items-center justify-between gap-4 border-b border-slate-100 sticky top-0 z-20">
                
                <!-- Left: Hamburger & Page Title -->
                <div class="flex items-center gap-3.5">
                    <!-- Hamburger Toggle Button -->
                    <button type="button" 
                            @click="sidebarOpen = !sidebarOpen" 
                            title="Toggle Menu Samping"
                            class="w-10 h-10 rounded-2xl bg-gymove-pill hover:bg-gymove-soft-blue text-slate-600 hover:text-gymove-blue transition-colors flex items-center justify-center cursor-pointer active:scale-95 shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h10M4 18h16" />
                        </svg>
                    </button>

                    <div>
                        <h1 class="text-lg font-black text-slate-900 tracking-tight leading-none">
                            @yield('page_title', 'Rekomendasi Tempat Belajar Edukatif')
                        </h1>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Kota Bengkulu</p>
                    </div>
                </div>

                <!-- Center: Functional Search Input (Searches Tempat Belajar) -->
                <div class="flex-1 max-w-md mx-2 hidden sm:block">
                    <form action="/tempat" method="GET" class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" 
                               name="q" 
                               value="{{ request('q') }}"
                               placeholder="Cari tempat belajar, museum, taman, atau mata pelajaran..." 
                               class="w-full bg-gymove-pill hover:bg-slate-100 focus:bg-white text-slate-700 text-xs font-medium pl-10 pr-10 py-2.5 rounded-full border border-transparent focus:border-gymove-blue/40 focus:outline-none transition-all placeholder:text-slate-400" />
                        <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs font-bold text-gymove-blue hover:text-gymove-blue-hover">
                            Cari
                        </button>
                    </form>
                </div>

                <!-- Right: Action Icons & Profile -->
                <div class="flex items-center gap-2">
                    <!-- Direktori Icon -->
                    <a href="{{ route('frontend.places') }}" title="Direktori Tempat Belajar" class="w-10 h-10 rounded-2xl bg-gymove-pill hover:bg-gymove-soft-blue hidden sm:flex items-center justify-center text-slate-500 hover:text-gymove-blue transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </a>

                    <!-- Student Profile Capsule -->
                    <div class="flex items-center gap-2 pl-2 border-l border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-gymove-soft-blue text-gymove-blue font-black flex items-center justify-center ring-1 ring-white shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                        </div>
                        <div class="text-left hidden lg:block">
                            <h4 class="text-xs font-extrabold text-slate-800 leading-tight">Siswa Kota Bengkulu</h4>
                            <span class="text-[10px] font-bold text-slate-400 block">Portal Pembelajaran</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- PAGE CONTENT INJECTION -->
            <main class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto">
                @yield('content')
            </main>

            <!-- FOOTER -->
            <footer class="py-6 px-8 border-t border-slate-100 text-center text-xs text-slate-400">
                <p>© {{ date('Y') }} <strong>EDU PESISIR</strong> — Sistem Informasi Rekomendasi Tempat Belajar Edukatif Kota Bengkulu.</p>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
