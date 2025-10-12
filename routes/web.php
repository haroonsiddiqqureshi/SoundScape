<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

// --- Admin Controllers ---
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ConcertController as AdminConcertController;
use App\Http\Controllers\Admin\PromoterController as AdminPromoterController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\HighlightController as AdminHighlightController;

// --- Promoter Controllers ---
use App\Http\Controllers\Promoter\PromoterController;
use App\Http\Controllers\Promoter\ConcertController as PromoterConcertController;

// --- User Controllers ---
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ProfileController as UserProfileController;

Route::middleware([
    'block_admin'
])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');

    // Add other routes accessible to both users and guests here...
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'block_admin',
    'role:web'
])->group(function () {
    Route::get('/user/profile', [UserProfileController::class, 'show'])->name('user.profile.show');

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
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile.show');
    Route::get('/user', [AdminUserController::class, 'index'])->name('user.index');

    // --- Admin Concert Management Routes ---
    Route::get('/concert', [AdminConcertController::class, 'index'])->name('concert.index');
    Route::get('/concert/create', [AdminConcertController::class, 'create'])->name('concert.create');
    Route::post('/concert', [AdminConcertController::class, 'store'])->name('concert.store');
    Route::get('/concert/{concert}', [AdminConcertController::class, 'detail'])->name('concert.detail');
    Route::get('/concert/edit/{concert}', [AdminConcertController::class, 'edit'])->name('concert.edit');
    Route::post('/concert/{concert}', [AdminConcertController::class, 'update'])->name('concert.update');

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
    Route::patch('highlight/{highlight}/toggle-active', [AdminHighlightController::class, 'updateActiveStatus'])->name('highlight.updateActiveStatus');

    // Add other admin-only routes here...
});
