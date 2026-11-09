<?php

namespace App\Services;

use App\Models\Location;
use App\Models\Subject;

class RecommendationService
{
    /**
     * Bengkulu reference schools for quick departure points.
     */
    public const DEPARTURE_PRESETS = [
        [
            'id' => 'sman1',
            'name' => 'SMAN 1 Kota Bengkulu',
            'lat' => -3.7915000,
            'lng' => 102.2610000,
            'address' => 'Jl. Jend. Sudirman No. 1, Pintu Batu, Kec. Teluk Segara',
        ],
        [
            'id' => 'sman2',
            'name' => 'SMAN 2 Kota Bengkulu',
            'lat' => -3.8005000,
            'lng' => 102.2750000,
            'address' => 'Jl. Mahoni No. 14, Padang Jati, Kec. Ratu Samban',
        ],
        [
            'id' => 'sman3',
            'name' => 'SMAN 3 Kota Bengkulu',
            'lat' => -3.8560000,
            'lng' => 102.3270000,
            'address' => 'Jl. RE Martadinata No. 34, Pagar Dewa',
        ],
        [
            'id' => 'sman4',
            'name' => 'SMAN 4 Kota Bengkulu',
            'lat' => -3.8220000,
            'lng' => 102.2980000,
            'address' => 'Jl. Zainul Arifin, Singaran Pati',
        ],
        [
            'id' => 'sman5',
            'name' => 'SMAN 5 Kota Bengkulu',
            'lat' => -3.8055000,
            'lng' => 102.2715000,
            'address' => 'Jl. Cendana No. 20, Sawah Lebar, Kec. Ratu Samban',
        ],
        [
            'id' => 'sman6',
            'name' => 'SMAN 6 Kota Bengkulu',
            'lat' => -3.7490000,
            'lng' => 102.2960000,
            'address' => 'Jl. Semarak No. 21, Bentiring, Kec. Muara Bangka Hulu',
        ],
        [
            'id' => 'sman7',
            'name' => 'SMAN 7 Kota Bengkulu',
            'lat' => -3.8290000,
            'lng' => 102.3080000,
            'address' => 'Jl. Sadang, Lingkar Barat, Kec. Gading Cempaka',
        ],
        [
            'id' => 'sman8',
            'name' => 'SMAN 8 Kota Bengkulu',
            'lat' => -3.7550000,
            'lng' => 102.2730000,
            'address' => 'Jl. WR Supratman, Kandang Limun, Kec. Muara Bangka Hulu',
        ],
        [
            'id' => 'sman9',
            'name' => 'SMAN 9 Kota Bengkulu',
            'lat' => -3.9050000,
            'lng' => 102.3160000,
            'address' => 'Jl. RE Martadinata, Kandang Mas, Kec. Kampung Melayu',
        ],
        [
            'id' => 'sman10',
            'name' => 'SMAN 10 Kota Bengkulu',
            'lat' => -3.7680000,
            'lng' => 102.2850000,
            'address' => 'Jl. Hibrida, Kec. Gading Cempaka',
        ],
        [
            'id' => 'smkn1',
            'name' => 'SMKN 1 Kota Bengkulu',
            'lat' => -3.8040000,
            'lng' => 102.2740000,
            'address' => 'Jl. Jati No. 40, Sawah Lebar',
        ],
        [
            'id' => 'smkn2',
            'name' => 'SMKN 2 Kota Bengkulu',
            'lat' => -3.8180000,
            'lng' => 102.2910000,
            'address' => 'Jl. Ciliwung, Padang Harapan',
        ],
        [
            'id' => 'smkn3',
            'name' => 'SMKN 3 Kota Bengkulu',
            'lat' => -3.7920000,
            'lng' => 102.2620000,
            'address' => 'Jl. Jend. Sudirman No. 2, Pintu Batu',
        ],
        [
            'id' => 'man1',
            'name' => 'MAN 1 Kota Bengkulu',
            'lat' => -3.8160000,
            'lng' => 102.2890000,
            'address' => 'Jl. Cimanuk No. 28, Padang Harapan',
        ],
        [
            'id' => 'man2',
            'name' => 'MAN 2 Kota Bengkulu',
            'lat' => -3.8010000,
            'lng' => 102.2760000,
            'address' => 'Jl. Mahoni No. 16, Padang Jati',
        ],
    ];

