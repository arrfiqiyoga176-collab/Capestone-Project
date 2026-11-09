<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolAndSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Subject::truncate();
        DB::table('location_subject')->truncate();
        School::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // =========================================================================
        // 1. SEED 29 SUBJECTS (SD / MI, SMP / MTs, SMA / SMK / MA)
        // =========================================================================
        $subjectsData = [
            // --- JENJANG SD / MI (JJ001) ---
            ['id_mapel' => 'MP001', 'nama_mapel' => 'Pendidikan Agama', 'id_jenjang' => 'JJ001', 'warna_teks' => '#0D9488', 'warna_bg_soft' => '#F0FDFA', 'deskripsi' => 'Pengenalan nilai-nilai keagamaan, toleransi, dan tempat ibadah bersejarah di Kota Bengkulu.'],
            ['id_mapel' => 'MP002', 'nama_mapel' => 'Pendidikan Pancasila', 'id_jenjang' => 'JJ001', 'warna_teks' => '#DC2626', 'warna_bg_soft' => '#FEF2F2', 'deskripsi' => 'Penanaman nilai kebangsaan, cinta tanah air, dan pahlawan nasional Bung Karno & Ibu Fatmawati.'],
            ['id_mapel' => 'MP003', 'nama_mapel' => 'Bahasa Indonesia', 'id_jenjang' => 'JJ001', 'warna_teks' => '#2563EB', 'warna_bg_soft' => '#EFF6FF', 'deskripsi' => 'Pengembangan kemampuan literasi membaca, bercerita, dan kosakata melalui ruang pustaka & budaya.'],
            ['id_mapel' => 'MP004', 'nama_mapel' => 'Matematika', 'id_jenjang' => 'JJ001', 'warna_teks' => '#D97706', 'warna_bg_soft' => '#FFFBEB', 'deskripsi' => 'Pengukuran jarak, estimasi luas kawasan, dan perhitungan sederhana dalam kehidupan nyata.'],
            ['id_mapel' => 'MP005', 'nama_mapel' => 'IPAS', 'id_jenjang' => 'JJ001', 'warna_teks' => '#059669', 'warna_bg_soft' => '#ECFDF5', 'deskripsi' => 'Eksplorasi ilmu pengetahuan alam dan sosial: ekosistem pesisir, tumbuhan mangrove, dan hewan laut.'],
            ['id_mapel' => 'MP006', 'nama_mapel' => 'Pendidikan Jasmani Olahraga dan Kesehatan', 'id_jenjang' => 'JJ001', 'warna_teks' => '#0284C7', 'warna_bg_soft' => '#F0F9FF', 'deskripsi' => 'Aktivitas fisik luar ruang, kebugaran jasmani, jogging track pantai, dan area rekreasi aktif.'],
            ['id_mapel' => 'MP007', 'nama_mapel' => 'Seni Budaya', 'id_jenjang' => 'JJ001', 'warna_teks' => '#9333EA', 'warna_bg_soft' => '#FAF5FF', 'deskripsi' => 'Pengenalan motif batik Besurek, seni ukir, musik tradisional Bengkulu, dan pentas seni.'],
            ['id_mapel' => 'MP008', 'nama_mapel' => 'Bahasa Inggris', 'id_jenjang' => 'JJ001', 'warna_teks' => '#4F46E5', 'warna_bg_soft' => '#EEF2FF', 'deskripsi' => 'Pengenalan kosakata pariwisata internasional di situs cagar budaya peninggalan bangsa Inggris.'],

            // --- JENJANG SMP / MTs (JJ002) ---
            ['id_mapel' => 'MP009', 'nama_mapel' => 'Ilmu Pengetahuan Alam', 'id_jenjang' => 'JJ002', 'warna_teks' => '#059669', 'warna_bg_soft' => '#ECFDF5', 'deskripsi' => 'Observasi ekosistem laut pesisir, konservasi penyu dan mangrove, gelombang dan tekanan air laut.'],
            ['id_mapel' => 'MP010', 'nama_mapel' => 'Ilmu Pengetahuan Sosial', 'id_jenjang' => 'JJ002', 'warna_teks' => '#EA580C', 'warna_bg_soft' => '#FFF7ED', 'deskripsi' => 'Studi dinamika masyarakat nelayan pesisir, interaksi ruang ekonomi lokal, dan cagar budaya.'],
            ['id_mapel' => 'MP011', 'nama_mapel' => 'Bahasa Indonesia', 'id_jenjang' => 'JJ002', 'warna_teks' => '#2563EB', 'warna_bg_soft' => '#EFF6FF', 'deskripsi' => 'Penulisan laporan observasi lapangan, teks deskripsi tempat wisata, dan resensi sejarah.'],
            ['id_mapel' => 'MP012', 'nama_mapel' => 'Matematika', 'id_jenjang' => 'JJ002', 'warna_teks' => '#D97706', 'warna_bg_soft' => '#FFFBEB', 'deskripsi' => 'Kalkulasi rasio, geometri bangun benteng cagar budaya, serta analisis statistik sederhana.'],
            ['id_mapel' => 'MP013', 'nama_mapel' => 'Pendidikan Pancasila', 'id_jenjang' => 'JJ002', 'warna_teks' => '#DC2626', 'warna_bg_soft' => '#FEF2F2', 'deskripsi' => 'Nilai-nilai kebhinekaan, toleransi masyarakat multikultural, dan sejarah perjuangan kemerdekaan.'],
            ['id_mapel' => 'MP014', 'nama_mapel' => 'Pendidikan Agama', 'id_jenjang' => 'JJ002', 'warna_teks' => '#0D9488', 'warna_bg_soft' => '#F0FDFA', 'deskripsi' => 'Kajian sejarah Islam di Bengkulu, arsitektur masjid bersejarah Bung Karno, dan kearifan lokal.'],
            ['id_mapel' => 'MP015', 'nama_mapel' => 'Informatika', 'id_jenjang' => 'JJ002', 'warna_teks' => '#132985', 'warna_bg_soft' => '#EEF2FF', 'deskripsi' => 'Pemanfaatan teknologi informasi, penelusuran data digital edukatif, dan analisis informasi.'],
            ['id_mapel' => 'MP016', 'nama_mapel' => 'Bahasa Inggris', 'id_jenjang' => 'JJ002', 'warna_teks' => '#4F46E5', 'warna_bg_soft' => '#EEF2FF', 'deskripsi' => 'Praktik pemanduan wisata berbahasa Inggris di Fort Marlborough dan Monumen Thomas Parr.'],
            ['id_mapel' => 'MP017', 'nama_mapel' => 'Seni Budaya', 'id_jenjang' => 'JJ002', 'warna_teks' => '#9333EA', 'warna_bg_soft' => '#FAF5FF', 'deskripsi' => 'Apresiasi karya seni daerah Bengkulu, kriya batik, seni rupa pesisir, dan tari tradisional.'],

            // --- JENJANG SMA / SMK / MA (JJ003) ---
            ['id_mapel' => 'MP018', 'nama_mapel' => 'Sejarah', 'id_jenjang' => 'JJ003', 'warna_teks' => '#EA580C', 'warna_bg_soft' => '#FFF7ED', 'deskripsi' => 'Analisis kritis kolonialisme Inggris EIC, masa pengasingan Bung Karno, dan revolusi nasional.'],
            ['id_mapel' => 'MP019', 'nama_mapel' => 'Biologi', 'id_jenjang' => 'JJ003', 'warna_teks' => '#059669', 'warna_bg_soft' => '#ECFDF5', 'deskripsi' => 'Riset biologi kelautan, konservasi hutan mangrove Pulau Baai, dan keanekaragaman flora pesisir.'],
            ['id_mapel' => 'MP020', 'nama_mapel' => 'Fisika', 'id_jenjang' => 'JJ003', 'warna_teks' => '#0891B2', 'warna_bg_soft' => '#ECFEFF', 'deskripsi' => 'Mekanika gelombang laut Samudera Hindia, aerodinamika angin pesisir, dan energi kinetik arus.'],
            ['id_mapel' => 'MP021', 'nama_mapel' => 'Matematika', 'id_jenjang' => 'JJ003', 'warna_teks' => '#D97706', 'warna_bg_soft' => '#FFFBEB', 'deskripsi' => 'Trigonometri navigasi maritim, estimasi jarak spasial Haversine, dan pemodelan matematis.'],
            ['id_mapel' => 'MP022', 'nama_mapel' => 'Ekonomi', 'id_jenjang' => 'JJ003', 'warna_teks' => '#CA8A04', 'warna_bg_soft' => '#FEFCE8', 'deskripsi' => 'Kajian ekonomi mikro pelaku UMKM pesisir, sentra perikanan, rantai pasok dan kewirausahaan.'],
            ['id_mapel' => 'MP023', 'nama_mapel' => 'Geografi', 'id_jenjang' => 'JJ003', 'warna_teks' => '#4F46E5', 'warna_bg_soft' => '#EEF2FF', 'deskripsi' => 'Geomorfologi pesisir, mitigasi abrasi pantai, tata ruang wilayah pesisir Kota Bengkulu.'],
            ['id_mapel' => 'MP024', 'nama_mapel' => 'Sosiologi', 'id_jenjang' => 'JJ003', 'warna_teks' => '#9333EA', 'warna_bg_soft' => '#FAF5FF', 'deskripsi' => 'Struktur sosial komunitas nelayan, kearifan lokal pesisir, modal sosial, dan perubahan sosial.'],
            ['id_mapel' => 'MP025', 'nama_mapel' => 'Informatika', 'id_jenjang' => 'JJ003', 'warna_teks' => '#132985', 'warna_bg_soft' => '#EEF2FF', 'deskripsi' => 'Sistem informasi geografis, algoritma pembobotan SPK SAW, dan pengolahan data spasial.'],
            ['id_mapel' => 'MP026', 'nama_mapel' => 'Bahasa Indonesia', 'id_jenjang' => 'JJ003', 'warna_teks' => '#2563EB', 'warna_bg_soft' => '#EFF6FF', 'deskripsi' => 'Karya tulis ilmiah hasil studi lapangan, penulisan esai kritis, dan analisis dokumen sejarah.'],
            ['id_mapel' => 'MP027', 'nama_mapel' => 'Bahasa Inggris', 'id_jenjang' => 'JJ003', 'warna_teks' => '#3B82F6', 'warna_bg_soft' => '#EFF6FF', 'deskripsi' => 'Komunikasi akademis internasional, translasi arsip peninggalan Inggris British East India Co.'],
            ['id_mapel' => 'MP028', 'nama_mapel' => 'Pendidikan Pancasila', 'id_jenjang' => 'JJ003', 'warna_teks' => '#DC2626', 'warna_bg_soft' => '#FEF2F2', 'deskripsi' => 'Kajian geopolitik kebangsaan, integrasi nasional, dan jejak perumusan dasar negara di Bengkulu.'],
            ['id_mapel' => 'MP029', 'nama_mapel' => 'Pendidikan Agama', 'id_jenjang' => 'JJ003', 'warna_teks' => '#0D9488', 'warna_bg_soft' => '#F0FDFA', 'deskripsi' => 'Studi peradaban Islam Nusantara di pesisir barat Sumatera dan arsitektur religius bersejarah.'],
        ];

        // Upsert subjects by kode_mapel
        $subjectModelMap = [];
        foreach ($subjectsData as $data) {
            $sub = Subject::updateOrCreate(
                ['kode_mapel' => $data['id_mapel']],
                [
                    'nama_mapel' => $data['nama_mapel'],
                    'id_jenjang' => $data['id_jenjang'],
                    'warna_teks' => $data['warna_teks'],
                    'warna_bg_soft' => $data['warna_bg_soft'],
                    'deskripsi' => $data['deskripsi'],
                ]
            );
            $subjectModelMap[$data['id_mapel']] = $sub;
        }

        // =========================================================================
        // 2. CONNECT 50 LOCATIONS WITH THE 29 SUBJECTS
        // =========================================================================
        // Clear pivot and rebind intelligently
        DB::table('location_subject')->truncate();

        $locations = Location::all();

        foreach ($locations as $loc) {
            $cat = $loc->kategori ?? 'Cagar Budaya';
            $nama = strtolower($loc->nama_lokasi . ' ' . $loc->deskripsi);

            $attachIds = [];

            // History / Culture places (Benteng, Rumah Bung Karno, Museum, Monumen)
            if (str_contains($nama, 'benteng') || str_contains($nama, 'bung karno') || str_contains($nama, 'museum') || str_contains($nama, 'monumen') || str_contains($nama, 'cagar budaya') || str_contains($nama, 'fatmawati') || str_contains($nama, 'makam') || str_contains($nama, 'parr') || str_contains($nama, 'hamilton')) {
                // SD
                $attachIds[] = $subjectModelMap['MP002']->id; // Pend. Pancasila
                $attachIds[] = $subjectModelMap['MP005']->id; // IPAS
                $attachIds[] = $subjectModelMap['MP007']->id; // Seni Budaya
                $attachIds[] = $subjectModelMap['MP008']->id; // B. Inggris
                // SMP
                $attachIds[] = $subjectModelMap['MP010']->id; // IPS
                $attachIds[] = $subjectModelMap['MP013']->id; // Pend. Pancasila
                $attachIds[] = $subjectModelMap['MP016']->id; // B. Inggris
                $attachIds[] = $subjectModelMap['MP017']->id; // Seni Budaya
                // SMA
                $attachIds[] = $subjectModelMap['MP018']->id; // Sejarah
                $attachIds[] = $subjectModelMap['MP024']->id; // Sosiologi
                $attachIds[] = $subjectModelMap['MP027']->id; // B. Inggris
                $attachIds[] = $subjectModelMap['MP028']->id; // Pend. Pancasila
            }

            // Nature / Ecology / Beach / Mangrove / Hydroponics
            if (str_contains($nama, 'mangrove') || str_contains($nama, 'pantai') || str_contains($nama, 'alam') || str_contains($nama, 'taman') || str_contains($nama, 'kebun') || str_contains($nama, 'wisata') || str_contains($nama, 'danau') || str_contains($nama, 'hutan') || str_contains($nama, 'sungai') || str_contains($nama, 'farm')) {
                // SD
                $attachIds[] = $subjectModelMap['MP005']->id; // IPAS
                $attachIds[] = $subjectModelMap['MP006']->id; // PJOK
                // SMP
                $attachIds[] = $subjectModelMap['MP009']->id; // IPA
                $attachIds[] = $subjectModelMap['MP010']->id; // IPS
                // SMA
                $attachIds[] = $subjectModelMap['MP019']->id; // Biologi
                $attachIds[] = $subjectModelMap['MP020']->id; // Fisika
                $attachIds[] = $subjectModelMap['MP023']->id; // Geografi
            }

            // Religious Places (Masjid Jamik, Masjid Raya Baitul Izzah, dsb.)
            if (str_contains($nama, 'masjid') || str_contains($nama, 'religi') || str_contains($nama, 'ibadah')) {
                $attachIds[] = $subjectModelMap['MP001']->id; // Agama SD
                $attachIds[] = $subjectModelMap['MP014']->id; // Agama SMP
                $attachIds[] = $subjectModelMap['MP029']->id; // Agama SMA
                $attachIds[] = $subjectModelMap['MP007']->id; // Seni Budaya SD
                $attachIds[] = $subjectModelMap['MP017']->id; // Seni Budaya SMP
            }

            // Economic / Creative / Cafe / UMKM / Pasar
            if (str_contains($nama, 'kafe') || str_contains($nama, 'cafe') || str_contains($nama, 'pasar') || str_contains($nama, 'umkm') || str_contains($nama, 'kreatif') || str_contains($nama, 'batik') || str_contains($nama, 'kuliner') || str_contains($nama, 'sentra')) {
                // SD
                $attachIds[] = $subjectModelMap['MP004']->id; // Matematika
                $attachIds[] = $subjectModelMap['MP007']->id; // Seni Budaya
                // SMP
                $attachIds[] = $subjectModelMap['MP010']->id; // IPS
                $attachIds[] = $subjectModelMap['MP012']->id; // Matematika
                $attachIds[] = $subjectModelMap['MP017']->id; // Seni Budaya
                // SMA
                $attachIds[] = $subjectModelMap['MP022']->id; // Ekonomi
                $attachIds[] = $subjectModelMap['MP024']->id; // Sosiologi
            }

            // Literacy / Library / Coworking / IT / Study
            if (str_contains($nama, 'pustaka') || str_contains($nama, 'perpustakaan') || str_contains($nama, 'literasi') || str_contains($nama, 'baca') || str_contains($nama, 'space') || str_contains($nama, 'balai')) {
                // SD
                $attachIds[] = $subjectModelMap['MP003']->id; // B. Indo
                // SMP
                $attachIds[] = $subjectModelMap['MP011']->id; // B. Indo
                $attachIds[] = $subjectModelMap['MP015']->id; // Informatika
                // SMA
                $attachIds[] = $subjectModelMap['MP025']->id; // Informatika
                $attachIds[] = $subjectModelMap['MP026']->id; // B. Indo
            }

            // General fallback: ensure every location has at least 1 subject from each jenjang
            if (empty($attachIds)) {
                $attachIds = [
                    $subjectModelMap['MP005']->id, // IPAS SD
                    $subjectModelMap['MP009']->id, // IPA SMP
                    $subjectModelMap['MP019']->id, // Biologi SMA
                    $subjectModelMap['MP023']->id, // Geografi SMA
                ];
            }

            $uniqueIds = array_unique($attachIds);
            foreach ($uniqueIds as $subId) {
                DB::table('location_subject')->insert([
                    'location_id' => $loc->id,
                    'subject_id' => $subId,
                    'skor_relevansi_c1' => rand(80, 95),
                    'topik_kajian' => 'Kajian Lapangan Edukatif ' . $loc->nama_lokasi,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Apply rich contextual topics
        $this->call(InterestingTopicsSeeder::class);

        // =========================================================================
        // 3. SEED ALL 267 SCHOOLS (KOTA BENGKULU)
        // =========================================================================
        School::truncate();

        $schoolsData = [
            [1, 'MAN 1 KOTA BENGKULU', 'Gading Cempaka', -3.820700, 102.292600, 'JJ003'],
            [2, 'MAN 2 KOTA BENGKULU', 'Selebar', -3.855700, 102.341800, 'JJ003'],
            [3, 'MAS JA AL HAQ', 'Kampung Melayu', -3.830171, 102.345301, 'JJ003'],
            [4, 'MAS DARUSSALAM', 'Singaran Pati', -3.806300, 102.299500, 'JJ003'],
            [5, 'MAS MUHAMMADIYAH', 'Ratu Samban', -3.796200, 102.264400, 'JJ003'],
            [6, 'MAS Hidayatul Qomariyah', 'Kampung Melayu', -3.870455, 102.316107, 'JJ003'],
            [7, 'MAS PANCASILA', 'Singaran Pati', -3.808800, 102.288300, 'JJ003'],
            [8, 'Kreatif', 'Selebar', -3.850100, 102.320100, 'JJ003'],
            [9, 'MA AL-QUR`AN HARSALLAKUM', 'Selebar', -3.834100, 102.321200, 'JJ003'],
            [10, 'MAS YASURUKA', 'Muara Bangkahulu', -3.760100, 102.290100, 'JJ003'],
            [11, 'MAS Insan Kamil Islamic School', 'Ratu Samban', -3.795100, 102.260100, 'JJ003'],
            [12, 'MIS NURUL HUDA', 'Singaran Pati', -3.811500, 102.301500, 'JJ001'],
            [13, 'MIS AL-MUHAJIRIN', 'Singaran Pati', -3.812500, 102.302500, 'JJ001'],
            [14, 'MIS AL - ISLAM', 'Kampung Melayu', -3.901500, 102.311500, 'JJ001'],
            [15, 'MIS AL BAANI', 'Kampung Melayu', -3.902500, 102.312500, 'JJ001'],
            [16, 'MIS MUHAMMADIYAH', 'Muara Bangkahulu', -3.743116, 102.264079, 'JJ001'],
            [17, 'MIN 2 KOTA BENGKULU', 'Selebar', -3.836200, 102.327800, 'JJ001'],
            [18, 'MIS DARUSSALAM', 'Singaran Pati', -3.813500, 102.303500, 'JJ001'],
            [19, 'MIN 1 KOTA BENGKULU', 'Sungai Serut', -3.781500, 102.271500, 'JJ001'],
            [20, 'MIS PLUS JA-ALHAQ', 'Gading Cempaka', -3.835392, 102.297358, 'JJ001'],
            [21, 'MIS Ikhwanul Djauhariah', 'Kampung Melayu', -3.903500, 102.313500, 'JJ001'],
            [22, 'MIS Humaira', 'Selebar', -3.829691, 102.316918, 'JJ001'],
            [23, 'MI TERPADU MUTIARA ASSYIFA KOTA BENGKULU', 'Selebar', -3.841500, 102.321500, 'JJ001'],
            [24, 'MIS HIDAYATUL HASANIYYAH', 'Muara Bangkahulu', -3.761500, 102.291500, 'JJ001'],
            [25, 'MIS IT GENERASI RABBANI', 'Selebar', -3.842600, 102.336200, 'JJ001'],
            [26, 'MIS ASY-SYIFA', 'Sungai Serut', -3.782500, 102.272500, 'JJ001'],
            [27, 'MIS AS SHAFFAH', 'Selebar', -3.842500, 102.322500, 'JJ001'],
            [28, 'MI Abdurrahman Bin Auf', 'Selebar', -3.843500, 102.323500, 'JJ001'],
            [29, 'MTSS PANCASILA', 'Singaran Pati', -3.814500, 102.304500, 'JJ002'],
            [30, 'MTSN 1 KOTA BENGKULU', 'Singaran Pati', -3.830291, 102.314155, 'JJ002'],
            [31, 'MTSN 2 KOTA BENGKULU', 'Kampung Melayu', -3.904500, 102.314500, 'JJ002'],
            [32, 'MTSS JA-ALHAQ', 'Kampung Melayu', -3.905500, 102.315500, 'JJ002'],
            [33, 'MTSS MUHAMMADIYAH', 'Ratu Samban', -3.801500, 102.261500, 'JJ002'],
            [34, 'MTSS DARUSSALAM', 'Singaran Pati', -3.815500, 102.305500, 'JJ002'],
            [35, 'MTSS AL-QURAN HARSALLAKUM', 'Selebar', -3.834165, 102.321258, 'JJ002'],
            [36, 'MTSS MUBAARAK', 'Selebar', -3.820800, 102.321400, 'JJ002'],
            [37, 'MTs ROUDLOTUR ROSMANI', 'Selebar', -3.844500, 102.324500, 'JJ002'],
            [38, 'MTSS HUMAIRA HIBRIDA 13', 'Selebar', -3.827000, 102.317700, 'JJ002'],
            [39, 'MTs NUR RAHMA', 'Kampung Melayu', -3.906500, 102.316500, 'JJ002'],
            [40, 'MTSS Plus Tahfidz Al-Qur`an Abdurrahman Bin Auf', 'Selebar', -3.845500, 102.325500, 'JJ002'],
            [41, 'SD NEGERI 99 KOTA BENGKULU', 'Gading Cempaka', -3.832200, 102.313600, 'JJ001'],
            [42, 'SD NEGERI 09 KOTA BENGKULU', 'Ratu Samban', -3.802500, 102.262500, 'JJ001'],
            [43, 'SD NEGERI 88 KOTA BENGKULU', 'Muara Bangkahulu', -3.772481, 102.300540, 'JJ001'],
            [44, 'SD NEGERI 86 KOTA BENGKULU', 'Muara Bangkahulu', -3.755900, 102.268100, 'JJ001'],
            [45, 'SD NEGERI 85 KOTA BENGKULU', 'Muara Bangkahulu', -3.780500, 102.277900, 'JJ001'],
            [46, 'SD NEGERI 84 KOTA BENGKULU', 'Selebar', -3.880300, 102.349300, 'JJ001'],
            [47, 'SD NEGERI 83 KOTA BENGKULU', 'Kampung Melayu', -3.907500, 102.317500, 'JJ001'],
            [48, 'SD NEGERI 81 KOTA BENGKULU', 'Gading Cempaka', -3.835100, 102.306000, 'JJ001'],
            [49, 'SD NEGERI 08 KOTA BENGKULU', 'Teluk Segara', -3.793000, 102.252200, 'JJ001'],
            [50, 'SD NEGERI 03 KOTA BENGKULU', 'Sungai Serut', -3.783500, 102.273500, 'JJ001'],
            [51, 'SD NEGERI 11 KOTA BENGKULU', 'Teluk Segara', -3.791500, 102.251500, 'JJ001'],
            [52, 'SD NEGERI 102 KOTA BENGKULU', 'Kampung Melayu', -3.908500, 102.318500, 'JJ001'],
            [53, 'SD NEGERI 101 KOTA BENGKULU', 'Selebar', -3.867500, 102.355100, 'JJ001'],
            [54, 'SD NEGERI 01 KOTA BENGKULU', 'Teluk Segara', -3.790400, 102.249700, 'JJ001'],
            [55, 'SD NEGERI 15 KOTA BENGKULU', 'Ratu Agung', -3.811500, 102.271500, 'JJ001'],
            [56, 'SD NEGERI 16 KOTA BENGKULU', 'Selebar', -3.859800, 102.323200, 'JJ001'],
            [57, 'SD NEGERI 29 KOTA BENGKULU', 'Ratu Agung', -3.804600, 102.275400, 'JJ001'],
            [58, 'SD NEGERI 27 KOTA BENGKULU', 'Ratu Samban', -3.803500, 102.263500, 'JJ001'],
            [59, 'SD NEGERI 26 KOTA BENGKULU', 'Teluk Segara', -3.788400, 102.259700, 'JJ001'],
            [60, 'SD NEGERI 25 KOTA BENGKULU', 'Sungai Serut', -3.784500, 102.274500, 'JJ001'],
            [61, 'SD NEGERI 24 KOTA BENGKULU', 'Gading Cempaka', -3.811700, 102.298500, 'JJ001'],
            [62, 'SD NEGERI 22 KOTA BENGKULU', 'Ratu Samban', -3.804500, 102.264500, 'JJ001'],
            [63, 'SD NEGERI 19 KOTA BENGKULU', 'Ratu Agung', -3.796600, 102.273100, 'JJ001'],
            [64, 'SD NEGERI 18 KOTA BENGKULU', 'Ratu Agung', -3.804600, 102.269200, 'JJ001'],
            [65, 'SD NEGERI 17 KOTA BENGKULU', 'Sungai Serut', -3.785500, 102.275500, 'JJ001'],
            [66, 'SDIT IQRA 2 KOTA BENGKULU', 'Ratu Agung', -3.796500, 102.284500, 'JJ001'],
            [67, 'SDIT HIDAYATULLAH KOTA BENGKULU', 'Sungai Serut', -3.786500, 102.276500, 'JJ001'],
            [68, 'SD SINT CAROLUS KOTA BENGKULU', 'Teluk Segara', -3.793100, 102.249500, 'JJ001'],
            [69, 'SD PELITA KASIH KOTA BENGKULU', 'Gading Cempaka', -3.821100, 102.310200, 'JJ001'],
            [70, 'SD MUHAMMADIYAH 5 KOTA BENGKULU', 'Ratu Samban', -3.805500, 102.265500, 'JJ001'],
            [71, 'SD MUHAMMADIYAH 2 KOTA BENGKULU', 'Teluk Segara', -3.792500, 102.252500, 'JJ001'],
            [72, 'SD MUHAMMADIYAH 1 KOTA BENGKULU', 'Teluk Segara', -3.791200, 102.257200, 'JJ001'],
            [73, 'SD NEGERI 79 KOTA BENGKULU', 'Selebar', -3.848500, 102.315000, 'JJ001'],
            [74, 'SD NEGERI 61 KOTA BENGKULU', 'Singaran Pati', -3.816500, 102.306500, 'JJ001'],
            [75, 'SD NEGERI 60 KOTA BENGKULU', 'Singaran Pati', -3.817500, 102.307500, 'JJ001'],
            [76, 'SD NEGERI 59 KOTA BENGKULU', 'Ratu Agung', -3.801000, 102.279100, 'JJ001'],
            [77, 'SD NEGERI 58 KOTA BENGKULU', 'Ratu Agung', -3.813800, 102.280000, 'JJ001'],
            [78, 'SD NEGERI 57 KOTA BENGKULU', 'Sungai Serut', -3.787500, 102.277500, 'JJ001'],
            [79, 'SD NEGERI 56 KOTA BENGKULU', 'Selebar', -3.847300, 102.308900, 'JJ001'],
            [80, 'SD NEGERI 65 KOTA BENGKULU', 'Sungai Serut', -3.788500, 102.278500, 'JJ001'],
            [81, 'SD NEGERI 66 KOTA BENGKULU', 'Selebar', -3.849600, 102.323800, 'JJ001'],
            [82, 'SD NEGERI 78 KOTA BENGKULU', 'Selebar', -3.877100, 102.351600, 'JJ001'],
            [83, 'SD NEGERI 77 KOTA BENGKULU', 'Kampung Melayu', -3.909500, 102.319500, 'JJ001'],
            [84, 'SD NEGERI 76 KOTA BENGKULU', 'Selebar', -3.849900, 102.334100, 'JJ001'],
            [85, 'SD NEGERI 75 KOTA BENGKULU', 'Kampung Melayu', -3.910500, 102.320500, 'JJ001'],
            [86, 'SD NEGERI 74 KOTA BENGKULU', 'Selebar', -3.842200, 102.319300, 'JJ001'],
            [87, 'SD NEGERI 72 KOTA BENGKULU', 'Muara Bangkahulu', -3.773200, 102.302400, 'JJ001'],
            [88, 'SD NEGERI 71 KOTA BENGKULU', 'Muara Bangkahulu', -3.760900, 102.295800, 'JJ001'],
            [89, 'SD NEGERI 07 KOTA BENGKULU', 'Teluk Segara', -3.786000, 102.258200, 'JJ001'],
            [90, 'SD NEGERI 69 KOTA BENGKULU', 'Muara Bangkahulu', -3.747300, 102.285600, 'JJ001'],
            [91, 'SD NEGERI 68 KOTA BENGKULU', 'Muara Bangkahulu', -3.771500, 102.270600, 'JJ001'],
            [92, 'SD NEGERI 67 KOTA BENGKULU', 'Sungai Serut', -3.789500, 102.279500, 'JJ001'],
            [93, 'SD NEGERI 55 KOTA BENGKULU', 'Gading Cempaka', -3.833381, 102.299479, 'JJ001'],
            [94, 'SD NEGERI 53 KOTA BENGKULU', 'Ratu Samban', -3.806500, 102.266500, 'JJ001'],
            [95, 'SD NEGERI 42 KOTA BENGKULU', 'Singaran Pati', -3.818500, 102.308500, 'JJ001'],
            [96, 'SD NEGERI 41 KOTA BENGKULU', 'Singaran Pati', -3.819500, 102.309500, 'JJ001'],
            [97, 'SD NEGERI 40 KOTA BENGKULU', 'Ratu Agung', -3.799000, 102.279600, 'JJ001'],
            [98, 'SD NEGERI 04 KOTA BENGKULU', 'Teluk Segara', -3.788700, 102.254300, 'JJ001'],
            [99, 'SD NEGERI 38 KOTA BENGKULU', 'Ratu Agung', -3.817400, 102.277700, 'JJ001'],
            [100, 'SD NEGERI 37 KOTA BENGKULU', 'Teluk Segara', -3.793500, 102.253500, 'JJ001'],
            [101, 'SD NEGERI 36 KOTA BENGKULU', 'Ratu Agung', -3.809100, 102.269500, 'JJ001'],
            [102, 'SD NEGERI 35 KOTA BENGKULU', 'Gading Cempaka', -3.838200, 102.306700, 'JJ001'],
            [103, 'SD NEGERI 34 KOTA BENGKULU', 'Ratu Agung', -3.801000, 102.267600, 'JJ001'],
            [104, 'SD NEGERI 52 KOTA BENGKULU', 'Singaran Pati', -3.820500, 102.310500, 'JJ001'],
            [105, 'SD NEGERI 51 KOTA BENGKULU', 'Ratu Agung', -3.813977, 102.274756, 'JJ001'],
            [106, 'SD NEGERI 50 KOTA BENGKULU', 'Ratu Agung', -3.789300, 102.275100, 'JJ001'],
            [107, 'SD NEGERI 05 KOTA BENGKULU', 'Gading Cempaka', -3.821500, 102.291300, 'JJ001'],
            [108, 'SD NEGERI 49 KOTA BENGKULU', 'Ratu Agung', -3.796600, 102.276500, 'JJ001'],
            [109, 'SD NEGERI 48 KOTA BENGKULU', 'Sungai Serut', -3.790500, 102.280500, 'JJ001'],
            [110, 'SD NEGERI 47 KOTA BENGKULU', 'Teluk Segara', -3.794500, 102.254500, 'JJ001'],
            [111, 'SD NEGERI 44 KOTA BENGKULU', 'Gading Cempaka', -3.824039, 102.285502, 'JJ001'],
            [112, 'SD NEGERI 32 KOTA BENGKULU', 'Ratu Agung', -3.810500, 102.277500, 'JJ001'],
            [113, 'SD NEGERI 06 KOTA BENGKULU', 'Teluk Segara', -3.783600, 102.263400, 'JJ001'],
            [114, 'SD NEGERI 89 KOTA BENGKULU', 'Muara Bangkahulu', -3.751500, 102.292800, 'JJ001'],
            [115, 'SD AL-MANAR KOTA BENGKULU', 'Ratu Samban', -3.807500, 102.267500, 'JJ001'],
            [116, 'SD NEGERI 02 KOTA BENGKULU', 'Ratu Samban', -3.808500, 102.268500, 'JJ001'],
            [117, 'SD NEGERI 20 KOTA BENGKULU', 'Gading Cempaka', -3.824900, 102.301300, 'JJ001'],
            [118, 'SDIT IQRA 1 KOTA BENGKULU', 'Ratu Agung', -3.797100, 102.277200, 'JJ001'],
            [119, 'SD ALAM MAHIRA KOTA BENGKULU', 'Ratu Agung', -3.801200, 102.283400, 'JJ001'],
            [120, 'SDIT GENERASI RABBANI KOTA BENGKULU', 'Singaran Pati', -3.821500, 102.311500, 'JJ001'],
            [121, 'SD NEGERI 43 KOTA BENGKULU', 'Ratu Agung', -3.812500, 102.272500, 'JJ001'],
            [122, 'SD NEGERI 87 KOTA BENGKULU', 'Kampung Melayu', -3.911500, 102.321500, 'JJ001'],
            [123, 'SD NEGERI 82 KOTA BENGKULU', 'Gading Cempaka', -3.827300, 102.314700, 'JJ001'],
            [124, 'SD NEGERI 103 KOTA BENGKULU', 'Muara Bangkahulu', -3.776000, 102.288300, 'JJ001'],
            [125, 'SDIT AL HASANAH 1 KOTA BENGKULU', 'Selebar', -3.850100, 102.314800, 'JJ001'],
            [126, 'SD NEGERI 13 KOTA BENGKULU', 'Ratu Samban', -3.809500, 102.269500, 'JJ001'],
            [127, 'SD NEGERI 12 KOTA BENGKULU', 'Ratu Samban', -3.810500, 102.270500, 'JJ001'],
            [128, 'SD NEGERI 45 KOTA BENGKULU', 'Singaran Pati', -3.822500, 102.312500, 'JJ001'],
            [129, 'SD NEGERI 73 KOTA BENGKULU', 'Singaran Pati', -3.810500, 102.309700, 'JJ001'],
            [130, 'SDIT AL-MARJAN KOTA BENGKULU', 'Ratu Agung', -3.810400, 102.276600, 'JJ001'],
            [131, 'SDIT RABBANI KOTA BENGKULU', 'Ratu Agung', -3.807000, 102.282300, 'JJ001'],
            [132, 'SDIT AL AUFA KOTA BENGKULU', 'Selebar', -3.829800, 102.316600, 'JJ001'],
            [133, 'SD ISLAM AL KISWAH KOTA BENGKULU', 'Selebar', -3.845300, 102.342600, 'JJ001'],
            [134, 'SDIT INSAN MULIA KOTA BENGKULU', 'Sungai Serut', -3.789800, 102.308700, 'JJ001'],
            [135, 'SDIT BAITUL IZZAH KOTA BENGKULU', 'Gading Cempaka', -3.821000, 102.287900, 'JJ001'],
            [136, 'SD PATRIA DHARMA KOTA BENGKULU', 'Teluk Segara', -3.794917, 102.255994, 'JJ001'],
            [137, 'SD NEGERI 105 KOTA BENGKULU', 'Kampung Melayu', -3.912500, 102.322500, 'JJ001'],
            [138, 'SD NEGERI 106 KOTA BENGKULU', 'Selebar', -3.828000, 102.321700, 'JJ001'],
            [139, 'Plus Nur Rahma', 'Kampung Melayu', -3.913500, 102.323500, 'JJ001'],
            [140, 'SDIT AL-YASIIR KOTA BENGKULU', 'Singaran Pati', -3.823500, 102.313500, 'JJ001'],
            [141, 'SD FATMA KENANGA KOTA BENGKULU', 'Ratu Agung', -3.804320, 102.275370, 'JJ001'],
            [142, 'SD ISLAM AL AZHAR 51 KOTA BENGKULU', 'Singaran Pati', -3.824500, 102.314500, 'JJ001'],
            [143, 'SDIT NURUL FIKRI KOTA BENGKULU', 'Singaran Pati', -3.825500, 102.315500, 'JJ001'],
            [144, 'SDIT UMMI KOTA BENGKULU', 'Singaran Pati', -3.826500, 102.316500, 'JJ001'],
            [145, 'SD INSAN KAMIL ISLAMIC SCHOOL KOTA BENGKULU', 'Ratu Samban', -3.811500, 102.271500, 'JJ001'],
            [146, 'SDIT ULUL ALBAAB KOTA BENGKULU', 'Muara Bangkahulu', -3.751500, 102.293700, 'JJ001'],
            [147, 'SD INTEGRAL HIDAYATULLAH KOTA BENGKULU', 'Selebar', -3.857706, 102.325972, 'JJ001'],
            [148, 'SDIT ALAM GENERASI ISLAM KOTA BENGKULU', 'Muara Bangkahulu', -3.769100, 102.295100, 'JJ001'],
            [149, 'SD LANGIT BIRU KOTA BENGKULU', 'Muara Bangkahulu', -3.761300, 102.293100, 'JJ001'],
            [150, 'SDIT UMMU FATHIMAH KOTA BENGKULU', 'Selebar', -3.875483, 102.343467, 'JJ001'],
            [151, 'SD HIGHSCOPE INDONESIA BENGKULU', 'Gading Cempaka', -3.825370, 102.288703, 'JJ001'],
            [152, 'SD QURAN BAKTI ILAAHI KOTA BENGKULU', 'Teluk Segara', -3.795500, 102.255500, 'JJ001'],
            [153, 'SDIT AL ANWAR MUSLIM FADILAH KOTA BENGKULU', 'Selebar', -3.839500, 102.314500, 'JJ001'],
            [154, 'SDIT INSAN MADANI KOTA BENGKULU', 'Ratu Agung', -3.813500, 102.273500, 'JJ001'],
            [155, 'SDIT AL HASANAH 2 KOTA BENGKULU', 'Selebar', -3.874700, 102.343400, 'JJ001'],
            [156, 'SD ISLAM PLUS KARAKTER KOTA BENGKULU', 'Selebar', -3.843200, 102.340000, 'JJ001'],
            [157, 'SD PLUS SAMUDERA KASIH KOTA BENGKULU', 'Kampung Melayu', -3.914500, 102.324500, 'JJ001'],
            [158, 'SDIT INSAN INTANI KOTA BENGKULU', 'Muara Bangkahulu', -3.762500, 102.292500, 'JJ001'],
            [159, 'SDIT AS SYAKUR KOTA BENGKULU', 'Singaran Pati', -3.827500, 102.317500, 'JJ001'],
            [160, 'SDIT HARAPAN DAN DOA KOTA BENGKULU', 'Ratu Samban', -3.812500, 102.272500, 'JJ001'],
            [161, 'SDIT IQRA 3 KOTA BENGKULU', 'Selebar', -3.846500, 102.326500, 'JJ001'],
            [162, 'SMA NEGERI 6 KOTA BENGKULU', 'Teluk Segara', -3.783900, 102.262700, 'JJ003'],
            [163, 'SMA NEGERI 7 KOTA BENGKULU', 'Gading Cempaka', -3.835715, 102.297006, 'JJ003'],
            [164, 'SMA NEGERI 8 KOTA BENGKULU', 'Muara Bangkahulu', -3.756000, 102.290700, 'JJ003'],
            [165, 'SMA NEGERI 5 KOTA BENGKULU', 'Ratu Samban', -3.794600, 102.271800, 'JJ003'],
            [166, 'SMA NEGERI 4 KOTA BENGKULU', 'Singaran Pati', -3.818100, 102.308100, 'JJ003'],
            [167, 'SMA NEGERI 3 KOTA BENGKULU', 'Selebar', -3.845200, 102.320400, 'JJ003'],
            [168, 'SMA NEGERI 2 KOTA BENGKULU', 'Ratu Samban', -3.795419, 102.271359, 'JJ003'],
            [169, 'SMA NEGERI 1 KOTA BENGKULU', 'Ratu Agung', -3.822700, 102.280300, 'JJ003'],
            [170, 'SMAS SINT CAROLUS', 'Gading Cempaka', -3.828700, 102.295500, 'JJ003'],
            [171, 'SMAS PGRI', 'Ratu Agung', -3.791100, 102.276700, 'JJ003'],
            [172, 'SMAS PEMBANGUNAN', 'Ratu Samban', -3.794500, 102.261100, 'JJ003'],
            [173, 'SMAS PELITA KASIH', 'Gading Cempaka', -3.821001, 102.310804, 'JJ003'],
            [174, 'SMAS PANCASILA', 'Singaran Pati', -3.808100, 102.289300, 'JJ003'],
            [175, 'SMAS PALLAWA', 'Gading Cempaka', -3.825923, 102.308697, 'JJ003'],
            [176, 'SMAS MUHAMMADIYAH 4', 'Teluk Segara', -3.802000, 102.269800, 'JJ003'],
            [177, 'SMAS MUHAMMADIYAH 1', 'Muara Bangkahulu', -3.780400, 102.316400, 'JJ003'],
            [178, 'SMAS IDHATA', 'Ratu Agung', -3.812700, 102.278600, 'JJ003'],
            [179, 'SMAS HIDAYATULLAH', 'Sungai Serut', -3.785000, 102.321500, 'JJ003'],
            [180, 'SMAS BUDI MULIA', 'Selebar', -3.873500, 102.331500, 'JJ003'],
            [181, 'SMA NEGERI 9 KOTA BENGKULU', 'Muara Bangkahulu', -3.772000, 102.310500, 'JJ003'],
            [182, 'SMAN 10 BENGKULU', 'Selebar', -3.843300, 102.332000, 'JJ003'],
            [183, 'SMA IT IQRA', 'Ratu Agung', -3.794000, 102.285700, 'JJ003'],
            [184, 'SMA NEGERI 12 KEBERBAKATAN OLAHRAGA BENGKULU', 'Sungai Serut', -3.784700, 102.326500, 'JJ003'],
            [185, 'SMA NEGERI 11 KOTA BENGKULU', 'Kampung Melayu', -3.885700, 102.327800, 'JJ003'],
            [186, 'SMA BUDI UTOMO', 'Ratu Agung', -3.801900, 102.287200, 'JJ003'],
            [187, 'SMA IT MAHAD RABBANI 2 KOTA BENGKULU', 'Muara Bangkahulu', -3.771577, 102.312070, 'JJ003'],
            [188, 'SMAIT Al Hasanah', 'Selebar', -3.847500, 102.327500, 'JJ003'],
            [189, 'Sekolah Rakyat Menengah Atas 6 Bengkulu', 'Selebar', -3.832200, 102.325100, 'JJ003'],
            [190, 'SMKS 1 PEMBANGUNAN KOTA BENGKULU', 'Ratu Samban', -3.798400, 102.263500, 'JJ003'],
            [191, 'SMK SWASTA 10 TELEKOMUNIKASI BENGKULU', 'Gading Cempaka', -3.816300, 102.286400, 'JJ003'],
            [192, 'SMKS S 11 SERUNTING 2 BENGKULU', 'Gading Cempaka', -3.825500, 102.306500, 'JJ003'],
            [193, 'SMKS S 4 PGRI KOTA BENGKULU', 'Ratu Agung', -3.791200, 102.277100, 'JJ003'],
            [194, 'SMKS 3 SERUNTING 1 KOTA BENGKULU', 'Ratu Agung', -3.806203, 102.280022, 'JJ003'],
            [195, 'SMKS 13 YAMA KARYA BENGKULU', 'Ratu Agung', -3.848400, 102.358000, 'JJ003'],
            [196, 'SMK NEGERI 5 KOTA BENGKULU', 'Gading Cempaka', -3.827200, 102.296900, 'JJ003'],
            [197, 'SMK NEGERI 3 KOTA BENGKULU', 'Ratu Samban', -3.795600, 102.273100, 'JJ003'],
            [198, 'SMK NEGERI 2 KOTA BENGKULU', 'Gading Cempaka', -3.817900, 102.281500, 'JJ003'],
            [199, 'SMK NEGERI 1 KOTA BENGKULU', 'Ratu Samban', -3.795500, 102.272900, 'JJ003'],
            [200, 'SMKS 9 MUHAMMADIYAH BENGKULU', 'Ratu Agung', -3.802400, 102.269500, 'JJ003'],
            [201, 'SMK SWASTA 16 FARMASI BENGKULU', 'Ratu Agung', -3.814700, 102.282200, 'JJ003'],
            [202, 'SMKS 15 TARUNA INDONESIA BENGKULU', 'Gading Cempaka', -3.907700, 102.320800, 'JJ003'],
            [203, 'SMKS S 2 SEMARAK BENGKULU', 'Teluk Segara', -3.792100, 102.251200, 'JJ003'],
            [204, 'SMKS 17 BUDI MULIA', 'Selebar', -3.859800, 102.342300, 'JJ003'],
            [205, 'SMK NEGERI 4 KOTA BENGKULU', 'Sungai Serut', -3.779200, 102.264300, 'JJ003'],
            [206, 'SMK NEGERI 6 KOTA BENGKULU', 'Kampung Melayu', -3.919600, 102.311200, 'JJ003'],
            [207, 'SMKS 19 KESEHATAN BUDI MULIA KOTA BENGKULU', 'Selebar', -3.886700, 102.317900, 'JJ003'],
            [208, 'SMKS 18 AL-YASIR KOTA BENGKULU', 'Sungai Serut', -3.794800, 102.327700, 'JJ003'],
            [209, 'SMKS 21 ANALIS KESEHATAN QAWIY SHABAB', 'Selebar', -3.857300, 102.336600, 'JJ003'],
            [210, 'SMK NEGERI 7 KOTA BENGKULU', 'Ratu Agung', -3.824700, 102.279100, 'JJ003'],
            [211, 'SMK IT ARSITEK', 'Muara Bangkahulu', -3.775800, 102.326000, 'JJ003'],
            [212, 'SMKS AGRO MARITIM', 'Gading Cempaka', -3.832500, 102.308100, 'JJ003'],
            [213, 'SMK AGRIBISNIS DANGAU DATUK', 'Selebar', -3.845700, 102.348500, 'JJ003'],
            [214, 'SMP NEGERI 21 KOTA BENGKULU', 'Singaran Pati', -3.828500, 102.318500, 'JJ002'],
            [215, 'SMP NEGERI 20 KOTA BENGKULU', 'Selebar', -3.865200, 102.333400, 'JJ002'],
            [216, 'SMP NEGERI 02 KOTA BENGKULU', 'Ratu Samban', -3.813500, 102.273500, 'JJ002'],
            [217, 'SMP NEGERI 19 KOTA BENGKULU', 'Kampung Melayu', -3.915500, 102.325500, 'JJ002'],
            [218, 'SMP NEGERI 18 KOTA BENGKULU', 'Gading Cempaka', -3.826900, 102.299400, 'JJ002'],
            [219, 'SMP NEGERI 16 KOTA BENGKULU', 'Selebar', -3.876500, 102.348900, 'JJ002'],
            [220, 'SMP NEGERI 15 KOTA BENGKULU', 'Ratu Agung', -3.804700, 102.269500, 'JJ002'],
            [221, 'SMP NEGERI 14 KOTA BENGKULU', 'Singaran Pati', -3.829500, 102.319500, 'JJ002'],
            [222, 'SMP NEGERI 22 KOTA BENGKULU', 'Muara Bangkahulu', -3.765500, 102.276200, 'JJ002'],
            [223, 'SMP NEGERI 04 KOTA BENGKULU', 'Gading Cempaka', -3.819988, 102.292488, 'JJ002'],
            [224, 'SMP NEGERI 08 KOTA BENGKULU', 'Gading Cempaka', -3.833500, 102.294800, 'JJ002'],
            [225, 'SMP NEGERI 07 KOTA BENGKULU', 'Sungai Serut', -3.791500, 102.281500, 'JJ002'],
            [226, 'SMP NEGERI 06 KOTA BENGKULU', 'Singaran Pati', -3.830500, 102.320500, 'JJ002'],
            [227, 'SMP NEGERI 05 KOTA BENGKULU', 'Selebar', -3.846600, 102.320800, 'JJ002'],
            [228, 'SMP NEGERI 13 KOTA BENGKULU', 'Ratu Samban', -3.814500, 102.274500, 'JJ002'],
            [229, 'SMP BUDI MULIA KOTA BENGKULU', 'Selebar', -3.877300, 102.344800, 'JJ002'],
            [230, 'SMP NEGERI 03 KOTA BENGKULU', 'Teluk Segara', -3.790500, 102.260900, 'JJ002'],
            [231, 'SMPIT HIDAYATULLAH KOTA BENGKULU', 'Sungai Serut', -3.792500, 102.282500, 'JJ002'],
            [232, 'SMP IDHATA KOTA BENGKULU', 'Ratu Agung', -3.814500, 102.274500, 'JJ002'],
            [233, 'SMPIT IQRA KOTA BENGKULU', 'Teluk Segara', -3.788700, 102.263800, 'JJ002'],
            [234, 'SMP NEGERI 12 KOTA BENGKULU', 'Ratu Agung', -3.821000, 102.279300, 'JJ002'],
            [235, 'SMP NEGERI 11 KOTA BENGKULU', 'Muara Bangkahulu', -3.770800, 102.277500, 'JJ002'],
            [236, 'SMP NEGERI 10 KOTA BENGKULU', 'Sungai Serut', -3.793500, 102.283500, 'JJ002'],
            [237, 'SMP NEGERI 01 KOTA BENGKULU', 'Teluk Segara', -3.792200, 102.261400, 'JJ002'],
            [238, 'SMP SINT CAROLUS KOTA BENGKULU', 'Teluk Segara', -3.793300, 102.250200, 'JJ002'],
            [239, 'SMP SERUNTING 2 KOTA BENGKULU', 'Ratu Agung', -3.803400, 102.273000, 'JJ002'],
            [240, 'SMP SERUNTING 1 KOTA BENGKULU', 'Gading Cempaka', -3.826000, 102.308700, 'JJ002'],
            [241, 'SMP PGRI KOTA BENGKULU', 'Ratu Agung', -3.791200, 102.276600, 'JJ002'],
            [242, 'SMP PELITA KASIH KOTA BENGKULU', 'Gading Cempaka', -3.820900, 102.310400, 'JJ002'],
            [243, 'SMP PANCASILA KOTA BENGKULU', 'Singaran Pati', -3.831500, 102.321500, 'JJ002'],
            [244, 'SMP NEGERI 09 KOTA BENGKULU', 'Teluk Segara', -3.791800, 102.253000, 'JJ002'],
            [245, 'SMP NEGERI 23 (SATU ATAP) KOTA BENGKULU', 'Kampung Melayu', -3.916500, 102.326500, 'JJ002'],
            [246, 'SMP MUHAMMADIYAH TERPADU KOTA BENGKULU', 'Teluk Segara', -3.796500, 102.256500, 'JJ002'],
            [247, 'SMP NEGERI 17 KOTA BENGKULU', 'Muara Bangkahulu', -3.763300, 102.294800, 'JJ002'],
            [248, 'SMP NEGERI 24 KOTA BENGKULU', 'Selebar', -3.846800, 102.338200, 'JJ002'],
            [249, 'SMP ISLAM AL HASANAH KOTA BENGKULU', 'Selebar', -3.850200, 102.314500, 'JJ002'],
            [250, 'SMPIT KHAIRUNNAS KOTA BENGKULU', 'Gading Cempaka', -3.833200, 102.311300, 'JJ002'],
            [251, 'SMP ALKARIM KOTA BENGKULU', 'Muara Bangkahulu', -3.763500, 102.293500, 'JJ002'],
            [252, 'SMP ALAM MAHIRA KOTA BENGKULU', 'Ratu Agung', -3.803300, 102.282200, 'JJ002'],
            [253, 'SMPIT RABBANI KOTA BENGKULU', 'Ratu Agung', -3.805800, 102.283200, 'JJ002'],
            [254, 'SMP NEGERI 25 KOTA BENGKULU', 'Kampung Melayu', -3.917500, 102.327500, 'JJ002'],
            [255, 'SMPIT GENERASI RABBANI KOTA BENGKULU', 'Muara Bangkahulu', -3.778200, 102.274600, 'JJ002'],
            [256, 'SMPIT ISTIQOMAH KOTA BENGKULU', 'Ratu Agung', -3.815500, 102.275500, 'JJ002'],
            [257, 'SMP NURUL HUDA KOTA BENGKULU', 'Singaran Pati', -3.832500, 102.322500, 'JJ002'],
            [258, 'SMPIT MAHAD RABBANI 2 KOTA BENGKULU', 'Muara Bangkahulu', -3.764500, 102.294500, 'JJ002'],
            [259, 'SMP INSAN KAMIL ISLAMIC SCHOOL KOTA BENGKULU', 'Ratu Samban', -3.815500, 102.275500, 'JJ002'],
            [260, 'SMP PLUS JA-ALHAQ KOTA BENGKULU', 'Kampung Melayu', -3.918500, 102.328500, 'JJ002'],
            [261, 'SMPIT BAITUL IZZAH KOTA BENGKULU', 'Gading Cempaka', -3.823000, 102.286200, 'JJ002'],
            [262, 'SMP ISLAM AL AZHAR 52 KOTA BENGKULU', 'Singaran Pati', -3.833500, 102.323500, 'JJ002'],
            [263, 'SMPIT AS SYAKUR KOTA BENGKULU', 'Singaran Pati', -3.834500, 102.324500, 'JJ002'],
            [264, 'SMP FATMA KENANGA ISLAMIC CHARACTER SCHOOL KOTA BENGKULU', 'Ratu Agung', -3.816500, 102.276500, 'JJ002'],
            [265, 'SMPIT AL-MARJAN KOTA BENGKULU', 'Ratu Agung', -3.817500, 102.277500, 'JJ002'],
            [266, 'SMPIT HARAPAN DAN DOA KOTA BENGKULU', 'Ratu Samban', -3.816500, 102.276500, 'JJ002'],
            [267, 'SMP ISLAM AL YASIIR KOTA BENGKULU', 'Singaran Pati', -3.835500, 102.325500, 'JJ002'],
        ];

        $schoolsToInsert = [];
        foreach ($schoolsData as $row) {
            $schoolsToInsert[] = [
                'id' => $row[0],
                'nama_sekolah' => $row[1],
                'alamat' => 'Kec. ' . $row[2] . ', Kota Bengkulu',
                'latitude' => $row[3],
                'longitude' => $row[4],
                'id_jenjang' => $row[5],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach (array_chunk($schoolsToInsert, 50) as $chunk) {
            School::insert($chunk);
        }
    }
}
