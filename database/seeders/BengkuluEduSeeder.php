<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BengkuluEduSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. SEED SUBJECTS (Mata Pelajaran SMA/SMK Kota Bengkulu)
        $subjectsData = [
            [
                'kode_mapel' => 'SEJ',
                'nama_mapel' => 'Sejarah',
                'ikon_svg' => 'landmark',
                'warna_teks' => '#EA580C', // Orange
                'warna_bg_soft' => '#FFF7ED',
                'deskripsi' => 'Kajian sejarah kemerdekaan, masa kolonial Inggris-Belanda, dan situs cagar budaya Bengkulu.',
            ],
            [
                'kode_mapel' => 'BIO',
                'nama_mapel' => 'Biologi',
                'ikon_svg' => 'leaf',
                'warna_teks' => '#059669', // Emerald
                'warna_bg_soft' => '#ECFDF5',
                'deskripsi' => 'Observasi ekosistem alam, konservasi mangrove, keanekaragaman hayati, dan agrikultur hidroponik.',
            ],
            [
                'kode_mapel' => 'GEO',
                'nama_mapel' => 'Geografi',
                'ikon_svg' => 'globe',
                'warna_teks' => '#4F46E5', // Indigo
                'warna_bg_soft' => '#EEF2FF',
                'deskripsi' => 'Studi bentang alam pesisir, geomorfologi pantai, tata ruang wilayah, dan lingkungan hidup.',
            ],
            [
                'kode_mapel' => 'FIS',
                'nama_mapel' => 'Fisika',
                'ikon_svg' => 'zap',
                'warna_teks' => '#0891B2', // Cyan
                'warna_bg_soft' => '#ECFEFF',
                'deskripsi' => 'Penerapan mekanika fluida gelombang laut, aerodinamika angin pesisir, dan energi terbarukan.',
            ],
            [
                'kode_mapel' => 'EKO',
                'nama_mapel' => 'Ekonomi & Kewirausahaan',
                'ikon_svg' => 'coins',
                'warna_teks' => '#D97706', // Amber
                'warna_bg_soft' => '#FEF3C7',
                'deskripsi' => 'Pengamatan aktivitas UMKM kreatif, perdagangan jasa, agribisnis, dan pengelolaan usaha lokal.',
            ],
            [
                'kode_mapel' => 'SOS',
                'nama_mapel' => 'Sosiologi',
                'ikon_svg' => 'users',
                'warna_teks' => '#7C3AED', // Purple
                'warna_bg_soft' => '#F5F3FF',
                'deskripsi' => 'Interaksi sosial masyarakat, kearifan lokal, komunitas pemuda, dan dinamika ruang publik kota.',
            ],
            [
                'kode_mapel' => 'SEN',
                'nama_mapel' => 'Seni Budaya',
                'ikon_svg' => 'palette',
                'warna_teks' => '#DB2777', // Pink
                'warna_bg_soft' => '#FCE7F3',
                'deskripsi' => 'Apresiasi seni rupa, pertunjukan teater tari tradisional, arsitektur pusaka, dan tradisi Tabut.',
            ],
            [
                'kode_mapel' => 'LIT',
                'nama_mapel' => 'Bahasa & Literasi',
                'ikon_svg' => 'book-open',
                'warna_teks' => '#2563EB', // Blue
                'warna_bg_soft' => '#EFF6FF',
                'deskripsi' => 'Riset literatur, pengayaan kemampuan membaca, penulisan karya ilmiah, dan bedah referensi.',
            ],
            [
                'kode_mapel' => 'AGM',
                'nama_mapel' => 'Pendidikan Agama & Budi Pekerti',
                'ikon_svg' => 'book',
                'warna_teks' => '#0D9488', // Teal
                'warna_bg_soft' => '#F0FDFA',
                'deskripsi' => 'Pembelajaran akhlak mulia, sejarah peradaban Islam di Bengkulu, dan studi arsitektur religi.',
            ],
            [
                'kode_mapel' => 'PJK',
                'nama_mapel' => 'Penjasorkes & Olahraga',
                'ikon_svg' => 'activity',
                'warna_teks' => '#DC2626', // Red
                'warna_bg_soft' => '#FEF2F2',
                'deskripsi' => 'Praktik kebugaran jasmani, aktivitas atletik luar ruang, olahraga permainan, dan pola hidup sehat.',
            ],
            [
                'kode_mapel' => 'TIK',
                'nama_mapel' => 'Informatika & TIK',
                'ikon_svg' => 'cpu',
                'warna_teks' => '#475569', // Slate
                'warna_bg_soft' => '#F8FAFC',
                'deskripsi' => 'Pemanfaatan teknologi kota cerdas (Smart City), literasi digital, dan sistem informasi publik.',
            ],
        ];

        $subjectModels = [];
        foreach ($subjectsData as $s) {
            $subjectModels[$s['kode_mapel']] = Subject::updateOrCreate(['kode_mapel' => $s['kode_mapel']], $s);
        }

        // 2. READ CSV AND SEED 50 PLACES
        $csvPath = database_path('data/tempat_belajar.csv');
        if (!file_exists($csvPath)) {
            return;
        }

        // Mapping rules from ID or keywords to subjects
        $subjectMappingRules = [
            'TP001' => ['SEJ' => 98, 'SOS' => 88, 'SEN' => 85], // Benteng Marlborough
            'TP002' => ['BIO' => 98, 'GEO' => 95, 'FIS' => 85], // Mangrove Pulau Baai
            'TP003' => ['SEJ' => 95, 'SEN' => 95, 'SOS' => 88], // Museum Bengkulu
            'TP004' => ['SEJ' => 99, 'LIT' => 90, 'SOS' => 86], // Rumah Bung Karno
            'TP005' => ['LIT' => 99, 'SEJ' => 85, 'TIK' => 82], // Dinas Perpustakaan Provinsi
            'TP006' => ['BIO' => 88, 'GEO' => 92, 'FIS' => 90, 'PJK' => 92], // Pantai Panjang
            'TP007' => ['BIO' => 95, 'EKO' => 92], // Toboponik
            'TP008' => ['LIT' => 95, 'SEJ' => 80], // Perpustakaan Kota
            'TP009' => ['LIT' => 92, 'SOS' => 85], // Tjahaja Hati
            'TP010' => ['LIT' => 90, 'TIK' => 80], // Perpus SMAN 5
            'TP011' => ['LIT' => 96, 'TIK' => 88], // Perpus UNIB
            'TP012' => ['LIT' => 94, 'EKO' => 85], // Gramedia Meranti
            'TP013' => ['LIT' => 90, 'EKO' => 82], // TB Samudra Jaya
            'TP014' => ['LIT' => 88, 'EKO' => 80], // Magek Persada
            'TP015' => ['LIT' => 88, 'EKO' => 80], // TB Bengkulu
            'TP016' => ['SEJ' => 98, 'SEN' => 88, 'SOS' => 85], // Rumah Ibu Fatmawati
            'TP017' => ['SEJ' => 94, 'SOS' => 80], // Tugu Thomas Parr
            'TP018' => ['SEJ' => 90, 'LIT' => 88], // Tugu Pers Bengkulu
            'TP019' => ['AGM' => 98, 'SEN' => 88, 'SEJ' => 85], // Masjid Raya Baitul Izzah
            'TP020' => ['SOS' => 90, 'BIO' => 82, 'PJK' => 80], // Taman Remaja
            'TP021' => ['TIK' => 96, 'SOS' => 85], // Taman Smart City
            'TP022' => ['GEO' => 88, 'BIO' => 85, 'SOS' => 82], // Taman Pantai Berkas
            'TP023' => ['SOS' => 85, 'GEO' => 80], // Taman Simpang Kandis
            'TP024' => ['SEN' => 98, 'SOS' => 90, 'SEJ' => 85], // Taman Budaya Provinsi
            'TP025' => ['SEN' => 95, 'SOS' => 86], // Taman Seni Budaya
            'TP026' => ['SOS' => 85, 'BIO' => 80], // RTH Bentiring
            'TP027' => ['SOS' => 90, 'SEJ' => 82, 'PJK' => 80], // Alun-Alun Kota
            'TP028' => ['BIO' => 88, 'GEO' => 82], // Taman Wisata Family
            'TP029' => ['LIT' => 88, 'SOS' => 88], // Belungguk Point
            'TP030' => ['SOS' => 82], // Taman Simpang Skip
            'TP031' => ['SEJ' => 92, 'GEO' => 90, 'FIS' => 85], // Pantai Tapak Paderi
            'TP032' => ['BIO' => 94, 'GEO' => 86, 'EKO' => 80], // Wisata Edukasi Queen
            'TP033' => ['BIO' => 92, 'EKO' => 88], // Nusantara Farm
            'TP034' => ['EKO' => 88, 'SOS' => 86, 'LIT' => 82], // Node Coffee
            'TP035' => ['EKO' => 88, 'SOS' => 86, 'LIT' => 85], // Eternal Social Space
            'TP036' => ['EKO' => 86, 'SOS' => 84, 'LIT' => 82], // Trilogi Coffee
            'TP037' => ['EKO' => 85, 'SOS' => 84, 'LIT' => 80], // Larzo Coffee
            'TP038' => ['EKO' => 85, 'SOS' => 82, 'LIT' => 82], // Huma Coffee
            'TP039' => ['EKO' => 86, 'SOS' => 88, 'LIT' => 84], // NGOPI.ID UNIB
            'TP040' => ['EKO' => 84, 'SOS' => 82], // Bika Coffee
            'TP041' => ['EKO' => 85, 'SOS' => 84, 'LIT' => 80], // Nusa Coffee
            'TP042' => ['EKO' => 84, 'SOS' => 82], // Kopi Konnichiwa
            'TP043' => ['EKO' => 88, 'SOS' => 88, 'LIT' => 85], // TENTANG KOPI x EDU 86
            'TP044' => ['EKO' => 84, 'SOS' => 82], // BUKOMIE
            'TP045' => ['SOS' => 86, 'LIT' => 82], // Rumah Kenzie
            'TP046' => ['SOS' => 84, 'PJK' => 82], // Playodania
            'TP047' => ['SOS' => 84, 'PJK' => 80], // Big Planet
            'TP048' => ['SOS' => 82, 'EKO' => 80], // Superland Mega Mall
            'TP049' => ['SOS' => 82, 'EKO' => 80], // Happy Kiddy
            'TP050' => ['PJK' => 98, 'SOS' => 85], // Lapangan Basket Nusa Indah
        ];

        // Truncate old locations and pivots to cleanly import all 50 places
        \Illuminate\Support\Facades\DB::table('location_subject')->delete();
        Location::query()->delete();

        $handle = fopen($csvPath, 'r');
        $header = fgetcsv($handle); // Read header

        $locationsCount = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (empty($row)) {
                continue;
            }
            // If the row was enclosed in outer quotes, parse inner CSV
            if (count($row) === 1 && !empty($row[0])) {
                $row = str_getcsv($row[0]);
            }
            if (count($row) < 5) {
                continue;
            }

            // CSV Columns:
            // 0: id_tempat (e.g. TP001)
            // 1: nama_tempat
            // 2: alamat
            // 3: des
            // 4: latitude
            // 5: longitude
            // 6: foto
            // 7: Biaya Tiket Masuk / Estimasi Tarif
            // 8: Jam Operasional

            $idTempat = trim($row[0]);
            $namaTempat = trim($row[1]);
            $alamat = trim($row[2]);
            $deskripsi = trim($row[3]);
            $lat = (float) trim($row[4]);
            $lng = (float) trim($row[5]);
            $foto = isset($row[6]) && !empty(trim($row[6])) ? trim($row[6]) : 'default.jpg';
            $biaya = isset($row[7]) && !empty(trim($row[7])) ? trim($row[7]) : 'Gratis';
            $jamOperasional = isset($row[8]) && !empty(trim($row[8])) ? trim($row[8]) : 'Terbuka 24 Jam';
            // clean ? to -
            $jamOperasional = str_replace('?', ' - ', $jamOperasional);
            $status = 'Buka';

            $slug = Str::slug($namaTempat);

            // Calculate base facility score C3 and safety score C4
            $fasilitasSkor = 85;
            $keamananSkor = 88;

            if (stripos($biaya, 'gratis') !== false) {
                $fasilitasSkor = 84;
            } else {
                $fasilitasSkor = 90;
            }

            // High profile heritage/museums get higher scores
            if (in_array($idTempat, ['TP001', 'TP003', 'TP004', 'TP005', 'TP011', 'TP016', 'TP019', 'TP024'])) {
                $fasilitasSkor = 94;
                $keamananSkor = 95;
            }

            $loc = Location::updateOrCreate(
                ['id_tempat' => $idTempat],
                [
                    'nama_lokasi' => $namaTempat,
                    'slug' => $slug,
                    'alamat_kawasan' => $alamat,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'biaya' => $biaya,
                    'deskripsi' => $deskripsi,
                    'jam_operasional' => $jamOperasional,
                    'foto' => $foto,
                    'status' => $status,
                    'kondisi_cuaca' => 'Cerah',
                    'kecepatan_angin_knot' => 10,
                    'arah_angin' => 'Barat Daya',
                    'tinggi_gelombang_meter' => 1.0,
                    'tingkat_risiko' => 'Rendah',
                    'skor_fasilitas_c3' => $fasilitasSkor,
                    'skor_keamanan_c4' => $keamananSkor,
                ]
            );

            // Attach mapped subjects
            $attachData = [];
            if (isset($subjectMappingRules[$idTempat])) {
                foreach ($subjectMappingRules[$idTempat] as $kode => $skorC1) {
                    if (isset($subjectModels[$kode])) {
                        $attachData[$subjectModels[$kode]->id] = [
                            'skor_relevansi_c1' => $skorC1,
                            'topik_kajian' => 'Materi kajian ' . $subjectModels[$kode]->nama_mapel . ' di ' . $namaTempat,
                        ];
                    }
                }
            } else {
                // Fallback default mapping to Sejarah & Sosiologi
                if (isset($subjectModels['SEJ'])) {
                    $attachData[$subjectModels['SEJ']->id] = [
                        'skor_relevansi_c1' => 80,
                        'topik_kajian' => 'Studi edukasi sejarah dan peradaban lokal',
                    ];
                }
            }

            if (!empty($attachData)) {
                $loc->subjects()->sync($attachData);
            }

            $locationsCount++;
        }

        fclose($handle);
    }
}
