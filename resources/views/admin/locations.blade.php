@extends('admin.layout')

@section('title', 'Kelola Lokasi Edukatif (CRUD) | Admin PESISIR-EDU')
@section('page_title', 'Kelola Data Lokasi Pembelajaran Pesisir')
@section('page_subtitle', 'Pengelolaan atribut spasial, fasilitas (C3), keamanan (C4), dan pemetaan kurikulum')

@section('content')
<div x-data="locationCrud()" class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-card shadow-soft-card border border-slate-100">
        <div>
            <h3 class="text-base font-extrabold text-slate-900">Basis Data Lokasi Edukatif Pesisir</h3>
            <p class="text-xs text-slate-400 mt-0.5">Seluruh lokasi terverifikasi digunakan dalam perhitungan SPK metode SAW.</p>
        </div>
        <button type="button" 
                @click="showCreate = true" 
                class="px-5 py-2.5 rounded-full bg-gymove-blue hover:bg-gymove-blue-hover text-white text-xs font-bold transition-all shadow-blue-glow flex items-center gap-2 shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Lokasi Baru</span>
        </button>
    </div>

    <!-- Location Table -->
    <div class="bg-white rounded-card shadow-soft-card border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-3.5">#</th>
                        <th class="p-3.5">Nama Lokasi & Alamat</th>
                        <th class="p-3.5 text-center">Koordinat (Lat, Lng)</th>
                        <th class="p-3.5 text-center">Fasilitas (C3)</th>
                        <th class="p-3.5 text-center">Keamanan (C4)</th>
                        <th class="p-3.5">Mata Pelajaran Relevan</th>
                        <th class="p-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($locations as $index => $loc)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-3.5 font-bold text-slate-400">{{ $index + 1 }}</td>
                            <td class="p-3.5">
                                <div class="font-extrabold text-slate-900 text-sm">{{ $loc->nama_lokasi }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">{{ Str::limit($loc->alamat_kawasan, 45) }}</div>
                            </td>
                            <td class="p-3.5 text-center font-mono text-[11px] text-slate-500 font-semibold">
                                {{ $loc->latitude }}, {{ $loc->longitude }}
                            </td>
                            <td class="p-3.5 text-center font-black font-mono text-gymove-blue text-sm">
                                {{ $loc->skor_fasilitas_c3 }}/100
                            </td>
                            <td class="p-3.5 text-center font-black font-mono text-emerald-600 text-sm">
                                {{ $loc->skor_keamanan_c4 }}/100
                            </td>
                            <td class="p-3.5">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    @foreach($loc->subjects as $sub)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold"
                                              style="background-color: {{ $sub->warna_bg_soft }}; color: {{ $sub->warna_teks }};">
                                            {{ $sub->kode_mapel }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="p-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" 
                                            @click="openEditModal({{ json_encode($loc) }})"
                                            class="px-3 py-1.5 rounded-xl bg-gymove-soft-blue text-gymove-blue hover:bg-gymove-blue hover:text-white text-xs font-bold transition-colors">
                                        Edit
                                    </button>
                                    
                                    <form action="{{ route('admin.locations.destroy', $loc->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi {{ $loc->nama_lokasi }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white text-xs font-bold transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- 1. MODAL TAMBAH LOKASI (CREATE) -->
    <div x-show="showCreate" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-card w-full max-w-2xl p-7 shadow-2xl border border-slate-100 relative max-h-[90vh] overflow-y-auto" @click.away="showCreate = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="text-lg font-extrabold text-slate-900">Tambah Lokasi Edukatif Baru</h3>
                <button type="button" @click="showCreate = false" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 font-bold">✕</button>
            </div>

            <form action="{{ route('admin.locations.store') }}" method="POST" class="mt-5 space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Lokasi Edukatif *</label>
                    <input type="text" name="nama_lokasi" required class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200" placeholder="Contoh: Pantai Berkas, Teluk Sepang..." />
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Alamat Kawasan *</label>
                    <input type="text" name="alamat_kawasan" required class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200" placeholder="Jl. Pariwisata, Kelurahan..." />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Latitude (Garis Lintang) *</label>
                        <input type="number" step="0.0000001" name="latitude" required class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200 font-mono" placeholder="-3.8150" />
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Longitude (Garis Bujur) *</label>
                        <input type="number" step="0.0000001" name="longitude" required class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200 font-mono" placeholder="102.2650" />
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Edukatif Lapangan *</label>
                    <textarea name="deskripsi" rows="3" required class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200" placeholder="Potensi studi kurikulum, karakteristik geomorfologi, ekosistem, sejarah..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Skor Fasilitas C3 (1-100) *</label>
                        <input type="number" min="1" max="100" name="skor_fasilitas_c3" value="80" required class="w-full bg-gymove-pill px-3 py-2.5 rounded-xl border border-slate-200" />
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Kelayakan sarana (aula, toilet, akses jalan)</span>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Skor Keamanan C4 (1-100) *</label>
                        <input type="number" min="1" max="100" name="skor_keamanan_c4" value="85" required class="w-full bg-gymove-pill px-3 py-2.5 rounded-xl border border-slate-200" />
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Keamanan siswa (pos pantau, pagar, jalur darurat)</span>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-2">Relasi Mata Pelajaran Terkait:</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach($allSubjects as $sub)
                            <label class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 border border-slate-200/60 cursor-pointer hover:bg-slate-100">
                                <input type="checkbox" name="subject_ids[]" value="{{ $sub->id }}" class="rounded text-gymove-blue" />
                                <span class="text-xs font-semibold">{{ $sub->kode_mapel }} - {{ $sub->nama_mapel }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="showCreate = false" class="px-5 py-2.5 rounded-full text-slate-500 font-bold">Batal</button>
                    <button type="submit" class="bg-gymove-blue text-white px-6 py-2.5 rounded-full font-bold shadow-blue-glow">Simpan Lokasi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. MODAL EDIT LOKASI (UPDATE) -->
    <div x-show="showEdit" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-card w-full max-w-2xl p-7 shadow-2xl border border-slate-100 relative max-h-[90vh] overflow-y-auto" @click.away="showEdit = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="text-lg font-extrabold text-slate-900">Edit Data Lokasi Edukatif</h3>
                <button type="button" @click="showEdit = false" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 font-bold">✕</button>
            </div>

            <form :action="'/admin/locations/' + editLoc.id" method="POST" class="mt-5 space-y-4 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Lokasi Edukatif *</label>
                    <input type="text" name="nama_lokasi" x-model="editLoc.nama_lokasi" required class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200" />
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Alamat Kawasan *</label>
                    <input type="text" name="alamat_kawasan" x-model="editLoc.alamat_kawasan" required class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200" />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Latitude (Garis Lintang) *</label>
                        <input type="number" step="0.0000001" name="latitude" x-model.number="editLoc.latitude" required class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200 font-mono" />
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Longitude (Garis Bujur) *</label>
                        <input type="number" step="0.0000001" name="longitude" x-model.number="editLoc.longitude" required class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200 font-mono" />
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi Edukatif Lapangan *</label>
                    <textarea name="deskripsi" x-model="editLoc.deskripsi" rows="3" required class="w-full bg-gymove-pill px-4 py-2.5 rounded-xl border border-slate-200"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Skor Fasilitas C3 (1-100) *</label>
                        <input type="number" min="1" max="100" name="skor_fasilitas_c3" x-model.number="editLoc.skor_fasilitas_c3" required class="w-full bg-gymove-pill px-3 py-2.5 rounded-xl border border-slate-200" />
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Skor Keamanan C4 (1-100) *</label>
                        <input type="number" min="1" max="100" name="skor_keamanan_c4" x-model.number="editLoc.skor_keamanan_c4" required class="w-full bg-gymove-pill px-3 py-2.5 rounded-xl border border-slate-200" />
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" @click="showEdit = false" class="px-5 py-2.5 rounded-full text-slate-500 font-bold">Batal</button>
                    <button type="submit" class="bg-gymove-blue text-white px-6 py-2.5 rounded-full font-bold shadow-blue-glow">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function locationCrud() {
        return {
            showCreate: false,
            showEdit: false,
            editLoc: {},
            openEditModal(loc) {
                this.editLoc = { ...loc };
                this.showEdit = true;
            }
        };
    }
</script>
@endsection
