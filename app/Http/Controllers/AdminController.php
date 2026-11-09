<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Admin Dashboard: Educational spatial statistics & coastal learning locations overview.
     */
    public function dashboard(): View
    {
        $totalLocations = Location::count();
        $totalSubjects = Subject::count();
        $totalCurriculumTopics = \DB::table('location_subject')->count();

        $avgFacility = round((float)Location::avg('skor_fasilitas_c3'), 1);
        $avgSafety = round((float)Location::avg('skor_keamanan_c4'), 1);

        $recentLocations = Location::with('subjects')->latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'totalLocations',
            'totalSubjects',
            'totalCurriculumTopics',
            'avgFacility',
            'avgSafety',
            'recentLocations'
        ));
    }

    /**
     * Admin Location Management (CRUD List).
     */
    public function locations(): View
    {
        $locations = Location::with('subjects')->latest()->get();
        $allSubjects = Subject::all();

        return view('admin.locations', compact('locations', 'allSubjects'));
    }

    /**
     * Store new coastal learning location (CRUD Create).
     */
    public function storeLocation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat_kawasan' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'deskripsi' => 'required|string',
            'jam_operasional' => 'nullable|string',
            'skor_fasilitas_c3' => 'required|integer|min:1|max:100',
            'skor_keamanan_c4' => 'required|integer|min:1|max:100',
            'subject_ids' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['nama_lokasi']) . '-' . rand(100, 999);
        $validated['kondisi_cuaca'] = 'Normal';
        $validated['kecepatan_angin_knot'] = 10;
        $validated['arah_angin'] = 'Barat Daya';
        $validated['tinggi_gelombang_meter'] = 1.0;
        $validated['tingkat_risiko'] = 'Rendah';

        $location = Location::create($validated);

        if (!empty($validated['subject_ids'])) {
            $syncData = [];
            foreach ($validated['subject_ids'] as $sId) {
                $syncData[$sId] = [
                    'skor_relevansi_c1' => 85,
                    'topik_kajian' => 'Kajian Lapangan Kurikulum Pesisir',
                ];
            }
            $location->subjects()->sync($syncData);
        }

        return redirect()->route('admin.locations')->with('success', 'Lokasi pembelajaran pesisir baru berhasil ditambahkan!');
    }

    /**
     * Update existing coastal learning location (CRUD Edit).
     */
    public function updateLocation(Request $request, Location $location): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat_kawasan' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'deskripsi' => 'required|string',
            'jam_operasional' => 'nullable|string',
            'skor_fasilitas_c3' => 'required|integer|min:1|max:100',
            'skor_keamanan_c4' => 'required|integer|min:1|max:100',
            'subject_ids' => 'nullable|array',
        ]);

        $location->update($validated);

        if (isset($validated['subject_ids'])) {
            $syncData = [];
            foreach ($validated['subject_ids'] as $sId) {
                $syncData[$sId] = [
                    'skor_relevansi_c1' => 88,
                    'topik_kajian' => 'Kajian Lapangan Terverifikasi Admin',
                ];
            }
            $location->subjects()->sync($syncData);
        }

        return redirect()->route('admin.locations')->with('success', 'Data lokasi pembelajaran berhasil diperbarui!');
    }

    /**
     * Delete coastal learning location (CRUD Delete).
     */
    public function destroyLocation(Location $location): RedirectResponse
    {
        $nama = $location->nama_lokasi;
        $location->delete();

        return redirect()->route('admin.locations')->with('success', "Lokasi \"{$nama}\" berhasil dihapus dari sistem.");
    }

    /**
     * Admin Subject Management (Curriculum Topics Matrix).
     */
    public function subjects(): View
    {
        $subjects = Subject::with(['locations' => function ($q) {
            $q->withPivot('skor_relevansi_c1', 'topik_kajian');
        }])->orderBy('id_jenjang', 'asc')->orderBy('kode_mapel', 'asc')->get();

        return view('admin.subjects', compact('subjects'));
    }

    /**
     * Store a newly created subject (CRUD Create).
     */
    public function storeSubject(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'id_jenjang' => 'required|string|in:JJ001,JJ002,JJ003',
            'deskripsi' => 'required|string|max:500',
            'kode_mapel' => 'nullable|string|max:20|unique:subjects,kode_mapel',
            'warna_teks' => 'nullable|string|max:30',
            'warna_bg_soft' => 'nullable|string|max:30',
        ]);

        // Auto-generate code if not specified (e.g. MP030, MP031...)
        if (empty($validated['kode_mapel'])) {
            $lastSubject = Subject::where('kode_mapel', 'LIKE', 'MP%')
                ->orderByRaw('CAST(SUBSTRING(kode_mapel, 3) AS UNSIGNED) DESC')
                ->first();

            $nextNum = 30;
            if ($lastSubject && preg_match('/MP(\d+)/', $lastSubject->kode_mapel, $matches)) {
                $nextNum = max($nextNum, ((int)$matches[1]) + 1);
            }
            $validated['kode_mapel'] = 'MP' . str_pad((string)$nextNum, 3, '0', STR_PAD_LEFT);
        }

        // Default colors based on educational level if empty
        if (empty($validated['warna_teks']) || empty($validated['warna_bg_soft'])) {
            match ($validated['id_jenjang']) {
                'JJ001' => [
                    $validated['warna_teks'] = '#059669',
                    $validated['warna_bg_soft'] = '#ECFDF5',
                ],
                'JJ002' => [
                    $validated['warna_teks'] = '#2563EB',
                    $validated['warna_bg_soft'] = '#EFF6FF',
                ],
                default => [
                    $validated['warna_teks'] = '#132985',
                    $validated['warna_bg_soft'] = '#EEF2FF',
                ],
            };
        }

        $validated['ikon_svg'] = 'book-open';

        $subject = Subject::create($validated);

        return redirect()->route('admin.subjects')->with('success', "Mata pelajaran baru \"{$subject->nama_mapel}\" ({$subject->kode_mapel}) berhasil ditambahkan ke kurikulum!");
    }

    /**
     * Update an existing subject (CRUD Update).
     */
    public function updateSubject(Request $request, Subject $subject): RedirectResponse
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'required|string|max:20|unique:subjects,kode_mapel,' . $subject->id,
            'id_jenjang' => 'required|string|in:JJ001,JJ002,JJ003',
            'deskripsi' => 'required|string|max:500',
            'warna_teks' => 'nullable|string|max:30',
            'warna_bg_soft' => 'nullable|string|max:30',
        ]);

        if (empty($validated['warna_teks'])) {
            $validated['warna_teks'] = $subject->warna_teks ?? '#132985';
        }
        if (empty($validated['warna_bg_soft'])) {
            $validated['warna_bg_soft'] = $subject->warna_bg_soft ?? '#EEF2FF';
        }

        $subject->update($validated);

        return redirect()->route('admin.subjects')->with('success', "Data mata pelajaran \"{$subject->nama_mapel}\" ({$subject->kode_mapel}) berhasil diperbarui!");
    }

    /**
     * Remove a subject from curriculum (CRUD Delete).
     */
    public function destroySubject(Subject $subject): RedirectResponse
    {
        $nama = $subject->nama_mapel;
        $kode = $subject->kode_mapel;

        // Detach pivot connections
        $subject->locations()->detach();

        $subject->delete();

        return redirect()->route('admin.subjects')->with('success', "Mata pelajaran \"{$nama}\" ({$kode}) berhasil dihapus dari kurikulum.");
    }
}
