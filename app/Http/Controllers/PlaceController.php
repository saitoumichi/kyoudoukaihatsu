<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\View\View;

class PlaceController extends Controller
{
    /**
     * 場所一覧表示（タイプ別）
     */
    public function index(string $type): View
    {
        // タイプが有効かチェック
        if (!in_array($type, ['drive', 'karaoke', 'izakaya'])) {
            abort(404);
        }

        // データベースから該当タイプの場所を取得（画像とユーザー情報を含む）
        $places = Place::where('type', $type)
            ->where('is_active', true)
            ->with(['images', 'user'])
            ->latest()
            ->get();

        // 既存のビューファイルを使用
        return view("bkc.{$type}", compact('places'));
    }

    /**
     * 詳細表示（タイプ別）
     */
    public function show(string $type, Place $place): View
    {
        // タイプが有効かチェック
        if (!in_array($type, ['drive', 'karaoke', 'izakaya'])) {
            abort(404);
        }
        // URLのタイプと場所のタイプが一致するかチェック
        if ($place->type !== $type) {
            abort(404);
        }

        $place->load(['images', 'drive.category', 'karaoke', 'izakaya']);
        return view('places.show', compact('place', 'type'));
    }
}
