<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MyController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\FreeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// トップページ
Route::get('/', function () {
    return redirect()->route('login');
});

// 認証関連ルート
// ログインページ
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// ユーザー登録ページ
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// パスワード忘れた方フォーム
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);

// 新パスワード設定
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.update');

// マイページ関連ルート（認証必須）
Route::middleware(['auth'])->group(function () {
    // マイページ
    Route::get('/my', [MyController::class, 'index'])->name('my.index');
    // ログアウト
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 掲載作成（フリマ以外）
    Route::get('/my/places/create', [MyController::class, 'createPlace'])->name('my.places.create');
    Route::post('/my/places', [MyController::class, 'storePlace'])->name('my.places.store');
    Route::get('/my/places/{place}/edit', [MyController::class, 'editPlace'])->name('my.places.edit');
    Route::put('/my/places/{place}', [MyController::class, 'updatePlace'])->name('my.places.update');
    Route::delete('/my/places/{place}', [MyController::class, 'destroyPlace'])->name('my.places.destroy');
    Route::get('/my/places/{place}', [MyController::class, 'showPlace'])->name('my.places.show');
    Route::get('/my/places', [MyController::class, 'places'])->name('my.places.index'); // 掲載一覧

    // 掲載作成（フリマ）
    Route::get('/my/free/create', [MyController::class, 'createFree'])->name('my.free.create');
    Route::post('/my/free', [MyController::class, 'storeFree'])->name('my.free.store');
    Route::get('/my/free/{id}/edit', [MyController::class, 'editFree'])->name('my.free.edit');
    Route::delete('/my/free/{id}', [MyController::class, 'destroyFree'])->name('my.free.destroy');
    Route::get('/my/free/{id}/messages', [MyController::class, 'freeMessages'])->name('my.free.messages'); // メッセージ一覧
    Route::get('/my/free/{id}', [MyController::class, 'showFree'])->name('my.free.show');
    Route::get('/my/free', [MyController::class, 'free'])->name('my.free.index'); // 出品一覧

    // DM一覧
    Route::get('/my/messages', [MyController::class, 'allMessages'])->name('my.messages');
});

// 場所一覧（認証不要）
// これを先に置く（静的 -> 動的 の順）
Route::get('/places', [PlaceController::class, 'index'])
    ->name('places.index.default')
    ->defaults('type', 'all');

Route::get('/places/{type}', [PlaceController::class, 'index'])->name('places.index');
Route::get('/places/{type}/{place}', [PlaceController::class, 'show'])->name('places.show');

// フリマ商品一覧（認証不要）
Route::get('/free', [FreeController::class, 'index'])->name('free.index');
Route::get('/free/{item}', [FreeController::class, 'show'])->name('free.show');

// フリマ機能（認証必須）
Route::middleware(['auth'])->group(function () {
    Route::post('/free/{item}/buy', [FreeController::class, 'buy'])->name('free.buy');
    Route::get('/free/{item}/dm', [FreeController::class, 'dm'])->name('free.dm');
    Route::post('/free/{item}/dm', [FreeController::class, 'sendMessage'])->name('free.dm.send');
    Route::post('/free/{item}/dm/close', [FreeController::class, 'closeDm'])->name('free.dm.close');
    Route::get('/free/{item}/status', [FreeController::class, 'status'])->name('free.status');
    Route::put('/free/{item}/status', [FreeController::class, 'updateStatus'])->name('free.status.update');
    Route::put('/free/{item}', [FreeController::class, 'update'])->name('free.update');
});

// require __DIR__.'/auth.php'; // 重複を避けるため無効化

Route::get("/health-ping", fn() => response("pong",200));

Route::fallback(function(Request $r){
    return response()->json([
        "fallback" => true,
        "path"     => $r->path(),
        "host"     => $r->getHost(),
        "headers"  => $r->headers->all(),
    ], 404);
});

Route::get("/debug", function(Request $r){
    return response()->json([
        "ok"        => true,
        "host"      => $r->getHost(),
        "url"       => (string) $r->fullUrl(),
        "app_url"   => env("APP_URL"),
        "app_env"   => env("APP_ENV"),
        "routes"    => collect(Route::getRoutes())->map->uri()->take(5),
    ], 200);
});
