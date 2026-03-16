<?php

use Illuminate\Support\Facades\Route;

// --- Admin Controllers ---
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ConcertController as AdminConcertController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\PromoterController as AdminPromoterController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\HighlightController as AdminHighlightController;
use App\Http\Controllers\Admin\ArtistController as AdminArtistController;

// --- Promoter Controllers ---
use App\Http\Controllers\Promoter\PromoterController;
use App\Http\Controllers\Promoter\ConcertController as PromoterConcertController;
use App\Http\Controllers\Promoter\CommentController as PromoterCommentController;


// --- User Controllers ---
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\MapController;
use App\Http\Controllers\User\CommentController;

// --- Line Controller ---
use App\Http\Controllers\LineIntegrationController;

Route::middleware([
    'block_admin'
])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/concert/{concert}', [HomeController::class, 'concertDetail'])->name('concert.detail');
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
    Route::post('/concert/{concert}/comments', [CommentController::class, 'store'])->name('concert.comment.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('concert.comment.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('concert.comment.destroy');
    // Add other user-only routes here...

    Route::middleware('check_promoter')->group(function () {
        Route::get('/promoter', [PromoterController::class, 'index'])->name('promoter.index');
        Route::put('/promoter', [PromoterController::class, 'update'])->name('promoter.update');

        Route::middleware(['verified_promoter'])->prefix('promoter')->name('promoter.')->group(function () {
            // --- Promoter Concert Management Routes ---
            Route::get('/profile', [PromoterController::class, 'edit'])->name('profile');
            Route::get('/concert', [PromoterConcertController::class, 'index'])->name('concert.index');
            Route::get('/concert/create', [PromoterConcertController::class, 'create'])->name('concert.create');
            Route::post('/concert', [PromoterConcertController::class, 'store'])->name('concert.store');
            Route::get('/concert/{concert}', [PromoterConcertController::class, 'detail'])->name('concert.detail');
            Route::get('/concert/edit/{concert}', [PromoterConcertController::class, 'edit'])->name('concert.edit');
            Route::post('/concert/{concert}', [PromoterConcertController::class, 'update'])->name('concert.update');
            Route::delete('/concert/{concert}', [PromoterConcertController::class, 'destroy'])->name('concert.delete');
            Route::post('/concert-comments/{concert}', [PromoterCommentController::class, 'store'])->name('concert.comment.store');
            Route::put('/concert-comments/{comment}', [PromoterCommentController::class, 'update'])->name('concert.comment.update');
            Route::delete('/concert-comments/{comment}', [PromoterCommentController::class, 'destroy'])->name('concert.comment.destroy');

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
    Route::post('/concert/{concert}/comments', [AdminCommentController::class, 'store'])->name('concert.comment.store');
    Route::put('/comments/{comment}', [AdminCommentController::class, 'update'])->name('concert.comment.update');
    Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])->name('concert.comment.destroy');

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
    Route::put('highlight/reorder', [AdminHighlightController::class, 'reorder'])->name('highlight.reorder');
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

// --- Line ---
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    Route::get('/auth/line/redirect', [LineIntegrationController::class, 'redirect'])->name('line.redirect');
    Route::get('/auth/line/callback', [LineIntegrationController::class, 'callback']);
});

// --- Notification ---
Route::post('/notifications/{id}/read', function ($id, \Illuminate\Http\Request $request) {
    $notification = $request->user()->notifications()->findOrFail($id);
    $notification->markAsRead();
    return back();
})->name('notifications.read')->middleware('auth');
