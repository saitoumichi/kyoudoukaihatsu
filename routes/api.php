<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlaceController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ImageController;
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

    // カテゴリ管理（認証必須）
    Route::apiResource('categories', CategoryController::class);
    Route::post('/categories/sort', [CategoryController::class, 'updateSort']);
    Route::patch('/categories/{category}/toggle-active', [CategoryController::class, 'toggleActive']);

    // 画像管理（認証必須）
    Route::post('/places/{place}/images', [ImageController::class, 'upload']);
    Route::delete('/images/{image}', [ImageController::class, 'destroy']);
    Route::post('/places/{place}/images/sort', [ImageController::class, 'updateSort']);
    Route::patch('/images/{image}/alt-text', [ImageController::class, 'updateAltText']);
    Route::get('/places/{place}/images', [ImageController::class, 'index']);
    Route::get('/images/{image}', [ImageController::class, 'show']);
});

// 一般公開API（認証不要）
Route::get('/places', [PlaceController::class, 'index']);
Route::get('/places/{place}', [PlaceController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/active', [CategoryController::class, 'active']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
