<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Wajib untuk fitur pembersih sesi
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KostController;
use App\Http\Controllers\LandingController; 
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\RoleMiddleware;

// ============================================================
// ROUTE JEMBATAN (TRANSISI AMAN ANTAR HALAMAN & AKUN)
// ============================================================

// 1. Jembatan Menuju Halaman Publik (Dipakai di Dashboard Mitra)
Route::get('/menuju-publik', function () {
    if (Auth::guard('web')->check()) {
        Auth::guard('web')->logout();
    }
    return redirect()->route('home');
})->name('jembatan.publik');

// 2. Jembatan Menuju Halaman Publik (Dipakai di Sidebar Superadmin)
Route::get('/superadmin/ke-halaman-publik', function () {
    if (Auth::guard('web')->check()) {
        Auth::guard('web')->logout();
    }
    return redirect()->route('home');
})->name('superadmin.ke_publik');

// 3. Jembatan Menuju Dashboard Mitra (Dipakai di Sidebar Superadmin)
Route::get('/jembatan-mitra', function () {
    if (Auth::guard('web')->check()) {
        Auth::guard('web')->logout();
    }
    if (Auth::guard('mitra')->check()) {
        return redirect()->route('mitra.dashboard');
    }
    return redirect()->route('mitra.login');
})->name('jembatan.mitra');


// ============================================================
// HALAMAN PUBLIK
// ============================================================
Route::middleware([PreventBackHistory::class])->group(function () {
    Route::get('/', [LandingController::class, 'index'])->name('home');
    Route::get('/search', [KostController::class, 'search'])->name('kost.search');
    Route::post('/kost/filter', [KostController::class, 'filter'])->name('kost.filter');
    Route::get('/kategori/{nama_kategori}', [HomeController::class, 'searchByCategory'])->name('kost.kategori');
    Route::get('/kost/{id}', [KostController::class, 'show'])->name('kost.detail');
    Route::get('/kost/{id}/gallery', [KostController::class, 'gallery'])->name('kost.gallery');
    Route::get('/instansi/{kategori}', [LandingController::class, 'daftarInstansi'])->name('instansi.daftar');
    Route::post('/kost/{id}/review', [KostController::class, 'storePublicReview'])->name('review.store.public');
});

// ============================================================
// AUTHENTICATION (TERPISAH BERDASARKAN GUARD)
// ============================================================

// Login Pengunjung (Guard: web)
Route::middleware(['guest:web', PreventBackHistory::class])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Login Mitra (Guard: mitra)
Route::middleware(['guest:mitra', PreventBackHistory::class])->group(function () {
    Route::get('/mitra/login', [AuthController::class, 'showMitraLogin'])->name('mitra.login');
    Route::post('/mitra/login', [AuthController::class, 'mitraLogin'])->name('mitra.login.submit');
    Route::get('/mitra/register', [AuthController::class, 'showMitraRegister'])->name('mitra.register');
    Route::post('/mitra/register', [AuthController::class, 'mitraRegister'])->name('mitra.register.submit');
});

