<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\RecommendationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PESISIR-EDU WEB ROUTES (FRONTEND & BACKEND ADMIN PADA SATU PORT)
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. BAGIAN FRONTEND (Khusus Portal Siswa)
// ==========================================
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/rekomendasi', [FrontendController::class, 'recommendations'])->name('frontend.recommendations');
Route::get('/tempat-belajar', [FrontendController::class, 'places'])->name('frontend.places');
Route::get('/tempat-belajar/{location}', [FrontendController::class, 'showPlace'])->name('frontend.places.show');
Route::get('/peta/{location}', [FrontendController::class, 'showMap'])->name('frontend.places.map');

// ==========================================
// 2. OTENTIKASI ADMINISTRATOR (Guest Only)
// ==========================================
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// ==========================================
// 3. BAGIAN BACKEND (Khusus Admin Terautentikasi / Protected)
// ==========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // CRUD Lokasi Pesisir (Khusus Admin)
    Route::get('/locations', [AdminController::class, 'locations'])->name('locations');
    Route::post('/locations', [AdminController::class, 'storeLocation'])->name('locations.store');
    Route::put('/locations/{location}', [AdminController::class, 'updateLocation'])->name('locations.update');
    Route::delete('/locations/{location}', [AdminController::class, 'destroyLocation'])->name('locations.destroy');
    
    // CRUD Kurikulum & Mata Pelajaran (Khusus Admin)
    Route::get('/subjects', [AdminController::class, 'subjects'])->name('subjects');
    Route::post('/subjects', [AdminController::class, 'storeSubject'])->name('subjects.store');
    Route::put('/subjects/{subject}', [AdminController::class, 'updateSubject'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [AdminController::class, 'destroySubject'])->name('subjects.destroy');
});

// ==========================================
// 4. API ENDPOINTS (Interactive Calculations)
// ==========================================
Route::post('/api/calculate', [RecommendationController::class, 'calculate'])->name('api.calculate');
Route::get('/api/locations', [RecommendationController::class, 'getLocationsJson'])->name('api.locations');
Route::post('/api/locations', [RecommendationController::class, 'storeLocation'])->middleware(['auth'])->name('api.locations.store');
Route::delete('/api/locations/{location}', [RecommendationController::class, 'destroyLocation'])->middleware(['auth'])->name('api.locations.destroy');
