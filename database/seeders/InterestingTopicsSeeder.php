<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterestingTopicsSeeder extends Seeder
{
    /**
     * Run the database seeds to give every location-subject connection a unique, engaging topic.
     */
    public function run(): void
    {
        $locations = Location::all();
        $subjects = Subject::all()->keyBy('id');

        $updatedCount = 0;

        foreach ($locations as $loc) {
            $locName = $loc->nama_lokasi;
            $locLower = strtolower($locName . ' ' . ($loc->deskripsi ?? ''));
            $locId = $loc->id_tempat ?? '';

            // Get all subjects linked to this location
            $pivots = DB::table('location_subject')
                ->where('location_id', $loc->id)
                ->get();

            foreach ($pivots as $pivot) {
                $sub = $subjects[$pivot->subject_id] ?? null;
                if (!$sub) continue;

                $topic = $this->generateUniqueTopic($locName, $locLower, $locId, $sub);

                // Add slight variety to relevance score if needed
                $newScore = rand(83, 96);

                DB::table('location_subject')
                    ->where('id', $pivot->id)
                    ->update([
                        'topik_kajian' => $topic,
                        'skor_relevansi_c1' => $newScore,
                        'updated_at' => now(),
                    ]);

                $updatedCount++;
            }
        }

        $this->command->info("Berhasil memperbarui {$updatedCount} topik lapangan yang menarik dan bervariasi.");
    }

    /**
     * Generate engaging and specific field study topic based on location & subject.
     */
    protected function generateUniqueTopic(string $locName, string $locLower, string $locId, Subject $sub): string
    {
        $subName = $sub->nama_mapel;
        $jenjang = $sub->id_jenjang; // JJ001 (SD), JJ002 (SMP), JJ003 (SMA)

        // 1. SPECIFIC LANDMARK RULES FIRST

        // --- Benteng Marlborough ---
        if (str_contains($locLower, 'benteng marlborough') || $locId === 'TP001') {
            return match ($subName) {
                'Sejarah' => 'Arsitektur Pertahanan Benteng Kolonial EIC & Jalur Perdagangan Lada Abad ke-18',
                'Ilmu Pengetahuan Sosial' => 'Strategi Geopolitik Maritim Fort Marlborough & Perjanjian Traktat London 1824',
                'Pendidikan Pancasila' => 'Refleksi Perjuangan Kedaulatan Bangsa Melawan Hegemoni Kolonialisme Inggris di Bengkulu',
                'Seni Budaya' => 'Dokumentasi Visual Arsitektur Bentang Eropa, Meriam Kuno, & Sketsa Siluet Dinding Bata Benteng',
                'Bahasa Inggris' => 'Bilingual Heritage Tour Guiding Practice & Historical Narrative Writing on Fort Marlborough',
                'Bahasa Indonesia' => 'Penyusunan Teks Laporan Hasil Observasi (LHO) Struktur Cagar Budaya Peninggalan Inggris',
                'IPAS' => 'Mengamati Batuan Penyusun Benteng Kura-Kura & Pengaruh Udara Pesisir Terhadap Pelapukan Bangunan',
                'Matematika' => 'Aplikasi Geometri Sudut Elevasi Tembakan Meriam & Pengukuran Skala Denah Ruang Bawah Tanah',
                'Sosiologi' => 'Interaksi Komunitas Pengunjung dengan Peninggalan Sejarah di Ruang Wisata Publik Pesisir',
                default => "Kajian Kontekstual {$subName} di Kawasan Cagar Budaya Benteng Marlborough",
            };
        }

        // --- Rumah Pengasingan Bung Karno ---
        if (str_contains($locLower, 'bung karno') || $locId === 'TP003') {
            return match ($subName) {
                'Sejarah' => 'Kajian Periode Pengasingan Ir. Soekarno (1938-1942) & Ide-Ide Awal Kemerdekaan Bangsa',
                'Pendidikan Pancasila' => 'Penelusuran Jejak Keteladanan, Nasionalisme, & Toleransi Bung Karno di Tanah Bengkulu',
                'Bahasa Indonesia' => 'Analisis Koleksi Surat Korespondensi & Naskah Drama Sandiwara Karya Soekarno di Pengasingan',
                'Ilmu Pengetahuan Sosial' => 'Interaksi Sosial Budaya Bung Karno Bersama Tokoh-Tokoh Masyarakat Bengkulu',
                'Seni Budaya' => 'Apresiasi Desain Arsitektur Rumah Kolonial Indis & Koleksi Seni Era Pra-Kemerdekaan',
                'Bahasa Inggris' => 'Reading Historical Plaques & English Descriptive Presentation on Soekarno’s Exile Residence',
                'Sosiologi' => 'Pengaruh Ketokohan Soekarno dalam Membentuk Kesadaran Kolektif Masyarakat Lokal Bengkulu',
                default => "Eksplorasi Pembelajaran {$subName} Meneladani Pemikiran Bung Karno di Bengkulu",
            };
        }

        // --- Rumah Ibu Fatmawati ---
        if (str_contains($locLower, 'fatmawati') || $locId === 'TP004') {
            return match ($subName) {
                'Sejarah' => 'Kajian Patriotisme Ibu Fatmawati: Menjahit Sang Saka Merah Putih Menjelang Proklamasi',
                'Pendidikan Pancasila' => 'Implementasi Nilai Pengorbanan Tokoh Pahlawan Nasional Perempuan bagi Kemerdekaan RI',
                'Seni Budaya' => 'Kajian Arsitektur Rumah Tradisional Panggung Kayu Ulin Bengkulu & Seni Tenun Kuno',
                'Bahasa Indonesia' => 'Menulis Biografi Naratif Perjalanan Hidup Ibu Negara Pertama Republik Indonesia',
                'Ilmu Pengetahuan Sosial' => 'Peran Kaum Perempuan Bengkulu dalam Menunjang Perjuangan Fisik dan Diplomasi Bangsa',
                'IPAS' => 'Mengenal Bahan Alami Kayu Tahan Rayap pada Konstruksi Rumah Panggung Tradisional Bengkulu',
                default => "Studi Karakter {$subName} Melalui Sosok Pahlawan Nasional Ibu Fatmawati",
            };
        }

        // --- Wisata Hutan Mangrove Pulau Baai ---
        if (str_contains($locLower, 'mangrove') || str_contains($locLower, 'pulau baai') || $locId === 'TP002') {
            return match ($subName) {
                'Biologi' => 'Zonasi Vegetasi Tumbuhan Bakau (Rhizophora & Avicennia) Serta Rantai Makanan Estuari',
                'Ilmu Pengetahuan Alam' => 'Observasi Keanekaragaman Moluska, Krustasea, dan Peran Mangrove Sebagai Biofilter Alami',
                'IPAS' => 'Mengenal Bentuk Khusus Akar Tunjang Pohon Bakau dan Manfaatnya Menahan Hantaman Gelombang Ombak',
                'Geografi' => 'Sedimentasi Muara Alur Pelayaran Pulau Baai & Tata Kelola Konservasi Pesisir Berkelanjutan',
                'Pendidikan Jasmani Olahraga dan Kesehatan' => 'Aktivitas Jalan Sehat Menjelajah Titian Boardwalk Kayu & Terapi Udara Bersih Mangrove',
                'Fisika' => 'Analisis Atenuasi Energi Gelombang Laut Saat Menerobos Rimbun Perakaran Pohon Bakau',
                'Bahasa Indonesia' => 'Penyusunan Teks Deskripsi Estetika Hutan Payau & Kampanye Menjaga Lingkungan Pesisir',
                'Seni Budaya' => 'Dokumentasi Fotografi Lanskap Hutan Bakau & Sketsa Siluet Tumbuhan Air Alami',
                default => "Observasi Lapangan {$subName} di Ekosistem Hutan Mangrove Pulau Baai",
            };
        }

        // --- Museum Bengkulu ---
        if (str_contains($locLower, 'museum') || $locId === 'TP005') {
            return match ($subName) {
                'IPAS' => 'Melihat Fosil Purba, Pakaian Adat Pengantin 9 Suku Bengkulu, & Alat Tangkap Nelayan Tradisional',
                'Sejarah' => 'Studi Filologi Manuskrip Kuno Beraksara KaGaNga pada Media Kulit Kayu dan Bilah Bambu',
                'Seni Budaya' => 'Apresiasi Ragam Seni Rupa Etnografis: Kain Motif Besurek, Senjata Keris, & Seni Kriya Logam',
                'Sosiologi' => 'Evolusi Kebudayaan Suku Rejang, Serawai, dan Lembak dalam Menghadapi Arus Modernisasi',
                'Ilmu Pengetahuan Sosial' => 'Mempelajari Sistem Kehidupan Sosial & Mata Pencaharian Tradisional Masyarakat Bengkulu',
                'Bahasa Inggris' => 'Vocabulary Enrichment on Museology, Artifact Classification, & Cultural Heritage',
                'Bahasa Indonesia' => 'Praktik Menulis Resensi Koleksi Sejarah Berharga Museum Negeri Provinsi Bengkulu',
                default => "Eksplorasi Koleksi Etnografi {$subName} di Museum Negeri Bengkulu",
            };
        }

        // --- Pantai Panjang (Sport Center / TWA / Pasir Putih) ---
        if (str_contains($locLower, 'pantai panjang') || $locId === 'TP007' || $locId === 'TP008') {
            return match ($subName) {
                'Pendidikan Jasmani Olahraga dan Kesehatan' => 'Latihan Daya Tahan Jantung-Paru di Jogging Track Pantai & Kebugaran Pasir Laut',
                'Geografi' => 'Studi Morfologi Garis Pantai Pasir Putih 7 Km & Pengaruh Pasang Surut Samudera Hindia',
                'Fisika' => 'Penerapan Konsep Gelombang Air Laut, Efek Doppler Angin Pesisir, & Gaya Gesek Pasir',
                'Biologi' => 'Ekologi Formasi Vegetasi Pes-Caprae & Adaptasi Tumbuhan Cemara Laut (Casuarina equisetifolia)',
                'Ekonomi' => 'Analisis Rantai Pasok Usaha Kuliner Pesisir & Pengaruh Kunjungan Wisata Terhadap UMKM',
                'IPAS' => 'Mengenal Fenomena Pasang Surut Air Laut, Jenis Pasir Kuarsa, & Ekosistem Pesisir Terbuka',
                'Sosiologi' => 'Studi Perilaku Rekreasi Warga Kota Bengkulu & Pengelolaan Ruang Terbuka Hijau Wisata',
                default => "Observasi Dinamika Alam & Aktivitas {$subName} di Pantai Panjang",
            };
        }

        // --- Kebun Melon TOBOPONIK / Agrowisata Buah ---
        if (str_contains($locLower, 'melon') || str_contains($locLower, 'toboponik') || str_contains($locLower, 'hidroponik')) {
            return match ($subName) {
                'Biologi' => 'Teknik Fertigasi Hidroponik Tetes, Nutrisi AB Mix, & Perkembangan Pembungaan Buah Melon',
                'Ilmu Pengetahuan Alam' => 'Mekanisme Transpor Air & Unsur Hara pada Tanaman Modern Tanpa Media Tanah',
                'IPAS' => 'Belajar Cara Menanam Buah Melon Modern, Merawat Tunas, & Mengenal Manfaat Buah Segar',
                'Ekonomi' => 'Studi Kelayakan Agribisnis Komoditas Melon Premium & Pemasaran Hasil Panen Modern',
                'Pendidikan Jasmani Olahraga dan Kesehatan' => 'Aktivitas Fisik Berkebun Edukatif & Pembiasaan Gizi Sehat Alami bagi Tubuh',
                'Matematika' => 'Pengukuran Volume Larutan Nutrisi Tanaman & Perhitungan Rasio Laju Pertumbuhan Daun',
                default => "Praktik Budidaya Modern {$subName} di Kebun Melon Hidroponik",
            };
        }

        // --- Dinas Perpustakaan Dan Kearsipan Kota Bengkulu ---
        if (str_contains($locLower, 'perpustakaan') || str_contains($locLower, 'pustaka') || str_contains($locLower, 'arsip')) {
            return match ($subName) {
                'Bahasa Indonesia' => 'Teknik Riset Literasi Pustaka, Resensi Buku Pengetahuan, & Bedah Cerita Rakyat Bengkulu',
                'Informatika' => 'Pemanfaatan OPAC (Online Public Access Catalog), E-Library, & Manajemen Digitalisasi Arsip',
                'IPAS' => 'Mengeksplorasi Ensiklopedia Flora-Fauna Sumatera & Menumbuhkan Budaya Gemar Membaca',
                'Ilmu Pengetahuan Sosial' => 'Penelusuran Dokumen Arsip Sejarah Kota Bengkulu dari Masa Pra-Kolonial Hingga Otonomi',
                'Pendidikan Pancasila' => 'Pengamalan Literasi Kewarganegaraan: Menghargai Hak Intelektual Karya Cipta Buku',
                default => "Pendalaman Sumber Referensi {$subName} di Perpustakaan dan Arsip Daerah",
            };
        }

        // --- Sentra Batik Besurek / Kerajinan Khas Bengkulu ---
        if (str_contains($locLower, 'batik') || str_contains($locLower, 'besurek') || str_contains($locLower, 'kriya')) {
            return match ($subName) {
                'Seni Budaya' => 'Praktik Seni Kriya Batik: Menggambar Motif Kaligrafi Arab Pesisir Berpadu Bunga Rafflesia',
                'Ekonomi' => 'Manajemen Usaha Mikro Batik Besurek, Pemasaran Digital, & Pelestarian Kerajinan Daerah',
                'Ilmu Pengetahuan Sosial' => 'Akulturasi Budaya Melayu Pesisir dan Timur Tengah dalam Ragam Hias Kain Besurek',
                'Matematika' => 'Perhitungan Biaya Pokok Produksi Selembar Kain & Pola Simetri Geometri Motif Tradisional',
                'Bahasa Indonesia' => 'Menulis Teks Prosedur Pembuatan Batik Canting Tradisional Khas Bengkulu',
                default => "Eksplorasi Seni dan Nilai Ekonomi {$subName} di Sentra Pengrajin Batik Besurek",
            };
        }

        // --- Taman Budaya Provinsi Bengkulu ---
        if (str_contains($locLower, 'taman budaya') || str_contains($locLower, 'seni budaya')) {
            return match ($subName) {
                'Seni Budaya' => 'Apresiasi Pertunjukan Tari Tradisional Kejei, Irama Musik Tabuh Dol, & Teater Daerah',
                'Sosiologi' => 'Pelestarian Seni Tradisi di Kalangan Remaja Pelajar di Tengah Dinamika Modernitas',
                'Bahasa Indonesia' => 'Penulisan Naskah Sandiwara Pendek Berlatar Cerita Rakyat Bengkulu & Olah Vokal',
                'Pendidikan Pancasila' => 'Merawat Keberagaman Ekspresi Seni Etnis Nusantara di Panggung Budaya Daerah',
                default => "Apresiasi Karya Kreatif {$subName} di Taman Budaya Provinsi Bengkulu",
            };
        }

        // --- Tempat Bersejarah Kolonial Lainnya (Makam Inggris, Tugu Parr, Tugu Hamilton) ---
        if (str_contains($locLower, 'makam inggris') || str_contains($locLower, 'tugu thomas parr') || str_contains($locLower, 'hamilton') || str_contains($locLower, 'monumen')) {
            return match ($subName) {
                'Sejarah' => "Kajian Konteks Sejarah Monumen Peringatan Kolonial Inggris Era Residen Abad ke-19",
                'Pendidikan Pancasila' => "Menelaah Dampak Perlawanan Rakyat Pribumi Menentang Kebijakan Tanam Paksa Kolonial",
                'Bahasa Inggris' => "Translating Inscriptions on Colonial Epitaphs and Tombstones into Modern Context",
                'Ilmu Pengetahuan Sosial' => "Pengaruh Keberadaan Pemukiman Kolonial Terhadap Struktur Demografi Pesisir Bengkulu",
                'Seni Budaya' => "Analisis Bentuk Ornamen Monumen Peringatan Klasik dan Konservasi Batu Cagar Budaya",
                default => "Studi Jejak Sejarah {$subName} di Monumen Bersejarah Pesisir",
            };
        }

        // --- Tempat Ibadah Bersejarah (Masjid Jamik, Masjid Raya Baitul Izzah, dsb.) ---
        if (str_contains($locLower, 'masjid') || str_contains($locLower, 'baitul izzah')) {
            return match ($subName) {
                'Pendidikan Agama' => 'Kajian Sejarah Masuknya Islam di Bengkulu & Nilai Spiritual Tempat Ibadah Ramah Umat',
                'Seni Budaya' => 'Harmoni Arsitektur Jawa-Minangkabau pada Atap Segitiga Bertingkat Rancangan Bung Karno',
                'Pendidikan Pancasila' => 'Penanaman Sikap Moderasi Beragama, Toleransi Kebangsaan, & Kerukunan Warga Kota',
                'Bahasa Indonesia' => 'Teknik Berpidato Dakwah Persuasif & Menulis Esai Reflektif Nilai Kebaikan Sosial',
                default => "Kajian Nilai Religiusitas & Arsitektur {$subName} di Masjid Bersejarah",
            };
        }

        // --- Danau Dendam Tak Sudah / Ekosistem Perairan Darat ---
        if (str_contains($locLower, 'danau dendam') || str_contains($locLower, 'danau')) {
            return match ($subName) {
                'Biologi' => 'Keanekaragaman Hayati Anggrek Air (Vanda hookeriana) & Rantai Makanan Perairan Danau',
                'Ilmu Pengetahuan Alam' => 'Kualitas Parameter Fisika-Kimia Air Danau Alami Serta Adaptasi Tumbuhan Teratai',
                'Geografi' => 'Morfogenesis Danau Alami Tekto-Vulkanik & Pengendalian Erosi Lahan di Sekitar Catchment Area',
                'IPAS' => 'Mengamati Ekosistem Rawa Air Tawar, Ikan Endemik Danau, & Menjaga Sumber Air Bersih',
                'Seni Budaya' => 'Lukisan Pemandangan Alam Terbuka (Plein Air) dan Dokumentasi Fotografi Danau Alami',
                'Bahasa Indonesia' => 'Menggali Asal-Usul Cerita Rakyat Legenda Danau Dendam Tak Sudah Berbasis Kearifan Lokal',
                default => "Studi Ekologis Terpadu {$subName} di Kawasan Danau Dendam Tak Sudah",
            };
        }

        // --- Kafe Edukasi, Kedai Literasi, & Coworking Space ---
        if (str_contains($locLower, 'kopi') || str_contains($locLower, 'coffee') || str_contains($locLower, 'space') || str_contains($locLower, 'kafe') || str_contains($locLower, 'cafe') || str_contains($locLower, 'tjahaja')) {
            return match ($subName) {
                'Ekonomi' => "Kajian Rantai Pasok Kopi Robusta Bengkulu, Penetapan Harga Menu, & Analisis Usaha Kedai Kreatif",
                'Sosiologi' => "Pola Interaksi Sosial Remaja Pelajar & Perubahan Gaya Hidup Komunitas Belajar di Ruang Publik",
                'Informatika' => "Pemanfaatan Jaringan Internet Publik Secara Aman, Cloud Storage, & Alat Kolaborasi Daring",
                'Bahasa Indonesia' => "Sesi Diskusi Kelompok Literasi Kritis, Menulis Esai Opini, & Mengasah Percaya Diri Berbicara",
                'Matematika' => "Simulasi Manajemen Kas Harian Usaha Mandiri, Perhitungan Diskon Promo, & Break Even Point (BEP)",
                'Ilmu Pengetahuan Sosial' => "Peran Industri Kreatif Food and Beverage dalam Pertumbuhan Ekonomi Daerah Kota Bengkulu",
                default => "Pemanfaatan Ruang Kreatif {$locName} untuk Pendalaman Materi {$subName}",
            };
        }

        // --- Pantai Wisata / Rekreasi Terbuka (Pantai Tapak Paderi, Berkas, Jakat, dll.) ---
        if (str_contains($locLower, 'pantai') || str_contains($locLower, 'tapak paderi') || str_contains($locLower, 'jakat') || str_contains($locLower, 'berkas') || str_contains($locLower, 'zaki')) {
            return match ($subName) {
                'Geografi' => "Analisis Proses Abrasi dan Sedimentasi Pasir Pantai Pesisir Serta Bangunan Pemecah Ombak",
                'Fisika' => "Karakteristik Kecepatan Angin Laut, Pembiasan Cahaya Sore Hari, & Energi Kinetik Ombak Pesisir",
                'Ilmu Pengetahuan Alam' => "Keanekaragaman Biota Laut Dangkal, Hewan Pasir Pantai, & Keseimbangan Alam Pesisir",
                'IPAS' => "Melihat Aktivitas Perahu Nelayan Tradisional, Penjualan Ikan Segar, & Pemanfaatan Sumber Daya Laut",
                'Pendidikan Jasmani Olahraga dan Kesehatan' => "Aktivitas Senam Aerobik Luar Ruang di Bawah Angin Segar Pantai & Manfaat Udara Kaya Oksigen",
                'Sosiologi' => "Interaksi Sosial Budaya Antara Nelayan Pesisir dengan Wisatawan dan Pedagang Musiman",
                'Seni Budaya' => "Melukis Suasana Senja Pesisir Pantai Bengkulu & Fotografi Dokumenter Aktivitas Warga",
                default => "Observasi Lingkungan {$subName} di Kawasan Pesisir {$locName}",
            };
        }

        // 2. GENERAL SUBJECT PATTERNS FOR ANY OTHER VENUES (Intelligent Fallback)
        return match ($subName) {
            'Sejarah' => "Penelusuran Jejak Historis & Perkembangan Peradaban Lokal di Kawasan {$locName}",
            'Biologi' => "Identifikasi Karakteristik Keanekaragaman Hayati & Interaksi Ekologis di {$locName}",
            'Geografi' => "Analisis Karakteristik Keruangan, Pemanfaatan Lahan, & Faktor Lingkungan {$locName}",
            'Fisika' => "Kajian Konsep Mekanika, Termodinamika Lingkungan, & Fenomena Fisika Nyata di {$locName}",
            'Ekonomi' => "Analisis Aktivitas Transaksi, Efisiensi Biaya Operasional, & Nilai Manfaat di {$locName}",
            'Sosiologi' => "Kajian Dinamika Sosial Warga, Pola Komunikasi, & Kebiasaan Masyarakat di Sekitar {$locName}",
            'Pendidikan Pancasila' => "Penerapan Gotong Royong, Menjaga Fasilitas Publik, & Nilai Kebangsaan di {$locName}",
            'Pendidikan Agama' => "Refleksi Nilai Religiusitas, Spiritualitas Alam, & Budi Pekerti Mulia di {$locName}",
            'Seni Budaya' => "Eksplorasi Nilai Estetika, Tata Ruang Visual, & Apresiasi Ornamen Khas di {$locName}",
            'Bahasa Indonesia' => "Praktik Menulis Deskriptif & Penyusunan Laporan Reflektif Lapangan di {$locName}",
            'Bahasa Inggris' => "Communicative English Practice, Vocabulary Building, & Observational Tour at {$locName}",
            'Informatika' => "Pemanfaatan Teknologi Informasi, Dokumentasi Digital, & Analisis Data Lapangan di {$locName}",
            'Matematika' => "Pengukuran Geometris Luas Area & Perhitungan Aritmatika Terapan di {$locName}",
            'Pendidikan Jasmani Olahraga dan Kesehatan' => "Aktivitas Fisik Kebugaran Luar Ruang & Pembiasaan Gaya Hidup Aktif di {$locName}",
            'Ilmu Pengetahuan Alam' => "Eksplorasi Fenomena Alam Biotik-Abiotik & Keseimbangan Ekosistem di {$locName}",
            'Ilmu Pengetahuan Sosial' => "Studi Interaksi Manusia dengan Lingkungan Sekitar & Kegiatan Sosial di {$locName}",
            'IPAS' => "Eksplorasi Sains Sederhana & Mengenal Lingkungan Sosial Budaya di {$locName}",
            default => "Kajian Observasi Lapangan Edukatif {$subName} di {$locName}",
        };
    }
}
