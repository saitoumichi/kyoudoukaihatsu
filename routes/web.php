<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\FreeMarketController;
use Illuminate\Support\Facades\Route;

// トップページ
Route::get('/', function () {
    return redirect()->route('places.index');
});

// ダッシュボード
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 場所管理（認証必須）
Route::middleware(['auth', 'verified'])->group(function () {
    // 場所のCRUD
    Route::resource('places', PlaceController::class);

    // タイプ別一覧
    Route::get('/places/type/{type}', [PlaceController::class, 'byType'])->name('places.by-type');

    // 検索
    Route::get('/places/search', [PlaceController::class, 'search'])->name('places.search');

    // カテゴリ管理
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/sort', [CategoryController::class, 'updateSort'])->name('categories.sort');
    Route::patch('/categories/{category}/toggle-active', [CategoryController::class, 'toggleActive'])->name('categories.toggle-active');

    // 画像管理
    Route::post('/places/{place}/images', [ImageController::class, 'upload'])->name('images.upload');
    Route::delete('/images/{image}', [ImageController::class, 'destroy'])->name('images.destroy');
    Route::post('/places/{place}/images/sort', [ImageController::class, 'updateSort'])->name('images.sort');
    Route::patch('/images/{image}/alt-text', [ImageController::class, 'updateAltText'])->name('images.alt-text');
    Route::get('/places/{place}/images', [ImageController::class, 'index'])->name('images.index');
});

// 一般公開（認証不要）
Route::get('/places', [PlaceController::class, 'index'])->name('places.index');
Route::get('/places/{place}', [PlaceController::class, 'show'])->name('places.show');

// フリマ機能（一覧・詳細は認証不要）
Route::get('/free', [FreeMarketController::class, 'index'])->name('freemarket.index');
Route::get('/free/buy', [FreeMarketController::class, 'buy'])->name('freemarket.buy');
Route::get('/free/{id}', [FreeMarketController::class, 'show'])->name('freemarket.show');

// フリマ機能（認証必須）
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/free/dm', [FreeMarketController::class, 'dm'])->name('freemarket.dm');
    Route::get('/free/create', [FreeMarketController::class, 'create'])->name('freemarket.create');
    Route::post('/free', [FreeMarketController::class, 'store'])->name('freemarket.store');
    Route::get('/free/my', [FreeMarketController::class, 'my'])->name('freemarket.my');
    Route::get('/free/my/{id}', [FreeMarketController::class, 'myShow'])->name('freemarket.my.show');
    Route::get('/free/my/{id}/edit', [FreeMarketController::class, 'edit'])->name('freemarket.my.edit');
    Route::put('/free/my/{id}', [FreeMarketController::class, 'update'])->name('freemarket.my.update');
    Route::delete('/free/my/{id}', [FreeMarketController::class, 'destroy'])->name('freemarket.my.destroy');
});

// プロフィール管理
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
