<header class="h-20 bg-transparent px-8 flex items-center justify-between gap-6 shrink-0 z-20">
    <!-- Left: Hamburger & Page Title -->
    <div class="flex items-center gap-4">
        <button type="button" class="w-10 h-10 rounded-2xl bg-white shadow-sm flex items-center justify-center text-slate-600 hover:text-gymove-blue transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h10M4 18h16" />
            </svg>
        </button>
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Rekomendasi Lokasi Studi</h1>
        </div>
    </div>

    <!-- Center: Capsule Search Bar -->
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

    <!-- Right: Squircle Indicator Action Buttons & User Profile -->
    <div class="flex items-center gap-4">
        <!-- 2 Squircle Buttons: SAW Matrix & Guide -->
        <div class="flex items-center gap-2.5">

            <!-- 2. SAW Matrix Detail Modal Button -->
            <button type="button" 
                    @click="showSawModal = true"
                    title="Buka Matriks Detail Perhitungan SAW"
                    class="w-11 h-11 rounded-2xl bg-gymove-soft-blue text-gymove-blue hover:bg-gymove-blue hover:text-white transition-all flex items-center justify-center relative group shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <!-- Orange Indicator Dot -->
                <span class="absolute top-2.5 right-2.5 w-2 h-2 rounded-full bg-gymove-orange ring-2 ring-white"></span>
            </button>

            <!-- 3. Panduan Penggunaan Button -->
            <button type="button" 
                    @click="showGuideModal = true"
                    title="Panduan Sistem SPK"
                    class="w-11 h-11 rounded-2xl bg-gymove-soft-blue text-gymove-blue hover:bg-gymove-blue hover:text-white transition-all flex items-center justify-center relative group shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="absolute top-2.5 right-2.5 w-2 h-2 rounded-full bg-gymove-orange ring-2 ring-white"></span>
            </button>
        </div>

        <!-- Vertical Divider -->
        <div class="h-8 w-px bg-slate-200"></div>

        <!-- User Profile (Gymove Peter Parkur style) -->
        <div class="flex items-center gap-3 pl-1">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop&crop=faces" 
                     alt="Avatar" 
                     class="w-11 h-11 rounded-2xl object-cover ring-2 ring-white shadow-sm" />
                <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-emerald-500 ring-2 ring-white"></span>
            </div>
            <div class="text-left hidden sm:block">
                <h4 class="text-sm font-extrabold text-slate-800 leading-tight">Drs. H. Arifin</h4>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-gymove-soft-blue text-gymove-blue">Tim Capstone RPL</span>
                </div>
            </div>
        </div>
    </div>
</header>
