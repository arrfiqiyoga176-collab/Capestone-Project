<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Subject;
use App\Services\RecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RecommendationController extends Controller
{
    protected RecommendationService $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Display the main Gymove-styled PESISIR-EDU dashboard.
     */
    public function index(Request $request): View
    {
        $allSubjects = Subject::withCount('locations')->get();
        $departurePresets = RecommendationService::DEPARTURE_PRESETS;

        // Default selected subjects: e.g. Geografi + Ekonomi (or user inputs)
        $selectedSubjectIds = $request->input('subject_ids');
        if (empty($selectedSubjectIds)) {
            // Pick default subjects
            $defaultSubjs = Subject::whereIn('kode_mapel', ['GEO', 'EKO'])->pluck('id')->toArray();
            $selectedSubjectIds = !empty($defaultSubjs) ? $defaultSubjs : [$allSubjects->first()->id];
        } elseif (is_string($selectedSubjectIds)) {
            $selectedSubjectIds = explode(',', $selectedSubjectIds);
        }
        $selectedSubjectIds = array_map('intval', (array)$selectedSubjectIds);

        // Departure coordinates
        $originPreset = $request->input('origin_preset', 'sman1');
        $preset = collect($departurePresets)->firstWhere('id', $originPreset) ?? $departurePresets[0];

        $originLat = (float)$request->input('origin_lat', $preset['lat']);
        $originLng = (float)$request->input('origin_lng', $preset['lng']);
        $originName = $request->input('origin_name', $preset['name']);

        // Weights
        $weights = [
            'c1' => (float)$request->input('w_c1', 0.35),
            'c2' => (float)$request->input('w_c2', 0.25),
            'c3' => (float)$request->input('w_c3', 0.20),
            'c4' => (float)$request->input('w_c4', 0.20),
        ];

        // Execute recommendation pipeline in backend
        $recommendationData = $this->recommendationService->getRecommendations(
            $selectedSubjectIds,
            $originLat,
            $originLng,
            $originName,
            $weights
        );

        $allLocations = Location::with('subjects')->get();

        return view('pesisir.dashboard', compact(
            'allSubjects',
            'allLocations',
            'departurePresets',
            'selectedSubjectIds',
            'originLat',
            'originLng',
            'originName',
            'originPreset',
            'weights',
            'recommendationData'
        ));
    }

    /**
     * AJAX API endpoint for live simulation without full page reload.
     */
    public function calculate(Request $request): JsonResponse
    {
        $selectedSubjectIds = $request->input('subject_ids', []);
        if (is_string($selectedSubjectIds)) {
            $selectedSubjectIds = explode(',', $selectedSubjectIds);
        }
        $selectedSubjectIds = array_map('intval', (array)$selectedSubjectIds);

        if (empty($selectedSubjectIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih minimal satu mata pelajaran.',
            ], 422);
        }

        $originLat = (float)$request->input('origin_lat', -3.7915);
        $originLng = (float)$request->input('origin_lng', 102.2610);
        $originName = $request->input('origin_name', 'SMAN 1 Kota Bengkulu');

        $weights = [
            'c1' => (float)$request->input('w_c1', 0.35),
            'c2' => (float)$request->input('w_c2', 0.25),
            'c3' => (float)$request->input('w_c3', 0.20),
            'c4' => (float)$request->input('w_c4', 0.20),
        ];

        $data = $this->recommendationService->getRecommendations(
            $selectedSubjectIds,
            $originLat,
            $originLng,
            $originName,
            $weights
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Return all locations with coordinate data for Leaflet WebGIS.
     */
    public function getLocationsJson(): JsonResponse
    {
        $locations = Location::with('subjects')->get();
        return response()->json($locations);
    }

    /**
     * Quick CRUD: Store new location.
     */
    public function storeLocation(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat_kawasan' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'deskripsi' => 'required|string',
            'jam_operasional' => 'nullable|string',
            'kondisi_cuaca' => 'required|string',
            'kecepatan_angin_knot' => 'required|integer',
            'arah_angin' => 'required|string',
            'tinggi_gelombang_meter' => 'required|numeric',
            'tingkat_risiko' => 'required|in:Rendah,Sedang,Tinggi',
            'skor_fasilitas_c3' => 'required|integer|min:1|max:100',
            'skor_keamanan_c4' => 'required|integer|min:1|max:100',
            'subject_ids' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['nama_lokasi']) . '-' . rand(100, 999);

        $location = Location::create($validated);

        if (!empty($validated['subject_ids'])) {
            $syncData = [];
            foreach ($validated['subject_ids'] as $sId) {
                $syncData[$sId] = [
                    'skor_relevansi_c1' => 85,
                    'topik_kajian' => 'Kajian Lapangan Umum',
                ];
            }
            $location->subjects()->sync($syncData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lokasi pesisir berhasil ditambahkan!',
            'location' => $location->load('subjects'),
        ]);
    }

    /**
     * Quick CRUD: Delete location.
     */
    public function destroyLocation(Location $location): JsonResponse
    {
        $location->delete();
        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil dihapus.',
        ]);
    }
}
