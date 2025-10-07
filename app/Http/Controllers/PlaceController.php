<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlaceController extends Controller
{
    /**
     * 場所一覧表示（タイプ別）
     */
    public function index(string $type, Request $request): View
    {
        // タイプが有効かチェック
        if (!in_array($type, ['drive', 'karaoke', 'izakaya'])) {
            abort(404);
        }

        // データベースから該当タイプの場所を取得（画像とユーザー情報を含む）
        $query = Place::where('type', $type)
            ->where('is_active', true)
            ->with(['images', 'user', 'drive.category', 'karaoke', 'izakaya']);

        // ドライブの場合、カテゴリ絞り込み
        if ($type === 'drive' && $request->has('category') && $request->category) {
            $query->whereHas('drive', function($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        $places = $query->latest()->get();

        // ドライブの場合、カテゴリ別にグループ化
        if ($type === 'drive') {
            $placesGrouped = [
                'shopping' => $places->filter(function($place) {
                    return $place->drive && $place->drive->category_id == 1;
                }),
                'scenery' => $places->filter(function($place) {
                    return $place->drive && $place->drive->category_id == 2;
                }),
                'break' => $places->filter(function($place) {
                    return $place->drive && $place->drive->category_id == 3;
                }),
            ];
            return view("bkc.{$type}", compact('places', 'placesGrouped'));
        }

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
