<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 認証API（認証不要）
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// 認証が必要なAPI
Route::middleware('auth:sanctum')->group(function () {
    // 認証関連
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);

    // 場所管理（認証必須）
    Route::apiResource('places', PlaceController::class);
    Route::get('/places/type/{type}', [PlaceController::class, 'byType']);
    Route::get('/places/search', [PlaceController::class, 'search']);
});

// 一般公開API（認証不要）
Route::get('/places', [PlaceController::class, 'index']);
Route::get('/places/{place}', [PlaceController::class, 'show']);
