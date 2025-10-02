<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
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
        return Inertia::render('Profile/Show', [
            'confirmsTwoFactorAuthentication' => false,
            'sessions' => [],
        ]);
    })->name('profile.show');

    // Add other user-only routes here...
});

Route::middleware(['auth:admin', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');

    // Add other admin-only routes here...
});
