@extends('layouts.student')

@section('title', 'Hasil Rekomendasi Tempat Belajar | EDU PESISIR')
@section('page_title', 'Hasil Rekomendasi Tempat Belajar')

@push('styles')
<style>
    /* Custom Red Blocked / Prohibited Circle Cursor */
    .cursor-blocked,
    .cursor-blocked * {
        cursor: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E%3Ccircle cx='12' cy='12' r='9' stroke='%23ffffff' stroke-width='3.5' fill='none'/%3E%3Cline x1='5.6' y1='5.6' x2='18.4' y2='18.4' stroke='%23ffffff' stroke-width='3.5'/%3E%3Ccircle cx='12' cy='12' r='9' stroke='%23dc2626' stroke-width='2.2' fill='%23fee2e2' fill-opacity='0.25'/%3E%3Cline x1='5.6' y1='5.6' x2='18.4' y2='18.4' stroke='%23dc2626' stroke-width='2.2'/%3E%3C/svg%3E") 12 12, not-allowed !important;
    }
</style>
@endpush

@section('content')
<div class="space-y-7" x-data="{
    searchFilter: '',
    matchesFilter(text) {
        if (!this.searchFilter.trim()) return true;
        return text.toLowerCase().includes(this.searchFilter.toLowerCase());
    },

    init() {
        // Bersihkan query parameter di URL agar jika tombol Refresh di browser ditekan, halaman langsung kembali ke tampilan awal bersih
        if (window.history && window.history.replaceState && window.location.search) {
            window.history.replaceState(null, '', window.location.pathname);
        }
    },

    // 267 Sekolah
    schools: {{ json_encode($allSchools) }},
    selectedSchoolId: {{ $selectedSchool ? $selectedSchool->id : 'null' }},
    selectedSchoolName: '{{ $selectedSchool ? addslashes($selectedSchool->nama_sekolah) : 'Belum Memilih Sekolah Asal' }}',
    selectedSchoolAddress: '{{ $selectedSchool ? addslashes($selectedSchool->alamat) : 'Ketik atau pilih dari 267 sekolah di Kota Bengkulu' }}',
    schoolJenjang: '{{ $selectedSchool ? $selectedSchool->id_jenjang : 'ALL' }}',
    schoolJenjangTab: '{{ $selectedSchool ? $selectedSchool->id_jenjang : 'ALL' }}',
    subjectTab: '{{ $selectedSchool ? $selectedSchool->id_jenjang : 'ALL' }}',
    showJenjangNotice: false,
    jenjangNoticeMessage: '',
    schoolQuery: '',
    isDropdownOpen: false,

    // 29 Mata Pelajaran
    selectedSubjects: {{ json_encode($selectedSubjectIds) }},
    // Peta jenjang, kode, dan nama per subject ID
    subjectJenjangMap: {{ json_encode($allSubjects->pluck('id_jenjang', 'id')) }},
    subjectCodeMap: {{ json_encode($allSubjects->pluck('kode_mapel', 'id')) }},
    subjectNameMap: {{ json_encode($allSubjects->pluck('nama_mapel', 'id')) }},

    // Autocomplete Filtering for Schools
    get filteredSchools() {
        let list = this.schools;
        if (this.schoolJenjangTab !== 'ALL') {
            list = list.filter(s => s.id_jenjang === this.schoolJenjangTab);
        }
        if (this.schoolQuery.trim() !== '') {
            const q = this.schoolQuery.toLowerCase();
            list = list.filter(s => 
                s.nama_sekolah.toLowerCase().includes(q) || 
                (s.alamat && s.alamat.toLowerCase().includes(q))
            );
        }
        return list;
    },

    // Active Subject Jenjang (deteksi jenjang dari mapel yang sedang dipilih atau tab)
    get activeSubjectJenjang() {
        if (this.selectedSubjects.length > 0) {
            return this.subjectJenjangMap[this.selectedSubjects[0]] || null;
        }
        if (this.subjectTab !== 'ALL') {
            return this.subjectTab;
        }
        return null;
    },

    // 1. MEMILIH JENJANG SEKOLAH ASAL (SD, SMP, SMA, atau SEMUA)
    chooseSchoolJenjang(jenjang) {
        this.showJenjangNotice = false;

        if (jenjang === 'ALL') {
            // Reset total ke tampilan awal yang bersih (netral, tanpa jenjang terpilih)
            this.schoolJenjang = 'ALL';
            this.schoolJenjangTab = 'ALL';
            this.selectedSchoolId = null;
            this.selectedSchoolName = 'Belum Memilih Sekolah Asal';
            this.selectedSchoolAddress = 'Ketik atau pilih dari 267 sekolah di Kota Bengkulu';
            this.subjectTab = 'ALL';
            this.selectedSubjects = [];

            // Bersihkan query string di URL jika ada
            if (window.history && window.history.replaceState) {
                window.history.replaceState(null, '', window.location.pathname);
            }
            return;
        }

        this.schoolJenjang = jenjang;
        this.schoolJenjangTab = jenjang;
        this.subjectTab = jenjang;

        // Filter mata pelajaran yang terpilih agar hanya mempertahankan yang sesuai dengan jenjang baru
        this.selectedSubjects = this.selectedSubjects.filter(sId => this.subjectJenjangMap[sId] === jenjang);

        // Jika sekolah saat ini tidak sesuai dengan jenjang yang dipilih, kosongkan pilihan sekolah
        if (this.selectedSchoolId) {
            const currentSchoolMatches = this.schools.some(s => s.id === this.selectedSchoolId && s.id_jenjang === jenjang);
            if (!currentSchoolMatches) {
                this.selectedSchoolId = null;
                this.selectedSchoolName = 'Belum Memilih Sekolah Asal';
                this.selectedSchoolAddress = 'Ketik atau pilih dari sekolah jenjang ' + this.getJenjangBadge(jenjang).label;
            }
        }

        // Buka panel pencarian sekolah agar siswa memilih sekolahnya sendiri
        this.isDropdownOpen = true;
    },

    chooseJenjang(jenjang) {
        this.chooseSchoolJenjang(jenjang);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    // 2. MEMILIH SEKOLAH DARI DROPDOWN PENCARIAN
    selectSchool(school) {
        this.selectedSchoolId = school.id;
        this.selectedSchoolName = school.nama_sekolah;
        this.selectedSchoolAddress = school.alamat;
        this.schoolJenjang = school.id_jenjang;
        this.schoolJenjangTab = school.id_jenjang;
        this.isDropdownOpen = false;
        this.schoolQuery = '';
        this.showJenjangNotice = false;

        // Buka tab mapel sesuai jenjang sekolah yang dipilih tanpa memaksa mapel default
        this.subjectTab = school.id_jenjang;

        // Filter mapel terpilih agar hanya mempertahankan yang cocok dengan jenjang sekolah baru
        this.selectedSubjects = this.selectedSubjects.filter(sId => this.subjectJenjangMap[sId] === school.id_jenjang);
    },

    // 3. MEMILIH TAB MATA PELAJARAN (SEMUA, SD, SMP, SMA)
    setSubjectTab(tab) {
        this.showJenjangNotice = false;
        this.subjectTab = tab;
    },

    // 4. MEMILIH / TOGGLE KARTU MATA PELAJARAN
    toggleSubject(id) {
        this.showJenjangNotice = false;

        // Mode pratinjau: jika jenjang sekolah belum dipilih (masih ALL), tidak bisa klik mapel
        if (this.schoolJenjang === 'ALL') {
            return;
        }

        const subjJenjang = this.subjectJenjangMap[id];

        // Jika jenjang mapel berbeda dengan jenjang sekolah aktif, tidak dapat dipilih
        if (this.schoolJenjang !== subjJenjang) {
            return;
        }

        // Toggle selection bebas (bisa pilih banyak, dan bisa uncheck sampai kosong)
        if (this.selectedSubjects.includes(id)) {
            this.selectedSubjects = this.selectedSubjects.filter(s => s !== id);
        } else {
            this.selectedSubjects.push(id);
        }
    },

    removeSubject(id) {
        this.selectedSubjects = this.selectedSubjects.filter(s => s !== id);
    },

    clearAllSubjects() {
        this.selectedSubjects = [];
    },

    getJenjangBadge(code) {
        if (code === 'JJ001') return { label: 'SD / MI', class: 'bg-emerald-50 text-emerald-700 border-emerald-200' };
        if (code === 'JJ002') return { label: 'SMP / MTs', class: 'bg-sky-50 text-sky-700 border-sky-200' };
        if (code === 'JJ003') return { label: 'SMA / SMK / MA', class: 'bg-indigo-50 text-indigo-700 border-indigo-200' };
        return { label: 'Semua Jenjang', class: 'bg-slate-100 text-slate-700 border-slate-200' };
    }
}">

    <!-- ============================================== -->
    <!-- 1. SELECTION & CRITERIA FORM PANEL (Gymove Style) -->
    <!-- ============================================== -->
    <div class="bg-white rounded-card p-6 md:p-8 shadow-soft-card border border-slate-100">

        <!-- Header Filter -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-slate-100 mb-6">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full bg-gymove-soft-blue text-gymove-blue">
                        Filter Rekomendasi Belajar
                    </span>
                    <span class="text-xs text-slate-400">Pilih sekolah asalmu &amp; mata pelajaran untuk menemukan tempat belajar terbaik</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Pilih Sekolah Asal &amp; Mata Pelajaran
                </h2>
            </div>

            <div class="flex items-center gap-2 self-start sm:self-auto">
                <a href="{{ route('frontend.places') }}" 
                   class="px-4 py-2 rounded-full bg-gymove-soft-blue hover:bg-gymove-blue hover:text-white text-gymove-blue text-xs font-bold transition-all">
                    <span>Lihat Semua 50 Tempat →</span>
                </a>
            </div>
        </div>

        <form action="/rekomendasi" method="GET" class="space-y-6">
            <input type="hidden" name="school" :value="selectedSchoolId">
            <template x-for="sId in selectedSubjects" :key="sId">
                <input type="hidden" name="subjects[]" :value="sId">
            </template>

            <!-- ============================================== -->
            <!-- 1. SEARCHABLE SCHOOL COMBOBOX (SD, SMP, SMA) -->
            <!-- ============================================== -->
            <div>
                <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-2.5 mb-2">
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                        1. Sekolah Asal Keberangkatan (267 Sekolah Terdata di Kota Bengkulu):
                    </label>

                    <!-- Jenjang Tabs for School (STRICTLY 1 SINGLE ROW, NO WRAP) -->
                    <div class="flex items-center bg-slate-100 p-1 rounded-full text-[11px] font-bold shrink-0 flex-nowrap whitespace-nowrap overflow-x-auto max-w-full">
                        <button type="button" 
                                @click="chooseSchoolJenjang('ALL')"
                                :class="schoolJenjangTab === 'ALL' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                                class="px-3 py-1.5 rounded-full transition-all shrink-0 whitespace-nowrap">
                            Semua (<span x-text="schools.length">267</span>)
                        </button>
                        <button type="button" 
                                @click="chooseSchoolJenjang('JJ001')"
                                :class="schoolJenjangTab === 'JJ001' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                                class="px-3 py-1.5 rounded-full transition-all shrink-0 whitespace-nowrap">
                            SD/MI (<span x-text="schools.filter(s => s.id_jenjang === 'JJ001').length">138</span>)
                        </button>
                        <button type="button" 
                                @click="chooseSchoolJenjang('JJ002')"
                                :class="schoolJenjangTab === 'JJ002' ? 'bg-white text-sky-700 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                                class="px-3 py-1.5 rounded-full transition-all shrink-0 whitespace-nowrap">
                            SMP/MTs (<span x-text="schools.filter(s => s.id_jenjang === 'JJ002').length">66</span>)
                        </button>
                        <button type="button" 
                                @click="chooseSchoolJenjang('JJ003')"
                                :class="schoolJenjangTab === 'JJ003' ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                                class="px-3 py-1.5 rounded-full transition-all shrink-0 whitespace-nowrap">
                            SMA/SMK (<span x-text="schools.filter(s => s.id_jenjang === 'JJ003').length">63</span>)
                        </button>
                    </div>
                </div>

                <!-- Selected School Active Pill / Input Box -->
                <div class="relative" @click.away="isDropdownOpen = false">
                    <div class="p-3 sm:p-4 rounded-2xl bg-gymove-pill border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 transition-all focus-within:ring-2 focus-within:ring-gymove-blue focus-within:bg-white">
                        
                        <!-- Left: Search input & Active School Info -->
                        <div class="flex-1 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-200/80 flex items-center justify-center shrink-0 text-gymove-blue">
                                <svg class="w-5 h-5 text-gymove-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                </svg>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs font-black text-slate-900 truncate" x-text="selectedSchoolName"></span>
                                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-md border" 
                                          :class="getJenjangBadge(schoolJenjang).class"
                                          x-text="getJenjangBadge(schoolJenjang).label"></span>
                                </div>
                                <p class="text-[11px] text-slate-500 truncate" x-text="selectedSchoolId ? ('Kecamatan: ' + (selectedSchoolAddress || 'Kota Bengkulu')) : 'Ketik atau klik tombol di samping untuk memilih sekolah asal'"></p>
                            </div>
                        </div>

                        <!-- Right: Toggle Search / Change button -->
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" 
                                    @click="isDropdownOpen = !isDropdownOpen" 
                                    class="px-4 py-2 rounded-xl bg-white hover:bg-slate-100 text-gymove-blue text-xs font-bold border border-slate-200 shadow-sm flex items-center gap-1.5 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span x-text="isDropdownOpen ? 'Tutup Pencarian' : 'Ketik / Ganti Sekolah'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Autocomplete Search Dropdown Panel -->
                    <div x-show="isDropdownOpen" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute z-50 left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 p-3 space-y-3">
                        
                        <!-- Search Input Field -->
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" 
                                   x-model="schoolQuery" 
                                   placeholder="Ketik nama sekolahmu (contoh: SDN 01, SMP 2, SMAN 1, MAN, Selebar, Ratu Samban...)" 
                                   class="w-full bg-slate-50 text-slate-800 text-xs font-bold pl-10 pr-10 py-3 rounded-xl border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gymove-blue transition-all"
                                   autofocus>
                            
                            <button type="button" 
                                    x-show="schoolQuery" 
                                    @click="schoolQuery = ''"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                ✕
                            </button>
                        </div>

                        <!-- Dropdown Results Count & Quick Info -->
                        <div class="flex items-center justify-between text-[11px] px-1 text-slate-400">
                            <span>Ditemukan <strong class="text-slate-700" x-text="filteredSchools.length"></strong> sekolah</span>
                            <span>Klik nama sekolah untuk memilih</span>
                        </div>

                        <!-- Scrollable List of 267 Schools -->
                        <div class="max-h-64 overflow-y-auto space-y-1 pr-1 divide-y divide-slate-100">
                            <template x-for="sch in filteredSchools" :key="sch.id">
                                <div @click="selectSchool(sch)" 
                                     class="p-2.5 rounded-xl hover:bg-gymove-soft-blue cursor-pointer transition-colors flex items-center justify-between gap-3 group">
                                    <div class="min-w-0 flex items-center gap-2.5">
                                        <span class="w-2 h-2 rounded-full shrink-0" 
                                              :class="sch.id === selectedSchoolId ? 'bg-gymove-orange' : 'bg-slate-300 group-hover:bg-gymove-blue'"></span>
                                        <div class="truncate">
                                            <div class="text-xs font-bold text-slate-800 group-hover:text-gymove-blue truncate" x-text="sch.nama_sekolah"></div>
                                            <div class="text-[10px] text-slate-400 truncate" x-text="'Kecamatan ' + sch.alamat"></div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">
                                        <span class="text-[9px] font-extrabold px-2 py-0.5 rounded-md border" 
                                              :class="getJenjangBadge(sch.id_jenjang).class"
                                              x-text="getJenjangBadge(sch.id_jenjang).label"></span>
                                        <span x-show="sch.id === selectedSchoolId" class="text-gymove-orange font-black text-xs">✓ Terpilih</span>
                                    </div>
                                </div>
                            </template>

                            <div x-show="filteredSchools.length === 0" class="p-6 text-center text-xs text-slate-400">
                                Tidak ada sekolah yang cocok dengan kata kunci "<span x-text="schoolQuery" class="font-bold"></span>".
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- 2. PILIH MATA PELAJARAN (LENGKAP 29 MAPEL SESUAI KURIKULUM) -->
            <!-- ============================================== -->
            <div>
                <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-3 mb-2.5">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                2. Pilih Mata Pelajaran (Lengkap 29 Mapel Sesuai Kurikulum):
                            </label>
                            <template x-if="schoolJenjang !== 'ALL'">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border shadow-sm"
                                      :class="getJenjangBadge(schoolJenjang).class"
                                      x-text="getJenjangBadge(schoolJenjang).label">
                                </span>
                            </template>
                        </div>
                        <span class="text-xs text-slate-400 mt-0.5 block">
                            <template x-if="schoolJenjang !== 'ALL'">
                                <span>Menampilkan mata pelajaran tingkat <strong class="text-slate-700" x-text="getJenjangBadge(schoolJenjang).label"></strong>. Bisa memilih lebih dari 1 mata pelajaran.</span>
                            </template>
                            <template x-if="schoolJenjang === 'ALL'">
                                <span>Pilih mata pelajaran yang ingin dipelajari untuk mendapatkan rekomendasi tempat belajar.</span>
                            </template>
                        </span>
                    </div>

                    <!-- Jenjang Tabs for Subjects (STRICTLY 1 SINGLE ROW, NO WRAP) -->
                    <div class="flex items-center bg-slate-100 p-1 rounded-full text-[11px] font-bold shrink-0 flex-nowrap whitespace-nowrap overflow-x-auto max-w-full">
                        <button type="button" 
                                @click="setSubjectTab('ALL')"
                                :class="subjectTab === 'ALL' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                                class="px-3 py-1.5 rounded-full transition-all shrink-0 whitespace-nowrap">
                            Semua Mapel (29)
                        </button>
                        <button type="button" 
                                @click="setSubjectTab('JJ001')"
                                :class="subjectTab === 'JJ001' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                                class="px-3 py-1.5 rounded-full transition-all shrink-0 whitespace-nowrap">
                            Mapel SD (8)
                        </button>
                        <button type="button" 
                                @click="setSubjectTab('JJ002')"
                                :class="subjectTab === 'JJ002' ? 'bg-white text-sky-700 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                                class="px-3 py-1.5 rounded-full transition-all shrink-0 whitespace-nowrap">
                            Mapel SMP (9)
                        </button>
                        <button type="button" 
                                @click="setSubjectTab('JJ003')"
                                :class="subjectTab === 'JJ003' ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                                class="px-3 py-1.5 rounded-full transition-all shrink-0 whitespace-nowrap">
                            Mapel SMA (12)
                        </button>
                    </div>
                </div>

                <!-- Friendly Inactive Warning Banner (appears if student submits without selecting) -->
                <div x-show="showJenjangNotice" 
                     x-transition 
                     class="mb-3 p-3 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span x-text="jenjangNoticeMessage"></span>
                    </div>
                    <button type="button" @click="showJenjangNotice = false" class="text-amber-500 hover:text-amber-800 ml-2">✕</button>
                </div>

                <!-- Currently Selected Subjects Chips Box -->
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-[11px] font-black uppercase tracking-wider text-slate-500 shrink-0">
                            Mapel Terpilih (<span x-text="selectedSubjects.length"></span>):
                        </span>
                        
                        <template x-if="selectedSubjects.length === 0">
                            <span class="text-xs text-slate-400 italic">
                                Belum ada mata pelajaran dipilih (klik salah satu kartu mapel di bawah)
                            </span>
                        </template>

                        <template x-if="selectedSubjects.length > 0">
                            <template x-for="sId in selectedSubjects" :key="sId">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gymove-blue text-white text-xs font-bold shadow-sm">
                                    <span x-text="subjectNameMap[sId] || ('Mapel #' + sId)"></span>
                                    <button type="button" 
                                            @click="removeSubject(sId)" 
                                            title="Hapus mata pelajaran ini"
                                            class="w-4 h-4 rounded-full bg-white/20 hover:bg-white hover:text-gymove-blue flex items-center justify-center text-[10px] leading-none transition-colors">
                                        ✕
                                    </button>
                                </span>
                            </template>
                        </template>
                    </div>
                </div>

                <!-- Subjects Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5 pt-1 mt-2.5">
                    @foreach($allSubjects as $subj)
                        <div x-show="subjectTab === 'ALL' || subjectTab === '{{ $subj->id_jenjang }}'"
                             @click="toggleSubject({{ $subj->id }})"
                             class="flex items-center gap-2.5 p-3 rounded-2xl border transition-all select-none text-xs font-bold relative group"
                             :class="schoolJenjang === 'ALL'
                                      ? 'cursor-blocked bg-slate-50/80 text-slate-700 border-slate-200 shadow-none'
                                      : (schoolJenjang !== '{{ $subj->id_jenjang }}'
                                          ? 'cursor-blocked opacity-40 bg-slate-100 text-slate-400 border-slate-200 select-none'
                                          : (selectedSubjects.includes({{ $subj->id }}) 
                                              ? 'cursor-pointer bg-gymove-blue text-white border-gymove-blue shadow-blue-glow' 
                                              : 'cursor-pointer bg-white text-slate-700 border-slate-200 hover:border-slate-300 hover:bg-slate-50'))">
                            
                            <input type="checkbox" 
                                   :value="{{ $subj->id }}" 
                                   class="hidden" 
                                   :checked="selectedSubjects.includes({{ $subj->id }})">

                            <span class="w-2.5 h-2.5 rounded-full shrink-0 transition-transform group-hover:scale-110"
                                  :class="selectedSubjects.includes({{ $subj->id }}) ? 'bg-amber-300' : 'bg-slate-300'"></span>

                            <div class="min-w-0 flex-1">
                                <div class="truncate">{{ $subj->nama_mapel }}</div>
                                <div class="text-[10px] font-semibold flex items-center gap-1.5 opacity-80">
                                    <span>{{ $subj->kode_mapel }}</span>
                                    <span>•</span>
                                    <span>{{ $subj->jenjang_label }}</span>
                                </div>
                            </div>

                            <span class="text-[10px] font-extrabold px-1.5 py-0.5 rounded shrink-0"
                                  :class="selectedSubjects.includes({{ $subj->id }}) ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'">
                                {{ $subj->locations_count }} Lokasi
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between text-xs text-slate-400 mt-2">
                    <span x-text="selectedSubjects.length > 0 ? (selectedSubjects.length + ' Mapel ' + (schoolJenjang !== 'ALL' ? getJenjangBadge(schoolJenjang).label : '') + ' Aktif Dipilih') : 'Pilih mata pelajaran yang ingin dipelajari'"></span>
                    <span class="italic">Kombinasi mapel otomatis menganalisis materi belajar terpadu</span>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- 3. SUBMIT ACTION BUTTON (Single Gymove Orange Pill) -->
            <!-- ============================================== -->
            <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100">
                <button type="submit" 
                        @click="if (!selectedSchoolId || selectedSubjects.length === 0) { 
                            $event.preventDefault(); 
                            if (!selectedSchoolId) {
                                jenjangNoticeMessage = 'Silakan tentukan sekolah asal terlebih dahulu pada Bagian 1 untuk menghitung jarak rekomendasi.';
                            } else {
                                jenjangNoticeMessage = 'Silakan pilih minimal 1 mata pelajaran terlebih dahulu.';
                            }
                            showJenjangNotice = true; 
                        }"
                        class="w-full sm:w-auto px-8 py-3.5 rounded-full bg-gymove-orange hover:bg-gymove-orange-hover text-white text-xs font-black shadow-orange-glow transition-all active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Cari Rekomendasi Tempat Belajar</span>
                </button>

                <p class="text-xs text-slate-400 font-medium">
                    Jarak dihitung otomatis dari gerbang: <strong class="text-slate-700" x-text="selectedSchoolName"></strong>
                </p>
            </div>
        </form>
    </div>

    <!-- ============================================== -->
    <!-- 2. QUICK FILTER SEARCH INSIDE RECOMMENDATIONS -->
    <!-- ============================================== -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Daftar Tempat Belajar yang Direkomendasikan</h3>
            <p class="text-xs text-slate-400 mt-0.5">
                @if(!empty($recommendationData['is_initial']))
                    Pilih jenjang sekolah asal dan mata pelajaran untuk menghitung rekomendasi
                @else
                    Ditemukan <strong>{{ count($recommendationData['results']) }}</strong> pilihan tempat belajar paling relevan untukmu
                @endif
            </p>
        </div>

        <div class="w-full sm:w-72 relative">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" 
                   x-model="searchFilter" 
                   placeholder="Filter hasil rekomendasi..." 
                   class="w-full bg-white text-slate-700 text-xs font-medium pl-10 pr-4 py-2.5 rounded-2xl border border-slate-200 focus:border-gymove-blue focus:outline-none transition-all shadow-sm" />
        </div>
    </div>

    <!-- ============================================== -->
    <!-- 3. LIST OF RECOMMENDATIONS (Gymove Reference Style) -->
    <!-- ============================================== -->
    @if(!empty($recommendationData['is_initial']))
        <div class="bg-white rounded-card p-10 md:p-14 text-center border border-slate-100 shadow-soft-card">
            <div class="w-16 h-16 rounded-2xl bg-gymove-soft-blue text-gymove-blue mx-auto flex items-center justify-center mb-4 shadow-sm">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h4 class="text-lg font-black text-slate-800">Tentukan Sekolah Asal &amp; Mata Pelajaran</h4>
            <p class="text-xs text-slate-500 mt-1.5 max-w-lg mx-auto leading-relaxed">
                Mulai dengan memilih jenjang pendidikan (<strong class="text-emerald-700">SD/MI</strong>, <strong class="text-sky-700">SMP/MTs</strong>, atau <strong class="text-indigo-700">SMA/SMK</strong>) dan sekolah asalmu di Kota Bengkulu, lalu tentukan mata pelajaran yang ingin dipelajari untuk mendapatkan rekomendasi tempat belajar terdekat dan paling sesuai.
            </p>
            <div class="mt-6 flex items-center justify-center gap-3 flex-wrap">
                <button type="button" 
                        @click="chooseJenjang('JJ001')" 
                        class="px-5 py-2.5 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold transition-all flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Pilih Jenjang SD / MI</span>
                </button>
                <button type="button" 
                        @click="chooseJenjang('JJ002')" 
                        class="px-5 py-2.5 rounded-full bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 text-xs font-bold transition-all flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                    <span>Pilih Jenjang SMP / MTs</span>
                </button>
                <button type="button" 
                        @click="chooseJenjang('JJ003')" 
                        class="px-5 py-2.5 rounded-full bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-bold transition-all flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    <span>Pilih Jenjang SMA / SMK</span>
                </button>
            </div>
        </div>
    @elseif(empty($recommendationData['results']))
        <div class="bg-white rounded-card p-12 text-center border border-slate-100 shadow-soft-card">
            <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-500 mx-auto flex items-center justify-center mb-3">
                <svg class="w-7 h-7 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h4 class="text-base font-extrabold text-slate-800">Tidak ada tempat yang cocok secara langsung</h4>
            <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                Coba pilih mata pelajaran lainnya atau jelajahi direktori lengkap untuk melihat seluruh tempat belajar edukatif di Kota Bengkulu.
            </p>
            <div class="mt-5">
                <a href="{{ route('home') }}" class="px-6 py-2.5 rounded-full bg-gymove-blue text-white text-xs font-bold shadow-sm">
                    Kembali ke Pemilihan
                </a>
            </div>
        </div>
    @else

        <div class="space-y-4">
            @foreach($recommendationData['results'] as $idx => $item)
                @php
                    $isTopRank = ($item['rank'] ?? ($idx + 1)) === 1;
                    $loc = $item['location'] ?? null;
                @endphp

                @if($loc)
                    <div x-show="matchesFilter('{{ addslashes($loc->nama_lokasi) }} {{ addslashes($loc->alamat_kawasan) }}')"
                         class="rounded-card transition-all duration-200 {{ $isTopRank ? 'bg-gradient-to-r from-gymove-blue to-[#1c38a8] text-white p-6 shadow-xl relative overflow-hidden' : 'bg-white hover:bg-slate-50/80 p-5 border border-slate-100 shadow-sm' }}">
                        
                        @if($isTopRank)
                            <!-- Top Glow -->
                            <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                        @endif

                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 relative z-10">
                            
                            <!-- Left: Rank Badge + Info -->
                            <div class="flex items-start sm:items-center gap-4">
                                
                                <!-- Squircle Rank Badge -->
                                <div class="w-14 h-14 rounded-2xl flex flex-col items-center justify-center shrink-0 shadow-sm {{ $isTopRank ? 'bg-white/15 text-white border border-white/20' : 'bg-gymove-soft-blue text-gymove-blue' }}">
                                    <span class="text-[10px] font-black uppercase tracking-wider {{ $isTopRank ? 'text-amber-300' : 'text-slate-400' }}">
                                        RANK
                                    </span>
                                    <span class="text-xl font-black leading-none mt-0.5">
                                        #{{ $item['rank'] ?? ($idx + 1) }}
                                    </span>
                                </div>

                                <!-- Text Details -->
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="text-base sm:text-lg font-black tracking-tight {{ $isTopRank ? 'text-white' : 'text-slate-900' }}">
                                            {{ $loc->nama_lokasi }}
                                        </h3>

                                        @if($isTopRank)
                                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-400/20 text-emerald-300 border border-emerald-400/30 flex items-center gap-1">
                                                <svg class="w-3 h-3 text-emerald-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                <span>Rekomendasi Utama</span>
                                            </span>
                                        @endif

                                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-md {{ stripos($loc->biaya, 'gratis') !== false ? ($isTopRank ? 'bg-white/20 text-emerald-300' : 'bg-emerald-50 text-emerald-700 border border-emerald-200') : ($isTopRank ? 'bg-amber-400/20 text-amber-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                            {{ $loc->biaya }}
                                        </span>
                                    </div>

                                    <p class="text-xs mt-1 flex items-start gap-1 {{ $isTopRank ? 'text-white/80' : 'text-slate-500' }}">
                                        <svg class="w-3.5 h-3.5 shrink-0 mt-0.5 {{ $isTopRank ? 'text-amber-300' : 'text-gymove-orange' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>{{ $loc->alamat_kawasan }}</span>
                                    </p>

                                    <!-- Quick Meta Specs -->
                                    <div class="flex items-center gap-4 mt-2 text-xs font-semibold flex-wrap {{ $isTopRank ? 'text-white/80' : 'text-slate-500' }}">
                                        <span class="flex items-center gap-1 font-bold {{ $isTopRank ? 'text-amber-300' : 'text-gymove-orange' }}">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                            <span>{{ $item['distance_km'] ?? '?' }} Km dari {{ $selectedSchool ? $selectedSchool->nama_sekolah : 'Sekolah Asal' }}</span>
                                        </span>
                                        <span>•</span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{{ $loc->jam_operasional }}</span>
                                        </span>
                                        <span>•</span>
                                        <span class="font-bold text-emerald-500">
                                            ● {{ $loc->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Matched Subjects + Detail Button -->
                            <div class="flex items-center gap-3 shrink-0 flex-wrap justify-between lg:justify-end">
                                
                                <!-- Tags of matched subjects -->
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    @if(!empty($item['matched_topics']))
                                        @foreach($item['matched_topics'] as $top)
                                            <span class="text-[11px] font-bold px-2.5 py-1 rounded-xl {{ $isTopRank ? 'bg-white/15 text-white border border-white/20' : 'bg-slate-100 text-slate-700' }}">
                                                {{ $top['nama'] ?? $top['kode'] }}
                                            </span>
                                        @endforeach
                                    @else
                                        @foreach($loc->subjects->take(2) as $s)
                                            <span class="text-[11px] font-bold px-2.5 py-1 rounded-xl {{ $isTopRank ? 'bg-white/15 text-white border border-white/20' : 'bg-slate-100 text-slate-700' }}">
                                                {{ $s->nama_mapel }}
                                            </span>
                                        @endforeach
                                    @endif
                                </div>

                                <!-- Peta Rute Internal Button -->
                                <a :href="'/peta/{{ $loc->id }}' + (selectedSchoolId ? '?school=' + selectedSchoolId : '')"
                                   href="{{ route('frontend.places.map', ['location' => $loc->id, 'school' => $selectedSchool->id ?? null]) }}" 
                                   class="px-4 py-2.5 rounded-full text-xs font-bold transition-all active:scale-95 flex items-center gap-1.5 {{ $isTopRank ? 'bg-white/20 hover:bg-white/30 text-white border border-white/30' : 'bg-gymove-pill hover:bg-slate-200 text-slate-700' }}"
                                   :title="'Buka Peta Rute dari ' + (selectedSchoolName || 'Sekolah Asal')">
                                    <svg class="w-3.5 h-3.5 text-gymove-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                    <span>Peta Rute</span>
                                </a>

                                <!-- Action Button -->
                                <a :href="'/tempat-belajar/{{ $loc->id }}' + (selectedSchoolId ? '?school=' + selectedSchoolId : '')"
                                   href="{{ route('frontend.places.show', ['location' => $loc->id, 'school' => $selectedSchool->id ?? null]) }}" 
                                   class="px-5 py-2.5 rounded-full text-xs font-black tracking-wide transition-all active:scale-95 flex items-center gap-1.5 {{ $isTopRank ? 'bg-gymove-orange hover:bg-gymove-orange-hover text-white shadow-orange-glow' : 'bg-gymove-soft-blue hover:bg-gymove-blue text-gymove-blue hover:text-white' }}">
                                    <span>Detail Tempat</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>

                        </div>
                    </div>

                @elseif(isset($item['points']))
                    <!-- Skenario 2: Paket Pasangan Lokasi Berdekatan -->
                    <div class="bg-white rounded-card p-6 border border-slate-100 shadow-sm space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-gymove-orange font-black flex items-center justify-center text-sm">
                                    #{{ $item['rank'] ?? ($idx + 1) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800">
                                            Kombinasi 2 Lokasi Berdekatan
                                        </span>
                                        @if(!empty($item['covers_all']))
                                            <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center gap-1">
                                                <span>✓</span>
                                                <span>Semua {{ $item['coverage_count'] }} Mapel Terakomodasi</span>
                                            </span>
                                        @elseif(isset($item['coverage_count']))
                                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ $item['coverage_count'] }} Mapel Terakomodasi
                                            </span>
                                        @endif
                                    </div>
                                    <h4 class="text-base font-extrabold text-slate-800 mt-1">
                                        {{ $item['package_name'] ?? 'Paket Kunjungan Multi-Lokasi' }}
                                    </h4>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        Jarak antar-lokasi: <strong>{{ $item['inter_distance_km'] ?? '' }} Km</strong> ({{ $item['travel_time_est'] ?? '' }})
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Sub-points -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-2">
                            @foreach($item['points'] as $p)
                                <div class="p-3.5 rounded-2xl bg-gymove-pill border border-slate-200/60 flex items-start justify-between gap-3">
                                    <div>
                                        <span class="text-[10px] font-black px-2 py-0.5 rounded bg-gymove-blue text-white">
                                            Titik {{ $p['order'] }}
                                        </span>
                                        <h5 class="text-sm font-extrabold text-slate-800 mt-1">
                                            {{ $p['location']->nama_lokasi }}
                                        </h5>
                                        <p class="text-[11px] text-slate-500 mt-0.5">
                                            Materi: <strong class="text-gymove-blue">{{ $p['subject']->nama_mapel }}</strong>
                                        </p>
                                        @if(!empty($p['matched_subjects_list']) && count($p['matched_subjects_list']) > 1)
                                            <div class="flex items-center gap-1 flex-wrap mt-1.5">
                                                @foreach($p['matched_subjects_list'] as $sName)
                                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-md bg-white text-gymove-blue border border-slate-200 shadow-2xs">
                                                        {{ $sName }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <a :href="'/peta/{{ $p['location']->id }}' + (selectedSchoolId ? '?school=' + selectedSchoolId : '')"
                                           href="{{ route('frontend.places.map', ['location' => $p['location']->id, 'school' => $selectedSchool->id ?? null]) }}" 
                                           class="text-xs font-bold text-gymove-orange hover:underline flex items-center gap-1">
                                            <span>Rute</span>
                                        </a>
                                        <span class="text-slate-300">•</span>
                                        <a :href="'/tempat-belajar/{{ $p['location']->id }}' + (selectedSchoolId ? '?school=' + selectedSchoolId : '')"
                                           href="{{ route('frontend.places.show', ['location' => $p['location']->id, 'school' => $selectedSchool->id ?? null]) }}" 
                                           class="text-xs font-bold text-gymove-blue hover:text-gymove-orange">
                                            Detail →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            @endforeach
        </div>

    @endif

</div>
@endsection
