<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CoastalBengkuluSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. SEED SUBJECTS
        $subjectsData = [
            [
                'kode_mapel' => 'BIO',
                'nama_mapel' => 'Biologi (Ekosistem Pesisir)',
                'ikon_svg' => 'leaf',
                'warna_teks' => '#059669', // Emerald
                'warna_bg_soft' => '#ECFDF5',
                'deskripsi' => 'Studi flora & fauna estuari, mangrove, dan rantai makanan ekosistem laut pesisir.',
            ],
            [
                'kode_mapel' => 'GEO',
                'nama_mapel' => 'Geografi (Geomorfologi & Abrasi)',
                'ikon_svg' => 'globe',
                'warna_teks' => '#4F46E5', // Indigo
                'warna_bg_soft' => '#EEF2FF',
                'deskripsi' => 'Analisis geomorfologi pantai, sedimentasi pesisir, dan erosi/abrasi laut Samudra Hindia.',
            ],
            [
                'kode_mapel' => 'SEJ',
                'nama_mapel' => 'Sejarah (Kolonial & Maritim)',
                'ikon_svg' => 'landmark',
                'warna_teks' => '#EA580C', // Orange
                'warna_bg_soft' => '#FFF7ED',
                'deskripsi' => 'Eksplorasi jejak kolonial Inggris, benteng pertahanan maritim, dan jalur rempah nusantara.',
            ],
            [
                'kode_mapel' => 'FIS',
                'nama_mapel' => 'Fisika (Gelombang & Angin)',
                'ikon_svg' => 'wave',
                'warna_teks' => '#0891B2', // Cyan
                'warna_bg_soft' => '#ECFEFF',
                'deskripsi' => 'Mekanika gelombang fluida, tekanan angin pesisir, dan pasang surut air laut.',
            ],
            [
                'kode_mapel' => 'EKO',
                'nama_mapel' => 'Ekonomi (UMKM & Perikanan)',
                'ikon_svg' => 'coins',
                'warna_teks' => '#D97706', // Amber
                'warna_bg_soft' => '#FEF3C7',
                'deskripsi' => 'Rantai pasok perikanan tangkap, pelelangan ikan (TPI), dan ekonomi mikro UMKM pesisir.',
            ],
            [
                'kode_mapel' => 'SOS',
                'nama_mapel' => 'Sosiologi (Masyarakat Nelayan)',
                'ikon_svg' => 'users',
                'warna_teks' => '#7C3AED', // Purple
                'warna_bg_soft' => '#F5F3FF',
                'deskripsi' => 'Dinamika sosial komunitas nelayan, kearifan lokal pesisir, dan adaptasi perubahan iklim.',
            ],
            [
                'kode_mapel' => 'SEN',
                'nama_mapel' => 'Seni Budaya',
                'ikon_svg' => 'palette',
                'warna_teks' => '#DB2777', // Pink
                'warna_bg_soft' => '#FCE7F3',
                'deskripsi' => 'Ekspresi tradisi tabot pesisir, arsitektur vernakular maritim, dan kerajinan ornamen laut.',
            ],
        ];

        $subjectMap = [];
        foreach ($subjectsData as $s) {
            $created = Subject::updateOrCreate(['kode_mapel' => $s['kode_mapel']], $s);
            $subjectMap[$s['kode_mapel']] = $created->id;
        }

        // 2. SEED LOCATIONS
        $locationsData = [
            [
                'nama_lokasi' => 'Pantai Panjang (Zona Sport Center)',
                'slug' => 'pantai-panjang-sport-center',
                'alamat_kawasan' => 'Jl. Pariwisata, Kel. Lempuing, Kec. Ratu Agung, Kota Bengkulu',
                'latitude' => -3.8242000,
                'longitude' => 102.2685000,
                'deskripsi' => 'Garis pantai pasir putih membentang 7 km dengan bentukan gumuk pasir unik, pusat aktivitas olahraga, serta interaksi UMKM kuliner laut.',
                'jam_operasional' => '06:00 - 19:00 WIB',
                'kondisi_cuaca' => 'Cerah',
                'kecepatan_angin_knot' => 12,
                'arah_angin' => 'Barat Daya',
                'tinggi_gelombang_meter' => 1.20,
                'tingkat_risiko' => 'Rendah',
                'skor_fasilitas_c3' => 92,
                'skor_keamanan_c4' => 85,
                'subjects' => [
                    ['kode' => 'GEO', 'c1' => 90, 'topik' => 'Geomorfologi Gumuk Pasir & Sedimentasi Garis Pantai'],
                    ['kode' => 'EKO', 'c1' => 95, 'topik' => 'Ekonomi Wisata Pesisir & Sentra UMKM Kuliner Tradisional'],
                    ['kode' => 'SOS', 'c1' => 88, 'topik' => 'Interaksi Sosial Wisatawan dan Komunitas Pedagang Lokal'],
                ],
            ],
            [
                'nama_lokasi' => 'Hutan Mangrove Muara Jenggalu (Pulau Baai)',
                'slug' => 'mangrove-muara-jenggalu',
                'alamat_kawasan' => 'Kawasan Konservasi Pulau Baai, Kel. Sumber Jaya, Kec. Kampung Melayu, Kota Bengkulu',
                'latitude' => -3.9056000,
                'longitude' => 102.2989000,
                'deskripsi' => 'Ekosistem hutan bakau alami terlindung dengan keanekaragaman avifauna, krustasea, serta bentang estuari pertemuan air tawar dan laut.',
                'jam_operasional' => '08:00 - 17:00 WIB',
                'kondisi_cuaca' => 'Berawan',
                'kecepatan_angin_knot' => 8,
                'arah_angin' => 'Selatan',
                'tinggi_gelombang_meter' => 0.60,
                'tingkat_risiko' => 'Rendah',
                'skor_fasilitas_c3' => 78,
                'skor_keamanan_c4' => 90,
                'subjects' => [
                    ['kode' => 'BIO', 'c1' => 98, 'topik' => 'Konservasi Mangrove, Suksesi Vegetasi & Biota Estuari'],
                    ['kode' => 'GEO', 'c1' => 92, 'topik' => 'Zonasi Sedimen Estuari & Sistem Filtrasi Alami Pesisir'],
                ],
            ],
            [
                'nama_lokasi' => 'Benteng Marlborough (Kawasan Tapak Paderi)',
                'slug' => 'benteng-marlborough',
                'alamat_kawasan' => 'Jl. Benteng, Kel. Kebun Keling, Kec. Teluk Segara, Kota Bengkulu',
                'latitude' => -3.7885000,
                'longitude' => 102.2505000,
                'deskripsi' => 'Benteng pertahanan peninggalan East India Company (EIC) Inggris abad ke-18 terbesar di Asia Tenggara, terletak di tepi tebing pesisir Samudra Hindia.',
                'jam_operasional' => '08:00 - 17:30 WIB',
                'kondisi_cuaca' => 'Cerah',
                'kecepatan_angin_knot' => 10,
                'arah_angin' => 'Barat Laut',
                'tinggi_gelombang_meter' => 0.80,
                'tingkat_risiko' => 'Rendah',
                'skor_fasilitas_c3' => 95,
                'skor_keamanan_c4' => 95,
                'subjects' => [
                    ['kode' => 'SEJ', 'c1' => 98, 'topik' => 'Arsitektur Militer Kolonial EIC & Geopolitik Maritim Abad 18'],
                    ['kode' => 'SEN', 'c1' => 94, 'topik' => 'Estetika Arsitektur Bata Kuno & Tata Ruang Cagar Budaya Kolonial'],
                ],
            ],
            [
                'nama_lokasi' => 'Pantai Tapak Paderi & Pondok Besi',
                'slug' => 'pantai-tapak-paderi',
                'alamat_kawasan' => 'Kel. Pondok Besi & Kebun Keling, Kec. Teluk Segara, Kota Bengkulu',
                'latitude' => -3.7924000,
                'longitude' => 102.2530000,
                'deskripsi' => 'Bekas dermaga pendaratan laut Inggris bersejarah, dilengkapi revetment pemecah gelombang andesit untuk meredam dinamika laut Samudra Hindia.',
                'jam_operasional' => '06:00 - 18:30 WIB',
                'kondisi_cuaca' => 'Cerah Berawan',
                'kecepatan_angin_knot' => 14,
                'arah_angin' => 'Barat Daya',
                'tinggi_gelombang_meter' => 1.50,
                'tingkat_risiko' => 'Sedang',
                'skor_fasilitas_c3' => 80,
                'skor_keamanan_c4' => 78,
                'subjects' => [
                    ['kode' => 'FIS', 'c1' => 92, 'topik' => 'Dinamika Pasang Surut & Hidrodinamika Pemecah Gelombang (Breakwater)'],
                    ['kode' => 'SEJ', 'c1' => 86, 'topik' => 'Dermaga Kuno Pendaratan Kapal Inggris & Monumen Thomas Parr'],
                    ['kode' => 'GEO', 'c1' => 88, 'topik' => 'Transpor Sedimen Teluk & Pengendalian Erosi Tepi Pantai'],
                ],
            ],
            [
                'nama_lokasi' => 'Kampung Nelayan & TPI Pulau Baai',
                'slug' => 'kampung-nelayan-tpi-pulau-baai',
                'alamat_kawasan' => 'Kawasan Pelabuhan Samudra Pulau Baai, Kec. Kampung Melayu, Kota Bengkulu',
                'latitude' => -3.9168000,
                'longitude' => 102.3120000,
                'deskripsi' => 'Sentra perikanan tangkap terbesar di Bengkulu dengan aktivitas lelang ikan harian, cold storage, galangan kapal tradisional, dan komunitas nelayan bahari.',
                'jam_operasional' => '05:00 - 16:00 WIB',
                'kondisi_cuaca' => 'Berawan',
                'kecepatan_angin_knot' => 11,
                'arah_angin' => 'Tenggara',
                'tinggi_gelombang_meter' => 0.90,
                'tingkat_risiko' => 'Rendah',
                'skor_fasilitas_c3' => 82,
                'skor_keamanan_c4' => 80,
                'subjects' => [
                    ['kode' => 'EKO', 'c1' => 96, 'topik' => 'Sistem Rantai Dingin, Pelelangan Ikan (TPI) & Tataniaga Maritim'],
                    ['kode' => 'SOS', 'c1' => 95, 'topik' => 'Struktur Patron-Klien Nelayan, Pola Kerja Punggawa-Sawi'],
                    ['kode' => 'BIO', 'c1' => 90, 'topik' => 'Taksonomi Ikan Tangkapan Komersial & Biologi Perikanan Laut'],
                ],
            ],
            [
                'nama_lokasi' => 'Pantai Jakat',
                'slug' => 'pantai-jakat',
                'alamat_kawasan' => 'Kel. Bajak, Kec. Teluk Segara, Kota Bengkulu',
                'latitude' => -3.7801000,
                'longitude' => 102.2562000,
                'deskripsi' => 'Teluk berpasir landai dengan gelombang tenang yang menjadi sentra rekreasi air, persewaan wahana air, dan warung olahan hasil laut lokal.',
                'jam_operasional' => '06:00 - 18:00 WIB',
                'kondisi_cuaca' => 'Cerah',
                'kecepatan_angin_knot' => 13,
                'arah_angin' => 'Barat Laut',
                'tinggi_gelombang_meter' => 1.00,
                'tingkat_risiko' => 'Rendah',
                'skor_fasilitas_c3' => 86,
                'skor_keamanan_c4' => 88,
                'subjects' => [
                    ['kode' => 'FIS', 'c1' => 90, 'topik' => 'Mekanika Gelombang Air Dangkal & Gaya Apung Wahana Air'],
                    ['kode' => 'EKO', 'c1' => 85, 'topik' => 'Model Bisnis Persewaan Wisata Air & Ekonomi Kreatif Pesisir'],
                    ['kode' => 'SOS', 'c1' => 82, 'topik' => 'Karakteristik Rekreasi Publik & Budaya Pelesir Warga Perkotaan'],
                ],
            ],
            [
                'nama_lokasi' => 'Kawasan Abrasi Pantai Pasar Bengkulu & Sungai Suci',
                'slug' => 'abrasi-pasar-bengkulu',
                'alamat_kawasan' => 'Kel. Pasar Bengkulu, Kec. Sungai Serut, Kota Bengkulu',
                'latitude' => -3.7592000,
                'longitude' => 102.2470000,
                'deskripsi' => 'Laboratorium alami proses abrasi ekstrem di mana tebing batuan terkikis gelombang Samudra Hindia langsung, dilengkapi struktur geobag pengaman darurat.',
                'jam_operasional' => '07:00 - 17:30 WIB',
                'kondisi_cuaca' => 'Hujan Ringan',
                'kecepatan_angin_knot' => 18,
                'arah_angin' => 'Barat',
                'tinggi_gelombang_meter' => 2.20,
                'tingkat_risiko' => 'Tinggi',
                'skor_fasilitas_c3' => 70,
                'skor_keamanan_c4' => 65,
                'subjects' => [
                    ['kode' => 'FIS', 'c1' => 94, 'topik' => 'Energi Kinetik Gelombang Penghancur & Analisis Arus Susur Pantai'],
                    ['kode' => 'GEO', 'c1' => 96, 'topik' => 'Proses Abrasi Tebing Pantai & Desain Rekayasa Mitigasi Erosi'],
                    ['kode' => 'BIO', 'c1' => 80, 'topik' => 'Dampak Hilangnya Habitat Terestrial Akibat Erosi Garis Pantai'],
                ],
            ],
            [
                'nama_lokasi' => 'Kawasan Pecinan / Kampung Cina Pesisir',
                'slug' => 'kampung-cina-pesisir',
                'alamat_kawasan' => 'Jl. Ahmad Yani, Kel. Malabero, Kec. Teluk Segara, Kota Bengkulu',
                'latitude' => -3.7905000,
                'longitude' => 102.2522000,
                'deskripsi' => 'Kawasan permukiman niaga bersejarah sejak era kolonial Inggris, memiliki ruko antik khas Tionghoa-Melayu dan Klenteng Shu-Cing-Gong tertua di Bengkulu.',
                'jam_operasional' => '08:00 - 20:00 WIB',
                'kondisi_cuaca' => 'Cerah',
                'kecepatan_angin_knot' => 9,
                'arah_angin' => 'Barat Laut',
                'tinggi_gelombang_meter' => 0.70,
                'tingkat_risiko' => 'Rendah',
                'skor_fasilitas_c3' => 84,
                'skor_keamanan_c4' => 92,
                'subjects' => [
                    ['kode' => 'SEJ', 'c1' => 95, 'topik' => 'Jalur Perdagangan Candu-Lada & Akulturasi Tionghoa-Melayu Abad 19'],
                    ['kode' => 'EKO', 'c1' => 88, 'topik' => 'Evolusi Kawasan Ruko Komersial Masa Kolonial ke Era Modern'],
                    ['kode' => 'SEN', 'c1' => 92, 'topik' => 'Ornamen Arsitektur Klenteng Shu-Cing-Gong & Tipologi Bangunan Indis'],
                ],
            ],
        ];

        foreach ($locationsData as $item) {
            $subjects = $item['subjects'];
            unset($item['subjects']);

            $location = Location::updateOrCreate(['slug' => $item['slug']], $item);

            $attachData = [];
            foreach ($subjects as $sb) {
                if (isset($subjectMap[$sb['kode']])) {
                    $attachData[$subjectMap[$sb['kode']]] = [
                        'skor_relevansi_c1' => $sb['c1'],
                        'topik_kajian' => $sb['topik'],
                    ];
                }
            }
            $location->subjects()->sync($attachData);
        }
    }
}