// Login Superadmin (Guard: superadmin)
Route::middleware(['guest:superadmin', PreventBackHistory::class])->group(function () {
    Route::get('/superadmin/login', [AuthController::class, 'showSuperadminLogin'])->name('superadmin.login');
    Route::post('/superadmin/login', [AuthController::class, 'superadminLogin'])->name('superadmin.login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================================
// DASHBOARD (PROTECTED)
// ============================================================
Route::middleware(['auth:web,mitra,superadmin', PreventBackHistory::class])->group(function () {
    
    // 1. Pencari Kost
    Route::middleware(RoleMiddleware::class.':pencari_kost')->group(function () {
        Route::get('/pencari/dashboard', [AuthController::class, 'pencariDashboard'])->name('pencari.dashboard');
        Route::get('/profile/edit', [AuthController::class, 'editProfile'])->name('profile.edit');
        Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
        Route::get('/profile/bookmarks', [KostController::class, 'bookmarks'])->name('profile.bookmarks');
    });

    // 2. Mitra
    Route::middleware(RoleMiddleware::class.':admin_kost')->group(function () {
        Route::get('/mitra/dashboard', [AuthController::class, 'mitraDashboard'])->name('mitra.dashboard');
        Route::get('/mitra/kost', [KostController::class, 'indexMitra'])->name('mitra.kost.index');
        Route::get('/mitra/kost/create', [KostController::class, 'createMitra'])->name('mitra.kost.create');
        Route::post('/mitra/kost/store', [KostController::class, 'storeMitra'])->name('mitra.kost.store');
        Route::get('/mitra/kost/{id}/edit', [KostController::class, 'editMitra'])->name('mitra.kost.edit');
        Route::put('/mitra/kost/{id}', [KostController::class, 'updateMitra'])->name('mitra.kost.update');
        Route::delete('/mitra/kost/{id}', [KostController::class, 'destroyMitra'])->name('mitra.kost.destroy');
        Route::post('/mitra/kost/{id}/gallery-update', [KostController::class, 'updateGalleryMitra'])->name('mitra.kost.gallery.update');
        Route::get('/mitra/profile/edit', [AuthController::class, 'mitraProfileEdit'])->name('mitra.profile.edit');
        Route::post('/mitra/profile/update', [AuthController::class, 'updateProfile'])->name('mitra.profile.update');
        Route::get('/mitra/profile/password', [AuthController::class, 'editPassword'])->name('mitra.profile.password');
        Route::post('/mitra/profile/password/update', [AuthController::class, 'updatePassword'])->name('profile.password.update');
        Route::get('/mitra/bantuan', function () { return view('mitra.bantuan.index'); })->name('mitra.bantuan.index');
        Route::get('/mitra/bantuan/panduan', function () { return view('mitra.bantuan.guide'); })->name('mitra.bantuan.panduan');
    });

    // 3. Super Admin
    Route::middleware(RoleMiddleware::class.':super_admin')->group(function () {
        Route::get('/superadmin/dashboard', [AuthController::class, 'superadminDashboard'])->name('superadmin.dashboard');
        Route::get('/superadmin/tambah-kost', [KostController::class, 'create'])->name('tambah.kost');
        Route::post('/superadmin/store-kost', [KostController::class, 'store'])->name('store.kost');
        Route::get('/superadmin/daftar-kost', [KostController::class, 'index'])->name('daftar.kost');
        Route::delete('/superadmin/kost/{id}', [KostController::class, 'destroy'])->name('kost.destroy');
        Route::get('/superadmin/kost/{id}/edit', [KostController::class, 'edit'])->name('kost.edit');
        Route::put('/superadmin/kost/{id}', [KostController::class, 'update'])->name('kost.update');
        Route::get('/superadmin/kelola-detail', [KostController::class, 'manageDetailIndex'])->name('kost.manage-index');
        Route::get('/superadmin/kelola-detail/{id}', [KostController::class, 'manageDetailEdit'])->name('kost.manage-edit');
        Route::put('/superadmin/kelola-detail/{id}', [KostController::class, 'manageDetailUpdate'])->name('kost.manage-update');
        
        Route::get('/superadmin/kelola-landing', [LandingController::class, 'manage'])->name('landing.manage');
        Route::post('/superadmin/landing/update', [LandingController::class, 'updateInfo'])->name('landing.update_info');
        Route::post('/superadmin/landing/filter/{kategori}', [LandingController::class, 'updateFilter'])->name('landing.filter.update');
        Route::post('/superadmin/landing/banner/{index}', [LandingController::class, 'uploadBanner'])->name('landing.banner.upload');
        Route::delete('/superadmin/landing/banner/{index}', [LandingController::class, 'deleteBanner'])->name('landing.banner.delete');

        Route::delete('/admin/landing/filter/photo/{category}/{photo_index}', [LandingController::class, 'deletePhoto'])->name('landing.filter.delete_photo');
        Route::delete('/admin/landing/filter/clear-all/{category}', [LandingController::class, 'clearAllPhotos'])->name('landing.filter.clear_all');

        Route::get('/superadmin/kelola-review', [KostController::class, 'manageReviewIndex'])->name('review.manage');
        Route::get('/superadmin/review/lihat/{id}', [KostController::class, 'viewReviews'])->name('review.view');
        Route::get('/superadmin/review/kelola/{id}', [KostController::class, 'manageReviews'])->name('review.manage-list');
        Route::delete('/superadmin/review/delete/{id}', [KostController::class, 'deleteReview'])->name('review.delete');
        
        Route::get('/superadmin/mitra-dashboard', [AuthController::class, 'mitraDashboardList'])->name('mitra.dashboard.admin');
        Route::get('/superadmin/users', [AuthController::class, 'manageUsers'])->name('users.manage');
        Route::get('/superadmin/mitra/verifikasi', [AuthController::class, 'manageMitra'])->name('mitra.manage');
    });
    
    Route::post('/kost/bookmark', [KostController::class, 'toggleBookmark'])->name('kost.bookmark');
});