    /**
     * Calculate Haversine distance between two coordinates in Kilometers.
     */
    public function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadiusKm = 6371.0;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadiusKm * $c, 3);
    }

    /**
     * Main recommendation pipeline.
     *
     * @param array $subjectIds Array of chosen subject IDs
     * @param float $originLat Latitude of starting school/point
     * @param float $originLng Longitude of starting school/point
     * @param string $originName Name of starting point
     * @param array $weights [c1 => float, c2 => float, c3 => float, c4 => float]
     * @return array
     */
    public function getRecommendations(
        array $subjectIds,
        float $originLat,
        float $originLng,
        string $originName = 'Titik Keberangkatan',
        array $weights = []
    ): array {
        // Normalize weights (default: C1=0.35, C2=0.25, C3=0.20, C4=0.20)
        $w1 = isset($weights['c1']) ? (float)$weights['c1'] : 0.35;
        $w2 = isset($weights['c2']) ? (float)$weights['c2'] : 0.25;
        $w3 = isset($weights['c3']) ? (float)$weights['c3'] : 0.20;
        $w4 = isset($weights['c4']) ? (float)$weights['c4'] : 0.20;

        $totalW = $w1 + $w2 + $w3 + $w4;
        if ($totalW <= 0) {
            $w1 = 0.35; $w2 = 0.25; $w3 = 0.20; $w4 = 0.20;
            $totalW = 1.0;
        }

        $w = [
            'c1' => round($w1 / $totalW, 4),
            'c2' => round($w2 / $totalW, 4),
            'c3' => round($w3 / $totalW, 4),
            'c4' => round($w4 / $totalW, 4),
        ];

        // Fetch selected subjects
        $subjects = Subject::whereIn('id', $subjectIds)->get();
        if ($subjects->isEmpty()) {
            // Default to first subject if none specified
            $firstSubject = Subject::first();
            if ($firstSubject) {
                $subjects = collect([$firstSubject]);
                $subjectIds = [$firstSubject->id];
            }
        }

        $subjectCount = count($subjectIds);

        // ======================================================================
        // SKENARIO 1A: Lokasi tunggal yang memenuhi SEMUA mata pelajaran terpilih
        // ======================================================================
        if ($subjectCount > 1) {
            $allMatchQuery = Location::with('subjects');
            foreach ($subjectIds as $sId) {
                $allMatchQuery->whereHas('subjects', function ($q) use ($sId) {
                    $q->where('subjects.id', $sId);
                });
            }
            $fullMatchLocations = $allMatchQuery->get();

            if ($fullMatchLocations->isNotEmpty()) {
                return $this->processSingleMatchScenario(
                    $fullMatchLocations, $subjects, $originLat, $originLng, $originName, $w
                );
            }
        }


        // ======================================================================
        // SKENARIO 1 (single-subject only): Cek lokasi yang memenuhi satu mapel
        // ======================================================================
        if ($subjectCount === 1) {
            $singleQuery = Location::with('subjects');
            foreach ($subjectIds as $sId) {
                $singleQuery->whereHas('subjects', function ($q) use ($sId) {
                    $q->where('subjects.id', $sId);
                });
            }
            $singleMatchLocations = $singleQuery->get();

            if ($singleMatchLocations->isNotEmpty()) {
                return $this->processSingleMatchScenario(
                    $singleMatchLocations, $subjects, $originLat, $originLng, $originName, $w
                );
            }
        }

        // ======================================================================
        // SKENARIO 2: Tidak ada lokasi tunggal memadai — rekomendasi paket berdekatan
        // ======================================================================
        return $this->processMultiLocationNearbyScenario(
            $subjectIds, $subjects, $originLat, $originLng, $originName, $w
        );
    }

    /**
     * Process Scenario 1: Single Location Matching all subjects.
     */
    protected function processSingleMatchScenario(
        $locations,
        $subjects,
        float $originLat,
        float $originLng,
        string $originName,
        array $weights
    ): array {
        $subjectIds = $subjects->pluck('id')->toArray();

        // 1. Matriks Keputusan X
        $rawMatrix = [];
        $candidates = [];

        foreach ($locations as $loc) {
            // C1: Relevansi (rata-rata skor relevansi subjek yang dipilih)
            $c1Values = [];
            $topics = [];
            foreach ($loc->subjects as $sub) {
                if (in_array($sub->id, $subjectIds)) {
                    $c1Values[] = (float)$sub->pivot->skor_relevansi_c1;
                    $topics[] = [
                        'kode' => $sub->kode_mapel,
                        'nama' => $sub->nama_mapel,
                        'warna_teks' => $sub->warna_teks,
                        'warna_bg_soft' => $sub->warna_bg_soft,
                        'topik' => $sub->pivot->topik_kajian,
                        'skor_c1' => $sub->pivot->skor_relevansi_c1,
                    ];
                }
            }
            $c1 = count($c1Values) > 0 ? (array_sum($c1Values) / count($c1Values)) : 50.0;

            // C2: Jarak Tempuh (Km) - Cost
            $distKm = $this->haversine($originLat, $originLng, (float)$loc->latitude, (float)$loc->longitude);
            $c2 = max($distKm, 0.1); // Hindari 0

            // C3: Fasilitas - Benefit
            $c3 = (float)$loc->skor_fasilitas_c3;

            // C4: Keamanan & Cuaca - Benefit
            $c4 = (float)$loc->skor_keamanan_c4;

            $candidates[] = [
                'location' => $loc,
                'c1' => $c1,
                'c2' => $c2,
                'c3' => $c3,
                'c4' => $c4,
                'distance_km' => $distKm,
                'matched_topics' => $topics,
            ];

            $rawMatrix[$loc->id] = [
                'id' => $loc->id,
                'nama' => $loc->nama_lokasi,
                'c1' => round($c1, 2),
                'c2' => round($c2, 2),
                'c3' => round($c3, 2),
                'c4' => round($c4, 2),
            ];
        }

        // Ekstremum untuk normalisasi
        $maxC1 = max(array_column($candidates, 'c1'));
        $minC2 = min(array_column($candidates, 'c2'));
        $maxC3 = max(array_column($candidates, 'c3'));
        $maxC4 = max(array_column($candidates, 'c4'));

        // 2. Normalisasi Matriks R & Nilai Preferensi V
        $normMatrix = [];
        $prefResults = [];

        foreach ($candidates as &$cand) {
            $locId = $cand['location']->id;
            // C1 (Benefit): x / max
            $r1 = $maxC1 > 0 ? ($cand['c1'] / $maxC1) : 1.0;
            // C2 (Cost): min / x
            $r2 = $cand['c2'] > 0 ? ($minC2 / $cand['c2']) : 1.0;
            // C3 (Benefit): x / max
            $r3 = $maxC3 > 0 ? ($cand['c3'] / $maxC3) : 1.0;
            // C4 (Benefit): x / max
            $r4 = $maxC4 > 0 ? ($cand['c4'] / $maxC4) : 1.0;

            // V = sum(W * R)
            $v = ($weights['c1'] * $r1) +
                 ($weights['c2'] * $r2) +
                 ($weights['c3'] * $r3) +
                 ($weights['c4'] * $r4);

            $cand['r1'] = round($r1, 4);
            $cand['r2'] = round($r2, 4);
            $cand['r3'] = round($r3, 4);
            $cand['r4'] = round($r4, 4);
            $cand['v_score'] = round($v, 4);
            $cand['v_percentage'] = round($v * 100, 1);

            $normMatrix[$locId] = [
                'id' => $locId,
                'nama' => $cand['location']->nama_lokasi,
                'r1' => round($r1, 4),
                'r2' => round($r2, 4),
                'r3' => round($r3, 4),
                'r4' => round($r4, 4),
            ];

            $prefResults[$locId] = [
                'id' => $locId,
                'nama' => $cand['location']->nama_lokasi,
                'v' => round($v, 4),
                'formula' => sprintf(
                    "(%s×%.3f) + (%s×%.3f) + (%s×%.3f) + (%s×%.3f)",
                    $weights['c1'], $r1,
                    $weights['c2'], $r2,
                    $weights['c3'], $r3,
                    $weights['c4'], $r4
                ),
            ];
        }
        unset($cand);

        // Urutkan kandidat berdasarkan skor V tertinggi
        usort($candidates, fn($a, $b) => $b['v_score'] <=> $a['v_score']);

        // Berikan nomor peringkat
        $rank = 1;
        foreach ($candidates as &$cand) {
            $cand['rank'] = $rank++;
        }
        unset($cand);

        return [
            'scenario' => 'SINGLE_MATCH',
            'scenario_label' => 'Skenario 1: Rekomendasi Lokasi Tunggal Terpadu',
            'scenario_description' => 'Ditemukan tempat belajar terpadu di Kota Bengkulu yang mengakomodasi mata pelajaran pilihan Anda.',
            'origin' => [
                'name' => $originName,
                'lat' => $originLat,
                'lng' => $originLng,
            ],
            'selected_subjects' => $subjects,
            'weights' => $weights,
            'results' => $candidates,
            'best_match' => $candidates[0] ?? null,
            'saw_details' => [
                'criteria' => [
                    'c1' => ['name' => 'Relevansi Mata Pelajaran', 'type' => 'Benefit', 'weight' => $weights['c1'], 'max' => $maxC1],
                    'c2' => ['name' => 'Jarak Tempuh (Km)', 'type' => 'Cost', 'weight' => $weights['c2'], 'min' => $minC2],
                    'c3' => ['name' => 'Kelengkapan Fasilitas', 'type' => 'Benefit', 'weight' => $weights['c3'], 'max' => $maxC3],
                    'c4' => ['name' => 'Keamanan & Kondisi Cuaca', 'type' => 'Benefit', 'weight' => $weights['c4'], 'max' => $maxC4],
                ],
                'raw_matrix_x' => array_values($rawMatrix),
                'normalized_matrix_r' => array_values($normMatrix),
                'preference_matrix_v' => array_values($prefResults),
            ],
        ];
    }

    /**
     * Process Scenario 2: Proximity Pairing of 2 nearby locations.
     * Applies whenever >1 subjects are chosen and cannot be fully accommodated in a single location.
     */
    protected function processMultiLocationNearbyScenario(
        array $subjectIds,
        $subjects,
        float $originLat,
        float $originLng,
        string $originName,
        array $weights
    ): array {
        // 1. Temukan kandidat lokasi untuk masing-masing mata pelajaran (untuk evaluasi SAW & modal)
        $subjectCandidates = [];
        $uniqueLocationsMap = [];

        foreach ($subjects as $subj) {
            $locations = Location::whereHas('subjects', function ($q) use ($subj) {
                $q->where('subjects.id', $subj->id);
            })->with('subjects')->get();

            $scored = [];
            foreach ($locations as $loc) {
                $subPivot = $loc->subjects->firstWhere('id', $subj->id);
                $c1 = $subPivot ? (float)$subPivot->pivot->skor_relevansi_c1 : 50.0;
                $distKm = $this->haversine($originLat, $originLng, (float)$loc->latitude, (float)$loc->longitude);
                $c2 = max($distKm, 0.1);
                $c3 = (float)$loc->skor_fasilitas_c3;
                $c4 = (float)$loc->skor_keamanan_c4;

                $scored[] = [
                    'location' => $loc,
                    'subject' => $subj,
                    'topic' => $subPivot ? $subPivot->pivot->topik_kajian : '',
                    'c1' => $c1,
                    'c2' => $c2,
                    'c3' => $c3,
                    'c4' => $c4,
                    'distance_km' => $distKm,
                ];

                if (!isset($uniqueLocationsMap[$loc->id])) {
                    $uniqueLocationsMap[$loc->id] = $loc;
                }
            }

            // Normalisasi lokal untuk subjek ini
            if (!empty($scored)) {
                $maxC1 = max(array_column($scored, 'c1'));
                $minC2 = min(array_column($scored, 'c2'));
                $maxC3 = max(array_column($scored, 'c3'));
                $maxC4 = max(array_column($scored, 'c4'));

                foreach ($scored as &$sc) {
                    $r1 = $maxC1 > 0 ? ($sc['c1'] / $maxC1) : 1.0;
                    $r2 = $sc['c2'] > 0 ? ($minC2 / $sc['c2']) : 1.0;
                    $r3 = $maxC3 > 0 ? ($sc['c3'] / $maxC3) : 1.0;
                    $r4 = $maxC4 > 0 ? ($sc['c4'] / $maxC4) : 1.0;

                    $v = ($weights['c1'] * $r1) +
                         ($weights['c2'] * $r2) +
                         ($weights['c3'] * $r3) +
                         ($weights['c4'] * $r4);

                    $sc['v_score'] = round($v, 4);
                    $sc['r1'] = round($r1, 4);
                    $sc['r2'] = round($r2, 4);
                    $sc['r3'] = round($r3, 4);
                    $sc['r4'] = round($r4, 4);
                }
                unset($sc);

                usort($scored, fn($a, $b) => $b['v_score'] <=> $a['v_score']);
            }

            $subjectCandidates[$subj->id] = $scored;
        }

        // 2. Evaluasi profil gabungan untuk setiap lokasi unik
        $candidates = [];
        foreach ($uniqueLocationsMap as $loc) {
            $matchedSubs = $loc->subjects->whereIn('id', $subjectIds)->values();
            if ($matchedSubs->isEmpty()) {
                continue;
            }

            $c1Values = [];
            $topics = [];
            foreach ($matchedSubs as $s) {
                $c1Values[] = (float)($s->pivot->skor_relevansi_c1 ?? 50.0);
                if (!empty($s->pivot->topik_kajian)) {
                    $topics[] = $s->pivot->topik_kajian;
                }
            }

            $c1 = count($c1Values) > 0 ? (array_sum($c1Values) / count($c1Values)) : 50.0;
            $distKm = $this->haversine($originLat, $originLng, (float)$loc->latitude, (float)$loc->longitude);
            $c2 = max($distKm, 0.1);
            $c3 = (float)$loc->skor_fasilitas_c3;
            $c4 = (float)$loc->skor_keamanan_c4;

            $candidates[] = [
                'location' => $loc,
                'matched_subs' => $matchedSubs,
                'matched_ids' => $matchedSubs->pluck('id')->toArray(),
                'topics_str' => !empty($topics) ? implode('; ', array_unique($topics)) : 'Materi pembelajaran pesisir',
                'c1' => $c1,
                'c2' => $c2,
                'c3' => $c3,
                'c4' => $c4,
                'distance_km' => $distKm,
            ];
        }

        if (empty($candidates)) {
            return [
                'scenario' => 'MULTI_LOCATION_NEARBY',
                'scenario_label' => 'Skenario 2: Rekomendasi 2 Lokasi Edukatif Berdekatan',
                'scenario_description' => 'Tidak ditemukan lokasi yang cocok untuk mata pelajaran pilihan.',
                'origin' => ['name' => $originName, 'lat' => $originLat, 'lng' => $originLng],
                'selected_subjects' => $subjects,
                'weights' => $weights,
                'results' => [],
                'best_match' => null,
            ];
        }

        // Normalisasi SAW gabungan
        $maxC1 = max(array_column($candidates, 'c1')) ?: 1.0;
        $minC2 = min(array_column($candidates, 'c2')) ?: 0.1;
        $maxC3 = max(array_column($candidates, 'c3')) ?: 1.0;
        $maxC4 = max(array_column($candidates, 'c4')) ?: 1.0;

        foreach ($candidates as &$cand) {
            $r1 = $maxC1 > 0 ? ($cand['c1'] / $maxC1) : 1.0;
            $r2 = $cand['c2'] > 0 ? ($minC2 / $cand['c2']) : 1.0;
            $r3 = $maxC3 > 0 ? ($cand['c3'] / $maxC3) : 1.0;
            $r4 = $maxC4 > 0 ? ($cand['c4'] / $maxC4) : 1.0;

            $v = ($weights['c1'] * $r1) +
                 ($weights['c2'] * $r2) +
                 ($weights['c3'] * $r3) +
                 ($weights['c4'] * $r4);

            $cand['saw_score'] = round($v, 4);
        }
        unset($cand);

        // 3. Buat Pasangan 2 Lokasi (Titik 1 & Titik 2) yang Saling Berdekatan
        $packages = [];
        $totalCands = count($candidates);
        $totalSubjectsCount = count($subjectIds);

        for ($i = 0; $i < $totalCands; $i++) {
            for ($j = $i + 1; $j < $totalCands; $j++) {
                $candA = $candidates[$i];
                $candB = $candidates[$j];

                // Gabungan mapel yang dicakup oleh kedua lokasi
                $unionIds = array_values(array_unique(array_merge($candA['matched_ids'], $candB['matched_ids'])));
                $coveredCount = count($unionIds);

                // Kedua lokasi harus saling melengkapi (menambah cakupan mapel)
                $isComplementary = (count($unionIds) > count($candA['matched_ids'])) || (count($unionIds) > count($candB['matched_ids']));
                if (!$isComplementary && $coveredCount < $totalSubjectsCount && $totalSubjectsCount > 1) {
                    continue;
                }

                // Jarak antar-lokasi (haversine)
                $interDistanceKm = $this->haversine(
                    (float)$candA['location']->latitude,
                    (float)$candA['location']->longitude,
                    (float)$candB['location']->latitude,
                    (float)$candB['location']->longitude
                );
                $interDistanceMeters = round($interDistanceKm * 1000);

                // Urutkan titik: Titik 1 adalah yang lebih dekat dari sekolah asal
                if ($candA['distance_km'] <= $candB['distance_km']) {
                    $pt1 = $candA;
                    $pt2 = $candB;
                } else {
                    $pt1 = $candB;
                    $pt2 = $candA;
                }

                $estMinutes = max(2, (int)round(($interDistanceKm / 20) * 60));
                $estWalkMinutes = (int)round(($interDistanceKm / 4.5) * 60);
                $totalTripKm = round($pt1['distance_km'] + $interDistanceKm, 2);
                $avgSawScore = round(($pt1['saw_score'] + $pt2['saw_score']) / 2, 4);

                $coverageRatio = $totalSubjectsCount > 0 ? ($coveredCount / $totalSubjectsCount) : 1.0;
                $proximityFactor = 1 / (1 + ($interDistanceKm * 0.6));
                $compositeScore = round(($coverageRatio * 0.40) + ($proximityFactor * 0.40) + ($avgSawScore * 0.20), 4);

                $pt1SubjectName = $pt1['matched_subs']->pluck('nama_mapel')->implode(', ');
                $pt2SubjectName = $pt2['matched_subs']->pluck('nama_mapel')->implode(', ');

                $pt1SubjectObj = (object)[
                    'id' => $pt1['matched_subs']->first()->id ?? 0,
                    'nama_mapel' => $pt1SubjectName,
                    'kode_mapel' => $pt1['matched_subs']->pluck('kode_mapel')->implode(', '),
                ];

                $pt2SubjectObj = (object)[
                    'id' => $pt2['matched_subs']->first()->id ?? 0,
                    'nama_mapel' => $pt2SubjectName,
                    'kode_mapel' => $pt2['matched_subs']->pluck('kode_mapel')->implode(', '),
                ];

                $packages[] = [
                    'type' => 'PAIR',
                    'package_name' => "Paket Rute: {$pt1['location']->nama_lokasi} ⇄ {$pt2['location']->nama_lokasi}",
                    'coverage_count' => $coveredCount,
                    'coverage_ratio' => $coverageRatio,
                    'covers_all' => ($coveredCount === $totalSubjectsCount),
                    'composite_score' => $compositeScore,
                    'avg_saw_score' => $avgSawScore,
                    'inter_distance_km' => round($interDistanceKm, 3),
                    'inter_distance_m' => $interDistanceMeters,
                    'travel_time_est' => "±{$estMinutes} mnt berkendara" . ($interDistanceKm <= 1.2 ? " (±{$estWalkMinutes} mnt jalan kaki)" : ""),
                    'total_trip_km' => $totalTripKm,
                    'points' => [
                        [
                            'order' => 1,
                            'location' => $pt1['location'],
                            'subject' => $pt1SubjectObj,
                            'matched_subjects_list' => $pt1['matched_subs']->pluck('nama_mapel')->toArray(),
                            'topic' => $pt1['topics_str'],
                            'saw_score' => $pt1['saw_score'],
                            'distance_from_origin' => $pt1['distance_km'],
                            'c1' => $pt1['c1'],
                            'c2' => $pt1['c2'],
                            'c3' => $pt1['c3'],
                            'c4' => $pt1['c4'],
                        ],
                        [
                            'order' => 2,
                            'location' => $pt2['location'],
                            'subject' => $pt2SubjectObj,
                            'matched_subjects_list' => $pt2['matched_subs']->pluck('nama_mapel')->toArray(),
                            'topic' => $pt2['topics_str'],
                            'saw_score' => $pt2['saw_score'],
                            'distance_from_origin' => $pt2['distance_km'],
                            'c1' => $pt2['c1'],
                            'c2' => $pt2['c2'],
                            'c3' => $pt2['c3'],
                            'c4' => $pt2['c4'],
                        ],
                    ],
                    'route_coords' => [
                        [(float)$pt1['location']->latitude, (float)$pt1['location']->longitude],
                        [(float)$pt2['location']->latitude, (float)$pt2['location']->longitude],
                    ],
                ];
            }
        }

        // 4. Urutkan paket:
        // Prioritas 1: Cakupan jumlah mapel terbanyak (coverage_count DESC)
        // Prioritas 2: Jarak antar-lokasi berdekatan (inter_distance_km <= 1.5 km diutamakan)
        // Prioritas 3: Skor komposit tertinggi
        usort($packages, function ($a, $b) {
            if ($a['coverage_count'] !== $b['coverage_count']) {
                return $b['coverage_count'] <=> $a['coverage_count'];
            }
            $aNear = $a['inter_distance_km'] <= 1.5;
            $bNear = $b['inter_distance_km'] <= 1.5;
            if ($aNear && !$bNear) return -1;
            if (!$aNear && $bNear) return 1;

            return $b['composite_score'] <=> $a['composite_score'];
        });

        // Berikan nomor peringkat paket
        $pRank = 1;
        foreach ($packages as &$pkg) {
            $pkg['rank'] = $pRank++;
        }
        unset($pkg);

        // Ambil top 30 paket terbaik
        $packages = array_slice($packages, 0, 30);
        $bestPackage = $packages[0] ?? null;

        return [
            'scenario' => 'MULTI_LOCATION_NEARBY',
            'scenario_label' => 'Skenario 2: Rekomendasi 2 Lokasi Edukatif Berdekatan',
            'scenario_description' => 'Mata pelajaran yang Anda pilih saling melengkapi di 2 lokasi edukatif terdekat di Kota Bengkulu.',
            'origin' => [
                'name' => $originName,
                'lat' => $originLat,
                'lng' => $originLng,
            ],
            'selected_subjects' => $subjects,
            'weights' => $weights,
            'results' => $packages,
            'best_match' => $bestPackage,
            'subject_candidates' => $subjectCandidates,
            'saw_details' => [
                'criteria' => [
                    'c1' => ['name' => 'Relevansi Mata Pelajaran', 'type' => 'Benefit', 'weight' => $weights['c1']],
                    'c2' => ['name' => 'Jarak Tempuh (Km)', 'type' => 'Cost', 'weight' => $weights['c2']],
                    'c3' => ['name' => 'Kelengkapan Fasilitas', 'type' => 'Benefit', 'weight' => $weights['c3']],
                    'c4' => ['name' => 'Keamanan & Kondisi Cuaca', 'type' => 'Benefit', 'weight' => $weights['c4']],
                ],
                'note' => 'Perhitungan SAW dievaluasi per mata pelajaran secara terpisah, kemudian algoritma Spatial Proximity memadukan 2 lokasi saling berdekatan dengan rute terpendek.',
                'per_subject_tables' => collect($subjectCandidates)->map(function ($items, $sId) use ($subjects) {
                    $subj = $subjects->firstWhere('id', $sId);
                    return [
                        'subject_id' => $sId,
                        'subject_name' => $subj ? $subj->nama_mapel : "Mapel #$sId",
                        'subject_code' => $subj ? $subj->kode_mapel : "MAPEL",
                        'candidates' => array_map(function ($c) {
                            return [
                                'nama' => $c['location']->nama_lokasi,
                                'c1' => round($c['c1'], 2),
                                'c2' => round($c['c2'], 2),
                                'c3' => round($c['c3'], 2),
                                'c4' => round($c['c4'], 2),
                                'r1' => $c['r1'],
                                'r2' => $c['r2'],
                                'r3' => $c['r3'],
                                'r4' => $c['r4'],
                                'v' => $c['v_score'],
                            ];
                        }, $items),
                    ];
                })->values()->toArray(),
            ],
        ];
    }
}
