<?php

use App\Http\Controllers\RecommendationController;
use App\Models\Location;
use App\Models\Subject;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PESISIR-EDU Headless RESTful API Routes
|--------------------------------------------------------------------------
| Decoupled backend API for Frontend SPA client consumption.
*/

// 1. Get all subjects
Route::get('/subjects', function () {
    return response()->json([
        'success' => true,
        'data' => Subject::withCount('locations')->get(),
    ]);
});

// 2. Get school departure presets
Route::get('/departure-presets', function () {
    return response()->json([
        'success' => true,
        'data' => RecommendationService::DEPARTURE_PRESETS,
    ]);
});

// 3. Location CRUD endpoints
Route::get('/locations', [RecommendationController::class, 'getLocationsJson']);
Route::post('/locations', [RecommendationController::class, 'storeLocation']);
Route::delete('/locations/{location}', [RecommendationController::class, 'destroyLocation']);

// 4. Recommendation & SPK SAW Calculation
Route::post('/calculate', [RecommendationController::class, 'calculate']);
