<!-- Modal Detail Perhitungan Matriks SAW -->
<div x-show="showSawModal" 
     x-cloak 
     class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    <div class="bg-white rounded-card w-full max-w-4xl p-7 shadow-2xl border border-slate-100 relative max-h-[90vh] overflow-y-auto"
         @click.away="showSawModal = false">
        
        <!-- Header -->
        <div class="flex items-center justify-between pb-5 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-gymove-soft-blue text-gymove-blue font-extrabold flex items-center justify-center text-sm">
                        ∑
                    </span>
                    <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Transparansi Perhitungan Metode SAW</h3>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-1">
                    Metode Simple Additive Weighting (SAW) mencari penjumlahan terbobot dari rating kinerja pada setiap alternatif di semua kriteria.
                </p>
            </div>
            
            <button type="button" 
                    @click="showSawModal = false"
                    class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-lg transition-colors">
                ✕
            </button>
        </div>

        <!-- 1. Kriteria & Bobot W -->
        <div class="mt-6">
            <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-3">1. Bobot Kriteria ($W$)</h4>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <div class="p-3.5 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-900">
                    <span class="text-[10px] font-bold text-indigo-500 block uppercase">C1 • Relevansi</span>
                    <div class="text-lg font-black text-indigo-950 mt-0.5" x-text="(weights.c1 * 100).toFixed(0) + '%'"></div>
                    <span class="text-[10px] font-semibold text-indigo-600">Sifat: Benefit (Maksimal)</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-100 text-amber-900">
                    <span class="text-[10px] font-bold text-amber-500 block uppercase">C2 • Jarak Tempuh</span>
                    <div class="text-lg font-black text-amber-950 mt-0.5" x-text="(weights.c2 * 100).toFixed(0) + '%'"></div>
                    <span class="text-[10px] font-semibold text-amber-700">Sifat: Cost (Minimal)</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-900">
                    <span class="text-[10px] font-bold text-emerald-500 block uppercase">C3 • Fasilitas</span>
                    <div class="text-lg font-black text-emerald-950 mt-0.5" x-text="(weights.c3 * 100).toFixed(0) + '%'"></div>
                    <span class="text-[10px] font-semibold text-emerald-600">Sifat: Benefit (Maksimal)</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-cyan-50 border border-cyan-100 text-cyan-900">
                    <span class="text-[10px] font-bold text-cyan-500 block uppercase">C4 • Keamanan</span>
                    <div class="text-lg font-black text-cyan-950 mt-0.5" x-text="(weights.c4 * 100).toFixed(0) + '%'"></div>
                    <span class="text-[10px] font-semibold text-cyan-700">Sifat: Benefit (Maksimal)</span>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- SKENARIO 1: MATRIKS TUNGGAL TERPADU -->
        <!-- ============================================== -->
        <template x-if="currentScenario === 'SINGLE_MATCH'">
            <div>
                <!-- 2. Matriks Keputusan X -->
                <div class="mt-7">
                    <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">2. Matriks Keputusan ($X$)</h4>
                    <div class="overflow-x-auto rounded-2xl border border-slate-100 shadow-sm">
                        <table class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-gymove-pill text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="p-3">Alternatif Lokasi Pesisir</th>
                                    <th class="p-3 text-center">C1: Relevansi</th>
                                    <th class="p-3 text-center">C2: Jarak (Km)</th>
                                    <th class="p-3 text-center">C3: Fasilitas</th>
                                    <th class="p-3 text-center">C4: Keamanan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-if="sawData && sawData.raw_matrix_x">
                                    <template x-for="row in sawData.raw_matrix_x" :key="row.id">
                                        <tr class="hover:bg-slate-50">
                                            <td class="p-3 font-bold text-slate-800" x-text="row.nama"></td>
                                            <td class="p-3 text-center font-mono font-semibold" x-text="row.c1"></td>
                                            <td class="p-3 text-center font-mono font-semibold text-gymove-orange" x-text="row.c2 + ' Km'"></td>
                                            <td class="p-3 text-center font-mono font-semibold" x-text="row.c3"></td>
                                            <td class="p-3 text-center font-mono font-semibold" x-text="row.c4"></td>
                                        </tr>
                                    </template>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. Matriks Ternormalisasi R -->
                <div class="mt-7">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">3. Matriks Ternormalisasi ($R$)</h4>
                        <span class="text-[11px] text-slate-400">Formula: Benefit = x/max | Cost = min/x</span>
                    </div>
                    <div class="overflow-x-auto rounded-2xl border border-slate-100 shadow-sm">
                        <table class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-gymove-pill text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="p-3">Alternatif Lokasi Pesisir</th>
                                    <th class="p-3 text-center">r1 (C1 Benefit)</th>
                                    <th class="p-3 text-center">r2 (C2 Cost)</th>
                                    <th class="p-3 text-center">r3 (C3 Benefit)</th>
                                    <th class="p-3 text-center">r4 (C4 Benefit)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-if="sawData && sawData.normalized_matrix_r">
                                    <template x-for="row in sawData.normalized_matrix_r" :key="row.id">
                                        <tr class="hover:bg-slate-50">
                                            <td class="p-3 font-bold text-slate-800" x-text="row.nama"></td>
                                            <td class="p-3 text-center font-mono font-semibold" x-text="row.r1"></td>
                                            <td class="p-3 text-center font-mono font-semibold text-gymove-orange" x-text="row.r2"></td>
                                            <td class="p-3 text-center font-mono font-semibold" x-text="row.r3"></td>
                                            <td class="p-3 text-center font-mono font-semibold" x-text="row.r4"></td>
                                        </tr>
                                    </template>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 4. Nilai Preferensi V -->
                <div class="mt-7">
                    <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">4. Nilai Preferensi ($V_i = \sum w_j \cdot r_{ij}$)</h4>
                    <div class="overflow-x-auto rounded-2xl border border-slate-100 shadow-sm">
                        <table class="w-full text-left text-xs text-slate-600">
                            <thead class="bg-gymove-blue text-[11px] font-bold text-white uppercase tracking-wider">
                                <tr>
                                    <th class="p-3">Peringkat</th>
                                    <th class="p-3">Nama Lokasi</th>
                                    <th class="p-3">Formula Perkalian Bobot</th>
                                    <th class="p-3 text-right">Skor Akhir ($V$)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-if="sawData && sawData.preference_matrix_v">
                                    <template x-for="(row, idx) in sawData.preference_matrix_v" :key="row.id">
                                        <tr class="hover:bg-slate-50" :class="idx === 0 ? 'bg-indigo-50/50 font-bold' : ''">
                                            <td class="p-3 font-extrabold text-gymove-blue" x-text="'#' + (idx + 1)"></td>
                                            <td class="p-3 font-bold text-slate-900" x-text="row.nama"></td>
                                            <td class="p-3 font-mono text-[11px] text-slate-500" x-text="row.formula"></td>
                                            <td class="p-3 text-right font-black font-mono text-sm text-gymove-blue" x-text="row.v"></td>
                                        </tr>
                                    </template>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </template>

        <!-- ============================================== -->
        <!-- SKENARIO 2: EVALUASI PER MATA PELAJARAN & SPATIAL PROXIMITY -->
        <!-- ============================================== -->
        <template x-if="currentScenario === 'MULTI_LOCATION_NEARBY'">
            <div class="mt-7 space-y-6">
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 leading-relaxed">
                    <strong class="font-extrabold block">Evaluasi SAW Skenario 2 (Multi-Mapel Terpencar):</strong>
                    Karena tidak ada satu lokasi yang mencakup semua mata pelajaran terpilih, sistem mengevaluasi matriks keputusan SAW untuk masing-masing mata pelajaran secara independen, kemudian memadukan lokasi dengan <strong>jarak spasial terpendek (Haversine Formula)</strong>.
                </div>

                <template x-if="sawData && sawData.per_subject_tables">
                    <template x-for="(subjTable, sIdx) in sawData.per_subject_tables" :key="sIdx">
                        <div class="border border-slate-200/80 rounded-2xl p-4 shadow-sm bg-slate-50/50">
                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-gymove-blue text-white flex items-center justify-center text-[10px]" x-text="subjTable.subject_code"></span>
                                <span x-text="'Matriks SAW Mapel: ' + subjTable.subject_name"></span>
                            </h4>

                            <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
                                <table class="w-full text-left text-xs text-slate-600">
                                    <thead class="bg-gymove-pill text-[10px] font-bold text-slate-500 uppercase">
                                        <tr>
                                            <th class="p-2.5">Kandidat Lokasi</th>
                                            <th class="p-2.5 text-center">C1 (Rel)</th>
                                            <th class="p-2.5 text-center">C2 (Km)</th>
                                            <th class="p-2.5 text-center">C3 (Fas)</th>
                                            <th class="p-2.5 text-center">C4 (Aman)</th>
                                            <th class="p-2.5 text-center font-bold text-gymove-blue">Skor SAW (V)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-[11px]">
                                        <template x-for="cand in subjTable.candidates" :key="cand.nama">
                                            <tr class="hover:bg-slate-50">
                                                <td class="p-2.5 font-bold text-slate-800" x-text="cand.nama"></td>
                                                <td class="p-2.5 text-center font-mono" x-text="cand.c1"></td>
                                                <td class="p-2.5 text-center font-mono text-gymove-orange" x-text="cand.c2 + ' Km'"></td>
                                                <td class="p-2.5 text-center font-mono" x-text="cand.c3"></td>
                                                <td class="p-2.5 text-center font-mono" x-text="cand.c4"></td>
                                                <td class="p-2.5 text-center font-mono font-black text-gymove-blue text-xs" x-text="cand.v"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>
                </template>
            </div>
        </template>

        <!-- Close Button -->
        <div class="mt-7 pt-4 border-t border-slate-100 flex justify-end">
            <button type="button" 
                    @click="showSawModal = false"
                    class="bg-gymove-blue text-white font-bold px-6 py-2.5 rounded-full text-xs shadow-sm hover:bg-gymove-blue-hover transition-colors">
                Tutup Jendela Matriks
            </button>
        </div>
    </div>
</div>
