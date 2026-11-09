<div id="control-panel-container" class="bg-white rounded-card p-6 shadow-soft-card border border-slate-100/80 mb-7 transition-all">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-slate-100">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-gymove-orange animate-ping"></span>
                <span class="w-2 h-2 rounded-full bg-gymove-orange -ml-3.5"></span>
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Kombinasi Multi-Mata Pelajaran & Parameter SPK</h3>
            </div>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
                Pilih <strong class="text-gymove-blue">1 atau lebih dari 1 mata pelajaran</strong> sekaligus untuk mengaktifkan logika cerdas <span class="text-gymove-orange font-bold">Single-Match</span> vs <span class="text-gymove-blue font-bold">Proximity Clustering (Haversine)</span>.
            </p>
        </div>

        <!-- Quick Preset Badges for Testing Lecturer Revision Scenarios -->
        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tes Skenario:</span>
            <!-- Test Preset 1: Single Match -->
            <button type="button" 
                    @click="applyPreset(['GEO', 'EKO'])" 
                    class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors border border-indigo-200/50 flex items-center gap-1.5 shadow-sm">
                <span>📍 Skenario 1 (Geo + Eko)</span>
            </button>
            <!-- Test Preset 2: Multi Nearby -->
            <button type="button" 
                    @click="applyPreset(['BIO', 'SEJ'])" 
                    class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 hover:bg-amber-100 transition-colors border border-amber-200/50 flex items-center gap-1.5 shadow-sm">
                <span>⚡ Skenario 2 (Bio + Sej)</span>
            </button>
        </div>
    </div>

    <!-- 1. Interactive Multi-Subject Pill Chips -->
    <div class="mt-5">
        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">
            Pilih Mata Pelajaran (Bisa Multi-Select):
        </label>
        <div class="flex flex-wrap gap-2.5">
            @foreach($allSubjects as $sub)
                <button type="button" 
                        @click="toggleSubject({{ $sub->id }})"
                        :class="selectedSubjects.includes({{ $sub->id }}) 
                            ? 'ring-2 ring-offset-2 ring-gymove-blue scale-[1.03] shadow-md font-bold' 
                            : 'opacity-70 hover:opacity-100 hover:scale-[1.01]'"
                        class="px-4 py-2.5 rounded-2xl text-xs font-semibold flex items-center gap-2.5 transition-all cursor-pointer"
                        style="background-color: {{ $sub->warna_bg_soft }}; color: {{ $sub->warna_teks }}; border: 1px solid {{ $sub->warna_teks }}30;">
                    
                    <!-- Subject Icon Badge -->
                    <span class="w-6 h-6 rounded-lg flex items-center justify-center font-extrabold text-[11px] shadow-sm"
                          style="background-color: {{ $sub->warna_teks }}; color: #ffffff;">
                        {{ $sub->kode_mapel }}
                    </span>
                    
                    <span>{{ $sub->nama_mapel }}</span>

                    <!-- Check Indicator -->
                    <template x-if="selectedSubjects.includes({{ $sub->id }})">
                        <svg class="w-4 h-4 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </template>
                </button>
            @endforeach
        </div>
    </div>

    <!-- 2. Origin & Weight Configuration Drawer / Toggle -->
    <div class="mt-6 pt-5 border-t border-slate-100 grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
        <!-- Departure Point Selector -->
        <div class="md:col-span-5">
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                Titik Awal Keberangkatan (Sekolah):
            </label>
            <div class="relative">
                <select x-model="selectedPreset" 
                        @change="handlePresetChange()"
                        class="w-full bg-gymove-pill hover:bg-slate-100 text-slate-800 text-xs font-semibold py-3 px-4 pr-9 rounded-2xl border border-slate-200/80 focus:border-gymove-blue focus:bg-white focus:outline-none transition-all shadow-inner">
                    @foreach($departurePresets as $dp)
                        <option value="{{ $dp['id'] }}">{{ $dp['name'] }} ({{ $dp['address'] }})</option>
                    @endforeach
                    <option value="custom">📍 Titik Kustom (Klik di Peta)</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
            <p class="text-[11px] text-slate-400 mt-1.5 flex items-center gap-1">
                <span>Koordinat Asal:</span>
                <span class="font-mono text-slate-600 font-semibold" x-text="originLat.toFixed(4) + ', ' + originLng.toFixed(4)"></span>
            </p>
        </div>

        <!-- SAW Weights Display / Sliders -->
        <div class="md:col-span-5">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Bobot Kriteria SAW:
                </label>
                <button type="button" @click="resetWeights()" class="text-[11px] text-gymove-orange font-bold hover:underline">
                    Reset Standar
                </button>
            </div>
            
            <div class="grid grid-cols-4 gap-2">
                <!-- C1 -->
                <div class="bg-slate-50 p-2 rounded-xl text-center border border-slate-200/60">
                    <span class="text-[10px] font-bold text-slate-400 block">C1 Relevansi</span>
                    <input type="number" step="0.05" min="0.05" max="1" x-model.number="weights.c1" 
                           class="w-full text-center text-xs font-extrabold text-gymove-blue bg-transparent focus:outline-none" />
                </div>
                <!-- C2 -->
                <div class="bg-slate-50 p-2 rounded-xl text-center border border-slate-200/60">
                    <span class="text-[10px] font-bold text-slate-400 block">C2 Jarak (Cost)</span>
                    <input type="number" step="0.05" min="0.05" max="1" x-model.number="weights.c2" 
                           class="w-full text-center text-xs font-extrabold text-gymove-blue bg-transparent focus:outline-none" />
                </div>
                <!-- C3 -->
                <div class="bg-slate-50 p-2 rounded-xl text-center border border-slate-200/60">
                    <span class="text-[10px] font-bold text-slate-400 block">C3 Fasilitas</span>
                    <input type="number" step="0.05" min="0.05" max="1" x-model.number="weights.c3" 
                           class="w-full text-center text-xs font-extrabold text-gymove-blue bg-transparent focus:outline-none" />
                </div>
                <!-- C4 -->
                <div class="bg-slate-50 p-2 rounded-xl text-center border border-slate-200/60">
                    <span class="text-[10px] font-bold text-slate-400 block">C4 Keamanan</span>
                    <input type="number" step="0.05" min="0.05" max="1" x-model.number="weights.c4" 
                           class="w-full text-center text-xs font-extrabold text-gymove-blue bg-transparent focus:outline-none" />
                </div>
            </div>
        </div>

        <!-- Action Button -->
        <div class="md:col-span-2">
            <button type="button" 
                    @click="triggerCalculation()" 
                    :disabled="isLoading"
                    class="w-full bg-gymove-blue hover:bg-gymove-blue-hover text-white py-3.5 px-4 rounded-2xl text-xs font-extrabold tracking-wide shadow-blue-glow transition-all flex items-center justify-center gap-2 active:scale-95 disabled:opacity-50">
                <template x-if="isLoading">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                <template x-if="!isLoading">
                    <svg class="w-4 h-4 text-gymove-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </template>
                <span x-text="isLoading ? 'Memproses...' : 'Hitung SAW'"></span>
            </button>
        </div>
    </div>
</div>
