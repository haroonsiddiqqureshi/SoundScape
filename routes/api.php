<?php

use App\Http\Controllers\Api\ConcertController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProvinceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/provinces', [ProvinceController::class, 'index']);

Route::post('/concerts', [ConcertController::class, 'store']);