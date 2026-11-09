<aside class="w-64 bg-white flex flex-col justify-between shrink-0 min-h-screen border-r border-slate-100 shadow-[4px_0_30px_rgba(19,41,133,0.02)] z-30">
    <!-- Top Part: Logo & Navigation -->
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
                <p class="text-[11px] font-semibold text-slate-400 tracking-wide uppercase">WebGIS & SPK Bengkulu</p>
            </div>
        </div>

        <!-- Section Label -->
        <div class="px-6 pb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
            Main Menu
        </div>

        <!-- Navigation Links -->
        <nav class="space-y-1.5 px-3">
            <!-- 1. Dashboard & Peta -->
            <a href="{{ route('home') }}" 
               @click.prevent="activeTab = 'dashboard'; focusMap()"
               class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all group"
               :class="activeTab === 'dashboard' ? 'bg-gymove-soft-blue text-gymove-blue border-r-4 border-gymove-blue font-bold shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors"
                     :class="activeTab === 'dashboard' ? 'bg-white text-gymove-blue shadow-sm' : 'bg-slate-100 text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600'">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span>Dashboard & Peta</span>
            </a>

            <!-- 2. Rekomendasi Lokasi (SAW) - DEFAULT ACTIVE -->
            <a href="{{ route('recommendation.index') }}" 
               @click.prevent="activeTab = 'rekomendasi'"
               class="flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold transition-all group"
               :class="activeTab === 'rekomendasi' ? 'bg-gymove-soft-blue text-gymove-blue border-r-4 border-gymove-blue font-bold shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors"
                     :class="activeTab === 'rekomendasi' ? 'bg-white text-gymove-blue shadow-sm' : 'bg-slate-100 text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600'">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <span>Rekomendasi (SAW)</span>
            </a>

            <!-- 3. Peta Jarak & Rute (WebGIS) -->
            <button type="button"
               @click="switchRightTab('map'); focusMap()"
               class="w-full flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all group text-left">
                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                </div>
                <span>Peta Jarak & Rute</span>
            </button>

            <!-- 4. Matriks Perhitungan SAW -->
            <button type="button" 
               @click="showSawModal = true"
               class="w-full flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all group text-left">
                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
                <span>Matriks SAW (X, R, V)</span>
            </button>

            <!-- 5. Panduan Siswa -->
            <button type="button" 
               @click="showGuideModal = true"
               class="w-full flex items-center gap-3.5 px-4 py-3 rounded-2xl text-sm font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-50 transition-all group text-left">
                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span>Panduan Pembelajaran</span>
            </button>
        </nav>
    </div>

    <!-- Bottom Part: Signature Deep Royal Blue CTA Card & Copyright -->
    <div class="p-5">
        <!-- Call To Action Card (Gymove Signature) -->
        <div class="bg-gymove-blue text-white rounded-2xl p-4.5 shadow-blue-glow relative overflow-hidden group cursor-pointer transition-all hover:scale-[1.02]"
             @click="focusSelector()">
            <!-- Background pattern decorative circle -->
            <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-white/10 blur-sm pointer-events-none"></div>

            <div class="flex items-center gap-3 relative z-10">
                <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center text-white shrink-0 group-hover:bg-gymove-orange transition-colors">
                    <svg class="w-6 h-6 text-gymove-orange group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-sm leading-tight text-white">Simulasi SAW & Multi-Mapel</h4>
                    <p class="text-[11px] text-white/70 font-medium mt-0.5">Uji coba skenario 1 & 2</p>
                </div>
            </div>
        </div>

        <!-- Footer Copyright -->
        <div class="mt-5 text-[11px] text-slate-400 font-medium leading-relaxed text-center">
            PesisirEdu Admin Dashboard<br>
            <span class="text-slate-300">© 2026 All Rights Reserved</span>
        </div>
    </div>
</aside>
