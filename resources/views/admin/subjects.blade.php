@extends('admin.layout')

@section('title', 'Kelola Mapel Kurikulum (CRUD) | Admin PESISIR-EDU')
@section('page_title', 'Matriks Kurikulum & Mata Pelajaran Pesisir')
@section('page_subtitle', 'Pengelolaan 29+ mata pelajaran kurikulum (SD, SMP, & SMA) dan pemetaan topik tempat belajar')

@section('content')
<div x-data="subjectCrud()" class="space-y-6">

    <!-- Top Info & Action Banner (Gymove Style) -->
    <div class="bg-white rounded-card p-6 shadow-soft-card border border-slate-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gymove-soft-blue flex items-center justify-center text-gymove-blue shrink-0">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900 tracking-tight">{{ $subjects->count() }} Mata Pelajaran Kurikulum Terintegrasi</h3>
                <p class="text-xs text-slate-500 mt-0.5">Kelola data mata pelajaran, jenjang, kode unik, serta pemetaan topik kajian pada lokasi edukatif pesisir Bengkulu.</p>
            </div>
        </div>
        <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
            <span class="px-3.5 py-2 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-100 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>{{ $subjects->count() }} Mapel Aktif</span>
            </span>

            <button type="button" 
                    @click="openCreateModal()" 
                    class="px-5 py-2.5 rounded-full bg-gymove-orange hover:bg-gymove-orange-hover text-white text-xs font-bold transition-all shadow-orange-glow flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Mapel Baru</span>
            </button>
        </div>
    </div>

    <!-- Filter Tabs & Search Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Gymove Pill Tabs -->
        <div class="flex items-center bg-white p-1.5 rounded-full border border-slate-200 text-xs font-bold shadow-sm self-start sm:self-auto flex-wrap">
            <button type="button" 
                    @click="activeTab = 'ALL'"
                    :class="activeTab === 'ALL' ? 'bg-gymove-blue text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                    class="px-4 py-2 rounded-full transition-all">
                Semua Mapel ({{ $subjects->count() }})
            </button>
            <button type="button" 
                    @click="activeTab = 'JJ001'"
                    :class="activeTab === 'JJ001' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                    class="px-4 py-2 rounded-full transition-all">
                SD / MI ({{ $subjects->where('id_jenjang', 'JJ001')->count() }})
            </button>
            <button type="button" 
                    @click="activeTab = 'JJ002'"
                    :class="activeTab === 'JJ002' ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                    class="px-4 py-2 rounded-full transition-all">
                SMP / MTs ({{ $subjects->where('id_jenjang', 'JJ002')->count() }})
            </button>
            <button type="button" 
                    @click="activeTab = 'JJ003'"
                    :class="activeTab === 'JJ003' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                    class="px-4 py-2 rounded-full transition-all">
                SMA / SMK ({{ $subjects->where('id_jenjang', 'JJ003')->count() }})
            </button>
        </div>

        <!-- Search Input -->
        <div class="relative w-full sm:w-72">
            <input type="text" 
                   x-model="searchQuery" 
                   placeholder="Cari mapel atau kode..." 
                   class="w-full bg-white text-slate-700 text-xs font-medium pl-9 pr-4 py-2.5 rounded-full border border-slate-200 focus:border-gymove-blue focus:outline-none transition-all shadow-sm" />
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
        </div>
    </div>

    <!-- Subjects Grid (CRUD Cards) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        @foreach($subjects as $subj)
        <div x-show="matches('{{ addslashes($subj->nama_mapel) }}', '{{ $subj->kode_mapel }}', '{{ $subj->id_jenjang }}')"
             class="bg-white rounded-card p-6 shadow-soft-card border border-slate-100 flex flex-col justify-between hover:border-indigo-200 transition-all group">
            <div>
                <!-- Header with Action Buttons -->
                <div class="flex items-start justify-between gap-4 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold text-xs shadow-sm shrink-0"
                             style="background-color: {{ $subj->warna_bg_soft ?? '#EEF2FF' }}; color: {{ $subj->warna_teks ?? '#132985' }}">
                            {{ $subj->kode_mapel }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="text-base font-bold text-slate-900">{{ $subj->nama_mapel }}</h4>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider"
                                      style="background-color: {{ $subj->warna_bg_soft ?? '#EEF2FF' }}; color: {{ $subj->warna_teks ?? '#132985' }}">
                                    {{ $subj->jenjang_label }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5 line-clamp-2">{{ $subj->deskripsi }}</p>
                        </div>
                    </div>

                    <!-- Action buttons (Edit & Delete) + Location Counter Badge -->
                    <div class="flex flex-col items-end gap-2 shrink-0">
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-slate-100 text-slate-700">
                            {{ $subj->locations->count() }} Lokasi
                        </span>
                        
                        <div class="flex items-center gap-1.5">
                            <!-- Edit Button -->
                            <button type="button" 
                                    @click="openEditModal({{ json_encode($subj) }})"
                                    class="p-1.5 rounded-xl bg-gymove-soft-blue text-gymove-blue hover:bg-gymove-blue hover:text-white transition-all text-xs font-semibold"
                                    title="Edit Mata Pelajaran">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.subjects.destroy', $subj->id) }}" method="POST" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran {{ addslashes($subj->nama_mapel) }} ({{ $subj->kode_mapel }})? Hubungan lokasi yang tertaut akan otomatis dilepas.')"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-1.5 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all text-xs font-semibold"
                                        title="Hapus Mata Pelajaran">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Locations teaching this subject -->
                <div class="mt-4">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5 flex items-center justify-between">
                        <span>Lokasi Terverifikasi &amp; Topik Lapangan:</span>
                        <span class="text-[10px] text-slate-400 font-normal">Kajian C1</span>
                    </div>
                    <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                        @forelse($subj->locations as $loc)
                        <div class="p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors border border-slate-100 flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-800 truncate">{{ $loc->nama_lokasi }}</span>
                                    <span class="text-[9px] px-1.5 py-0.5 rounded font-bold {{ stripos($loc->biaya, 'gratis') !== false ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ stripos($loc->biaya, 'gratis') !== false ? 'Gratis' : 'Berbayar' }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-1 italic">
                                    "{{ $loc->pivot->topik_kajian ?? 'Kajian kurikulum lapangan' }}"
                                </p>
                            </div>
                            <div class="shrink-0 text-right">
                                <span class="text-[9px] font-semibold text-slate-400 block">Skor C1</span>
                                <span class="text-xs font-black text-gymove-blue font-mono">{{ $loc->pivot->skor_relevansi_c1 ?? 80 }}/100</span>
                            </div>
                        </div>
                        @empty
                        <div class="p-4 rounded-xl bg-slate-50 text-slate-400 text-xs text-center border border-dashed border-slate-200">
                            Belum ada lokasi yang ditautkan ke mata pelajaran ini. Anda dapat menautkannya melalui menu Kelola Lokasi.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Footer summary -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                <span>Rata-rata Skor Relevansi Lapangan:</span>
                <span class="font-bold text-slate-700 font-mono">
                    {{ $subj->locations->count() > 0 ? round($subj->locations->avg('pivot.skor_relevansi_c1'), 1) : 0 }} / 100
                </span>
            </div>
        </div>
        @endforeach
    </div>

    <!-- 1. MODAL TAMBAH MAPEL BARU (CREATE) -->
    <div x-show="showCreate" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-card w-full max-w-xl p-7 shadow-2xl border border-slate-100 relative max-h-[90vh] overflow-y-auto" @click.away="showCreate = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-orange-50 text-gymove-orange flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Tambah Mata Pelajaran Baru</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Integrasikan mata pelajaran baru ke dalam matriks kurikulum</p>
                    </div>
                </div>
                <button type="button" @click="showCreate = false" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 font-bold hover:bg-slate-200 transition-colors">✕</button>
            </div>

            <form action="{{ route('admin.subjects.store') }}" method="POST" class="mt-5 space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Mata Pelajaran *</label>
                    <input type="text" name="nama_mapel" placeholder="Contoh: Pendidikan Kemaritiman & Konservasi Laut" required class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200 focus:border-gymove-blue focus:bg-white focus:outline-none transition-all" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jenjang Pendidikan *</label>
                        <select name="id_jenjang" required class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200 focus:border-gymove-blue focus:bg-white focus:outline-none transition-all">
                            <option value="JJ001">SD / MI (Sekolah Dasar)</option>
                            <option value="JJ002">SMP / MTs (Sekolah Menengah Pertama)</option>
                            <option value="JJ003" selected>SMA / SMK / MA (Sekolah Menengah Atas)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Mapel (Opsional)</label>
                        <input type="text" name="kode_mapel" placeholder="Kosongkan untuk auto (MP030...)" class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200 focus:border-gymove-blue focus:bg-white focus:outline-none transition-all uppercase font-mono" />
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi / Capaian Pembelajaran Lapangan *</label>
                    <textarea name="deskripsi" rows="3" placeholder="Jelaskan fokus kurikulum dan capaian kompetensi pembelajaran luar kelas yang diharapkan..." required class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200 focus:border-gymove-blue focus:bg-white focus:outline-none transition-all"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Warna Teks Badge</label>
                        <input type="text" name="warna_teks" placeholder="#132985 (Otomatis jika kosong)" class="w-full bg-gymove-pill px-3 py-2 rounded-xl border border-slate-200 font-mono text-[11px]" />
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Warna Background Badge</label>
                        <input type="text" name="warna_bg_soft" placeholder="#EEF2FF (Otomatis jika kosong)" class="w-full bg-gymove-pill px-3 py-2 rounded-xl border border-slate-200 font-mono text-[11px]" />
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="showCreate = false" class="px-5 py-2.5 rounded-full text-slate-500 font-bold hover:bg-slate-100 transition-colors">Batal</button>
                    <button type="submit" class="bg-gymove-orange hover:bg-gymove-orange-hover text-white px-6 py-2.5 rounded-full font-bold shadow-orange-glow transition-all">
                        Simpan Mata Pelajaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. MODAL EDIT MAPEL (UPDATE) -->
    <div x-show="showEdit" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-card w-full max-w-xl p-7 shadow-2xl border border-slate-100 relative max-h-[90vh] overflow-y-auto" @click.away="showEdit = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-gymove-blue flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Edit Mata Pelajaran Kurikulum</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Perbarui nama, kode kurikulum, atau jenjang pendidikan</p>
                    </div>
                </div>
                <button type="button" @click="showEdit = false" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 font-bold hover:bg-slate-200 transition-colors">✕</button>
            </div>

            <form :action="editForm.actionUrl" method="POST" class="mt-5 space-y-4 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Mata Pelajaran *</label>
                    <input type="text" name="nama_mapel" x-model="editForm.nama_mapel" required class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200 focus:border-gymove-blue focus:bg-white focus:outline-none transition-all" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jenjang Pendidikan *</label>
                        <select name="id_jenjang" x-model="editForm.id_jenjang" required class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200 focus:border-gymove-blue focus:bg-white focus:outline-none transition-all">
                            <option value="JJ001">SD / MI (Sekolah Dasar)</option>
                            <option value="JJ002">SMP / MTs (Sekolah Menengah Pertama)</option>
                            <option value="JJ003">SMA / SMK / MA (Sekolah Menengah Atas)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Mapel *</label>
                        <input type="text" name="kode_mapel" x-model="editForm.kode_mapel" required class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200 focus:border-gymove-blue focus:bg-white focus:outline-none transition-all uppercase font-mono" />
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi / Capaian Pembelajaran Lapangan *</label>
                    <textarea name="deskripsi" x-model="editForm.deskripsi" rows="3" required class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200 focus:border-gymove-blue focus:bg-white focus:outline-none transition-all"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Warna Teks Badge</label>
                        <input type="text" name="warna_teks" x-model="editForm.warna_teks" class="w-full bg-gymove-pill px-3 py-2 rounded-xl border border-slate-200 font-mono text-[11px]" />
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Warna Background Badge</label>
                        <input type="text" name="warna_bg_soft" x-model="editForm.warna_bg_soft" class="w-full bg-gymove-pill px-3 py-2 rounded-xl border border-slate-200 font-mono text-[11px]" />
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="showEdit = false" class="px-5 py-2.5 rounded-full text-slate-500 font-bold hover:bg-slate-100 transition-colors">Batal</button>
                    <button type="submit" class="bg-gymove-blue hover:bg-gymove-blue-hover text-white px-6 py-2.5 rounded-full font-bold shadow-blue-glow transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function subjectCrud() {
        return {
            activeTab: 'ALL',
            searchQuery: '',
            showCreate: false,
            showEdit: false,
            editForm: {
                id: null,
                nama_mapel: '',
                kode_mapel: '',
                id_jenjang: 'JJ003',
                warna_teks: '#132985',
                warna_bg_soft: '#EEF2FF',
                deskripsi: '',
                actionUrl: ''
            },
            openCreateModal() {
                this.showCreate = true;
            },
            openEditModal(sub) {
                this.editForm = {
                    id: sub.id,
                    nama_mapel: sub.nama_mapel,
                    kode_mapel: sub.kode_mapel,
                    id_jenjang: sub.id_jenjang,
                    warna_teks: sub.warna_teks || '#132985',
                    warna_bg_soft: sub.warna_bg_soft || '#EEF2FF',
                    deskripsi: sub.deskripsi || '',
                    actionUrl: '{{ url('/admin/subjects') }}/' + sub.id
                };
                this.showEdit = true;
            },
            matches(name, code, jenjang) {
                const matchesTab = this.activeTab === 'ALL' || jenjang === this.activeTab;
                if (!matchesTab) return false;
                if (!this.searchQuery.trim()) return true;
                const q = this.searchQuery.toLowerCase();
                return name.toLowerCase().includes(q) || code.toLowerCase().includes(q);
            }
        };
    }
</script>
@endsection
