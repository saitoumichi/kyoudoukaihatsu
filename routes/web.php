<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlaceController;
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
});

// 一般公開（認証不要）
Route::get('/places', [PlaceController::class, 'index'])->name('places.index');
Route::get('/places/{place}', [PlaceController::class, 'show'])->name('places.show');

// プロフィール管理
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
