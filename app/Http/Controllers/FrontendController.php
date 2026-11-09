<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\School;
use App\Models\Subject;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FrontendController extends Controller
{
    protected RecommendationService $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * 1. Halaman Beranda Portal Siswa (Landing Page Edukatif).
     */
    public function index(Request $request): View
    {
        $allSubjects = Subject::withCount('locations')->orderBy('id_jenjang')->orderBy('kode_mapel')->get();
        $totalSchools = School::count();
        $totalPlaces = Location::count();

        // 6 Tempat Belajar Unggulan Populer untuk Tampilan Beranda
        $featuredPlaces = Location::with('subjects')
            ->whereIn('id_tempat', ['TP001', 'TP003', 'TP004', 'TP002', 'TP005', 'TP007'])
            ->get();

        if ($featuredPlaces->isEmpty()) {
            $featuredPlaces = Location::with('subjects')->take(6)->get();
        }

        return view('frontend.home', compact(
            'allSubjects',
            'featuredPlaces',
            'totalPlaces',
            'totalSchools'
        ));
    }

    /**
     * 2. Halaman Hasil Rekomendasi Belajar (Pencarian & Perhitungan SAW).
     */
    public function recommendations(Request $request): View
    {
        // 267 Sekolah Kota Bengkulu (SD, SMP, SMA)
        $allSchools = School::orderBy('nama_sekolah', 'asc')->get();
        
        // 29 Mata Pelajaran
        $allSubjects = Subject::withCount('locations')
            ->orderBy('id_jenjang', 'asc')
            ->orderBy('kode_mapel', 'asc')
            ->get();

        $schoolParam = $request->input('school');
        $selectedSchool = null;

        if (is_numeric($schoolParam)) {
            $selectedSchool = School::find((int)$schoolParam);
        } elseif (!empty($schoolParam)) {
            $selectedSchool = School::where('nama_sekolah', 'LIKE', "%{$schoolParam}%")->first();
        }

        if ($selectedSchool) {
            session(['user_school_id' => $selectedSchool->id]);
        }

        if (!$selectedSchool) {
            // Tampilan Awal: Default ke SEMUA (bukan langsung memilih jenjang SMA)
            $selectedSubjectIds = [];
            $chosenSubjects = collect();
            $recommendationData = [
                'results' => [],
                'scenario' => 'initial_all',
                'is_initial' => true,
            ];

            return view('frontend.recommendations', compact(
                'allSchools',
                'allSubjects',
                'selectedSchool',
                'selectedSubjectIds',
                'chosenSubjects',
                'recommendationData'
            ));
        }

        // Ambil Mata Pelajaran Terpilih (multi-select)
        $rawSubjects = $request->input('subjects', []);
        if (is_string($rawSubjects)) {
            $rawSubjects = explode(',', $rawSubjects);
        }
        $selectedSubjectIds = array_filter(array_map('intval', (array)$rawSubjects));

        // Kunci jenjang: Hanya perbolehkan mata pelajaran yang sesuai dengan id_jenjang sekolah terpilih
        $allowedSubjects = Subject::where('id_jenjang', $selectedSchool->id_jenjang)->get();
        $allowedSubjectIds = $allowedSubjects->pluck('id')->toArray();
        $selectedSubjectIds = array_values(array_intersect($selectedSubjectIds, $allowedSubjectIds));

        if (empty($selectedSubjectIds)) {
            $chosenSubjects = collect();
            $recommendationData = [
                'results' => [],
                'scenario' => 'initial_all',
                'is_initial' => true,
            ];

            return view('frontend.recommendations', compact(
                'allSchools',
                'allSubjects',
                'selectedSchool',
                'selectedSubjectIds',
                'chosenSubjects',
                'recommendationData'
            ));
        }

        // Jalankan Perhitungan SAW di Backend
        $recommendationData = $this->recommendationService->getRecommendations(
            $selectedSubjectIds,
            (float)$selectedSchool->latitude,
            (float)$selectedSchool->longitude,
            $selectedSchool->nama_sekolah
        );

        // Ambil objek mata pelajaran yang dipilih
        $chosenSubjects = Subject::whereIn('id', $selectedSubjectIds)->get();

        return view('frontend.recommendations', compact(
            'allSchools',
            'allSubjects',
            'selectedSchool',
            'selectedSubjectIds',
            'chosenSubjects',
            'recommendationData'
        ));
    }

    /**
     * 3. Halaman Direktori Semua Tempat Belajar Kota Bengkulu (50 Tempat).
     */
    public function places(Request $request): View
    {
        $query = Location::with('subjects');

        $searchQuery = trim($request->input('q', ''));
        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('nama_lokasi', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('alamat_kawasan', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$searchQuery}%");
            });
        }

        $places = $query->orderBy('nama_lokasi', 'asc')->get();
        $totalCount = Location::count();

        return view('frontend.places', compact(
            'places',
            'searchQuery',
            'totalCount'
        ));
    }

    /**
     * 4. Halaman Detail Tempat Belajar Edukatif.
     */
    public function showPlace(Location $location): View
    {
        $location->load('subjects');

        // Hitung jarak dari sekolah-sekolah terdekat di Kota Bengkulu (Top 10 Terdekat)
        $distances = School::all()->map(function ($school) use ($location) {
            return [
                'school' => $school->nama_sekolah,
                'jenjang' => $school->jenjang_label,
                'address' => $school->alamat,
                'distance_km' => $this->recommendationService->haversine(
                    $school->latitude,
                    $school->longitude,
                    $location->latitude,
                    $location->longitude
                ),
            ];
        })->sortBy('distance_km')->take(10)->values()->all();

        // Ambil tempat belajar serupa
        $subjectIds = $location->subjects->pluck('id')->toArray();
        $relatedPlaces = Location::where('id', '!=', $location->id)
            ->whereHas('subjects', function ($q) use ($subjectIds) {
                $q->whereIn('subjects.id', $subjectIds);
            })
            ->take(3)
            ->get();

        return view('frontend.place_detail', compact(
            'location',
            'distances',
            'relatedPlaces'
        ));
    }

    /**
     * 5. Halaman Peta Internal Rute Tempat Belajar (Leaflet OSM).
     */
    public function showMap(Request $request, Location $location): View
    {
        $location->load('subjects');

        $schools = School::orderBy('nama_sekolah', 'asc')->get();

        $schoolId = $request->input('school') ?? session('user_school_id');
        $selectedSchool = null;
        if ($schoolId && is_numeric($schoolId)) {
            $selectedSchool = School::find((int)$schoolId);
            if ($selectedSchool) {
                session(['user_school_id' => $selectedSchool->id]);
            }
        }

        if (!$selectedSchool) {
            $selectedSchool = School::where('nama_sekolah', 'LIKE', '%SMA NEGERI 1 %')
                ->orWhere('nama_sekolah', 'LIKE', '%SMAN 1 %')
                ->first() 
                ?? $schools->first();
        }

        // Hitung jarak Haversine (km)
        $distanceKm = null;
        if ($selectedSchool && $location->latitude && $location->longitude && $selectedSchool->latitude && $selectedSchool->longitude) {
            $lat1 = deg2rad((float)$selectedSchool->latitude);
            $lon1 = deg2rad((float)$selectedSchool->longitude);
            $lat2 = deg2rad((float)$location->latitude);
            $lon2 = deg2rad((float)$location->longitude);

            $dlat = $lat2 - $lat1;
            $dlon = $lon2 - $lon1;

            $a = sin($dlat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dlon / 2) ** 2;
            $c = 2 * asin(min(1, sqrt($a)));
            $distanceKm = round(6371 * $c, 2);
        }

        return view('frontend.place_map', compact(
            'location',
            'schools',
            'selectedSchool',
            'distanceKm'
        ));
    }
}

