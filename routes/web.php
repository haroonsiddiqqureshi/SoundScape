<?php

use Illuminate\Support\Facades\Route;

// --- Admin Controllers ---
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ConcertController as AdminConcertController;
use App\Http\Controllers\Admin\PromoterController as AdminPromoterController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\HighlightController as AdminHighlightController;
use App\Http\Controllers\Admin\ArtistController as AdminArtistController;

// --- Promoter Controllers ---
use App\Http\Controllers\Promoter\PromoterController;
use App\Http\Controllers\Promoter\ConcertController as PromoterConcertController;

// --- User Controllers ---
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\MapController;

Route::middleware([
    'block_admin'
])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/concert/{concert:name}', [HomeController::class, 'concertDetail'])->name('concert.detail');
    Route::get('/map', [MapController::class, 'index'])->name('map.index');
    Route::get('/search-query', [HomeController::class, 'searchQuery'])->name('api.search');
    // Add other routes accessible to both users and guests here...
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'block_admin',
    'role:web'
])->group(function () {
    Route::post('/concert/{concert}', [HomeController::class, 'followConcert'])->name('concert.follow');

    // Add other user-only routes here...

    Route::middleware('check_promoter')->group(function () {
        Route::get('/promoter', [PromoterController::class, 'index'])->name('promoter.index');

        Route::middleware(['verified_promoter'])->prefix('promoter')->name('promoter.')->group(function () {
            // --- Promoter Concert Management Routes ---
            Route::get('/concert', [PromoterConcertController::class, 'index'])->name('concert.index');
            Route::get('/concert/create', [PromoterConcertController::class, 'create'])->name('concert.create');
            Route::post('/concert', [PromoterConcertController::class, 'store'])->name('concert.store');
            Route::get('/concert/{concert}', [PromoterConcertController::class, 'detail'])->name('concert.detail');
            Route::get('/concert/edit/{concert}', [PromoterConcertController::class, 'edit'])->name('concert.edit');
            Route::post('/concert/{concert}', [PromoterConcertController::class, 'update'])->name('concert.update');
            Route::delete('/concert/{concert}', [PromoterConcertController::class, 'destroy'])->name('concert.delete');

            // Add other promoter-only routes here...
        });
    });
    Route::middleware(['verified'])->group(function () {
        Route::get('/promoter/create', [PromoterController::class, 'create'])->name('promoter.create');
        Route::post('/promoter', [PromoterController::class, 'store'])->name('promoter.store');
    });
});

Route::middleware(['auth:admin', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

    // --- Admin Concert Management Routes ---
    Route::get('/concert', [AdminConcertController::class, 'index'])->name('concert.index');
    Route::get('/concert/create', [AdminConcertController::class, 'create'])->name('concert.create');
    Route::post('/concert', [AdminConcertController::class, 'store'])->name('concert.store');
    Route::get('/concert/{concert}', [AdminConcertController::class, 'detail'])->name('concert.detail');
    Route::get('/concert/edit/{concert}', [AdminConcertController::class, 'edit'])->name('concert.edit');
    Route::post('/concert/{concert}', [AdminConcertController::class, 'update'])->name('concert.update');
    Route::delete('/concert/{concert}', [AdminConcertController::class, 'destroy'])->name('concert.delete');

    // --- Admin User Management Routes ---
    Route::get('/user', [AdminUserController::class, 'index'])->name('user.index');

    // --- Admin Promoter Management Routes ---
    Route::get('/promoter', [AdminPromoterController::class, 'index'])->name('promoter.index');
    Route::get('/promoter/{promoter}', [AdminPromoterController::class, 'detail'])->name('promoter.detail');
    Route::put('/promoter/{promoter}', [AdminPromoterController::class, 'updateVerificationStatus'])->name('promoter.updateVerificationStatus');

    // --- Admin Highlight Management Routes ---
    Route::get('/highlight', [AdminHighlightController::class, 'index'])->name('highlight.index');
    Route::get('/highlight/create', [AdminHighlightController::class, 'create'])->name('highlight.create');
    Route::post('/highlight', [AdminHighlightController::class, 'store'])->name('highlight.store');
    Route::get('/highlight/edit/{highlight}', [AdminHighlightController::class, 'edit'])->name('highlight.edit');
    Route::post('/highlight/{highlight}', [AdminHighlightController::class, 'update'])->name('highlight.update');
    Route::put('highlight/{highlight}', [AdminHighlightController::class, 'updateActiveStatus'])->name('highlight.updateActiveStatus');
    Route::delete('/highlight/{highlight}', [AdminHighlightController::class, 'destroy'])->name('highlight.delete');

    // --- Admin Artist Management Routes ---
    Route::get('/artist', [AdminArtistController::class, 'index'])->name('artist.index');
    Route::get('/artist/create', [AdminArtistController::class, 'create'])->name('artist.create');
    Route::post('/artist', [AdminArtistController::class, 'store'])->name('artist.store');
    Route::get('/artist/edit/{artist}', [AdminArtistController::class, 'edit'])->name('artist.edit');
    Route::post('/artist/{artist}', [AdminArtistController::class, 'update'])->name('artist.update');
    Route::delete('/artist/{artist}', [AdminArtistController::class, 'destroy'])->name('artist.delete');

    // Add other admin-only routes here...
});
