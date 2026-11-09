<!-- Modal Panduan Sistem SPK PesisirEdu -->
<div x-show="showGuideModal" 
     x-cloak 
     class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    <div class="bg-white rounded-card w-full max-w-2xl p-7 shadow-2xl border border-slate-100 relative max-h-[90vh] overflow-y-auto"
         @click.away="showGuideModal = false">
        
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-gymove-soft-blue text-gymove-blue flex items-center justify-center font-bold text-lg">
                    ?
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Panduan Logika Sistem & Revisi Dosen</h3>
                    <p class="text-xs text-slate-500 font-medium">Metode Simple Additive Weighting & Proximity Clustering (Haversine)</p>
                </div>
            </div>
            
            <button type="button" 
                    @click="showGuideModal = false"
                    class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-sm transition-colors">
                ✕
            </button>
        </div>

        <div class="mt-5 space-y-4 text-xs text-slate-600 leading-relaxed">
            <div class="p-4 rounded-2xl bg-indigo-50/70 border border-indigo-100">
                <h4 class="font-extrabold text-gymove-blue text-sm mb-1">📍 Skenario 1: Lokasi Tunggal Terpadu (Single Match)</h4>
                <p>
                    Terjadi secara otomatis ketika seluruh mata pelajaran yang dipilih oleh pengguna <strong>tersedia di dalam 1 lokasi yang sama</strong> (misalnya kombinasi <em>Geografi + Ekonomi</em> yang keduanya ada di Pantai Panjang). Sistem akan menghitung skor SAW komprehensif dan meranking lokasi-lokasi terpadu tersebut.
                </p>
            </div>

            <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80">
                <h4 class="font-extrabold text-amber-900 text-sm mb-1">⚡ Skenario 2: Paket Lokasi Berdekatan (Spatial Proximity)</h4>
                <p>
                    Terjadi secara otomatis ketika <strong>tidak ada satu pun lokasi tunggal yang memiliki seluruh kombinasi mata pelajaran</strong> yang dipilih (misalnya memilih <em>Biologi + Sejarah</em>). Sistem akan:
                </p>
                <ol class="list-decimal ml-4 mt-2 space-y-1">
                    <li>Mencari kandidat lokasi terbaik untuk masing-masing mata pelajaran.</li>
                    <li>Menghitung jarak spasial antar-lokasi menggunakan <strong>Haversine Formula</strong>.</li>
                    <li>Memasangkan 2 tempat yang <strong>paling berdekatan secara geografis</strong> (misal Benteng Marlborough dan Tapak Paderi/Jakat berjarak hanya ratusan meter) serta memiliki rata-rata skor SAW tertinggi.</li>
                    <li>Menampilkan rute sambungan oranye pada Peta WebGIS.</li>
                </ol>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/60">
                <h4 class="font-extrabold text-slate-800 text-sm mb-1.5">⚖️ Kriteria SPK Metode SAW:</h4>
                <ul class="space-y-1.5">
                    <li><strong>C1 (Relevansi Mapel - Benefit):</strong> Tingkat kesesuaian kurikulum dan topik kajian di lokasi tersebut.</li>
                    <li><strong>C2 (Jarak Tempuh - Cost):</strong> Jarak dinamis dari titik sekolah asal (makin dekat nilai makin tinggi).</li>
                    <li><strong>C3 (Kelengkapan Fasilitas - Benefit):</strong> Ketersediaan akses jalan, toilet, gazebo, dan area berkumpul.</li>
                    <li><strong>C4 (Keamanan Lingkungan Belajar - Benefit):</strong> Indikator keselamatan dan keamanan fisik area pesisir bagi aktivitas kegiatan belajar luar ruang siswa.</li>
                </ul>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end">
            <button type="button" 
                    @click="showGuideModal = false"
                    class="bg-gymove-blue text-white font-bold px-6 py-2.5 rounded-full text-xs shadow-sm hover:bg-gymove-blue-hover transition-colors">
                Mengerti
            </button>
        </div>
    </div>
</div>
