<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ConcertController as AdminConcertController;
use App\Http\Controllers\Admin\PromoterController as AdminPromoterController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Promoter\PromoterController;
use App\Http\Controllers\Promoter\ConcertController as PromoterConcertController;
use App\Models\Admin;
use Inertia\Inertia;

Route::middleware([
    'block_admin'
])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Add other routes accessible to both users and guests here...
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'block_admin',
    'role:web'
])->group(function () {
    Route::get('/user/profile', function () {
        return Inertia::render('User/Profile/Show', [
            'confirmsTwoFactorAuthentication' => false,
            'sessions' => [],
        ]);
    })->name('user.profile.show');


    Route::middleware('check_promoter')->group(function () {
        Route::get('/promoter', [PromoterController::class, 'index'])->name('promoter.index');

        Route::middleware('verified_promoter')->prefix('promoter')->name('promoter.')->group(function () {
            Route::get('/concert', [PromoterConcertController::class, 'index'])->name('concert.index');
            Route::get('/concert/create', [PromoterConcertController::class, 'create'])->name('concert.create');
            Route::post('/concert', [PromoterConcertController::class, 'store'])->name('concert.store');
            Route::get('/concert/{concert}', [PromoterConcertController::class, 'detail'])->name('concert.detail');
            
            // Add other promoter-only routes here...
        });
    });
    Route::get('/promoter/create', [PromoterController::class, 'create'])->name('promoter.create');
    Route::post('/promoter', [PromoterController::class, 'store'])->name('promoter.store');
    
    // Add other user-only routes here...
});

Route::middleware(['auth:admin', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');

    Route::get('/profile', function () {
        return Inertia::render('Admin/Profile/Show', [
            'confirmsTwoFactorAuthentication' => false,
            'sessions' => [],
        ]);
    })->name('profile.show');

    Route::get('/concert', [AdminConcertController::class, 'index'])->name('concert.index');
    Route::get('/concert/create', [AdminConcertController::class, 'create'])->name('concert.create');
    Route::post('/concert', [AdminConcertController::class, 'store'])->name('concert.store');

    Route::get('/promoter', [AdminPromoterController::class, 'index'])->name('promoter.index');
    Route::get('/promoter/{promoter}', [AdminPromoterController::class, 'detail'])->name('promoter.detail');
    Route::put('/promoter/{promoter}', [AdminPromoterController::class, 'updateVerificationStatus'])->name('promoter.updateVerificationStatus');

    Route::get('/user', [AdminUserController::class, 'index'])->name('user.index');

    Route::get('/concert/{concert}', [AdminConcertController::class, 'detail'])->name('concert.detail');
});
