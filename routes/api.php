<?php

use App\Http\Controllers\Api\ConcertController;
use App\Http\Controllers\Api\ArtistController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\HighlightController;
use App\Http\Controllers\Admin\ScraperController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/provinces', [ProvinceController::class, 'index']);

// Concert Routes
Route::post('/concerts', [ConcertController::class, 'store']);
Route::post('/concerts/cleanup', [ConcertController::class, 'cleanup']);

// Artist Routes
Route::post('/artists', [ArtistController::class, 'store']);

// Highlight Sync Route
Route::post('/highlights/sync', [HighlightController::class, 'sync']);

// Scraper Route
Route::post('/scraper/update/{job}', [ScraperController::class, 'updateStatus']);
