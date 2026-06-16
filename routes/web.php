<?php

use App\Http\Controllers\Admin\AdminMitraController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


// ============================================================
// Halaman Publik
// ============================================================
Route::get('/', [MapController::class, 'index'])->name('map.index');

// ============================================================
// API JSON untuk Leaflet & Dashboard Analytics
// ============================================================
Route::prefix('api/mitras')->group(function () {
    Route::get('/geojson',              [MapController::class, 'geojson'])->name('api.mitras.geojson');
    Route::get('/stats',                [MapController::class, 'stats'])->name('api.mitras.stats');
    Route::get('/wilayah-counts',       [MapController::class, 'wilayahCounts'])->name('api.mitras.wilayah-counts');
    Route::get('/monthly-growth',       [MapController::class, 'monthlyGrowth'])->name('api.mitras.monthly-growth');
    Route::get('/kategori-per-wilayah', [MapController::class, 'kategoriPerWilayah'])->name('api.mitras.kategori-per-wilayah');
});

// ============================================================
// Admin Authentication
// ============================================================
//
// SESI ADMIN:
// - Middleware "guest" (App\Http\Middleware\RedirectIfAuthenticated)
//   -> jika admin SUDAH LOGIN, /admin/login otomatis redirect
//      ke /admin/dashboard. Form login TIDAK BISA diakses lagi
//      sampai admin logout.
//
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.attempt');
});

Route::post('/admin/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('admin.logout');

// ============================================================
// Admin Protected Routes
// ============================================================
//
// Middleware "auth" (App\Http\Middleware\Authenticate, custom)
// -> jika BELUM LOGIN dan mengakses /admin/dashboard atau
//    /admin/mitras/*, redirect ke route('admin.login')
//    (BUKAN ke route 'login' yang tidak ada -> mencegah 404/500).
//
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('mitras', AdminMitraController::class)->except(['show']);

Route::get('/setup-database-rahasia', function () {
    Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
    return 'Database berhasil di-migrate dan di-seed wok!';
});

});
