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
    public function index(?string $type = 'all', Request $request): View
    {
        // 全件表示の場合
        if ($type === 'all' || $type === null) {
            $places = Place::where('is_active', true)
                ->with('images')
                ->latest()
                ->get();
            $type = 'all';
            return view('places.index', compact('places', 'type'));
        }

        // タイプが有効かチェック
        if (!in_array($type, ['drive', 'karaoke', 'izakaya'])) {
            abort(404);
        }

        // データベースから該当タイプの場所を取得（画像とドライブ情報もロード）
        $places = Place::where('type', $type)
            ->where('is_active', true)
            ->with(['images', 'drive.category'])
            ->latest()
            ->get();

        // ドライブの場合、カテゴリ別にグループ化
        if ($type === 'drive') {
            $placesGrouped = [];
            $categories = \App\Models\DriveCategory::where('is_active', true)->orderBy('sort')->get();

            foreach ($categories as $category) {
                $placesGrouped[$category->name] = $places->filter(function($place) use ($category) {
                    return $place->drive && $place->drive->category_id === $category->id;
                });
            }

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

        // 画像のみロード（存在するテーブル）
        $place->load(['images']);
        return view('places.show', compact('place', 'type'));
    }
}
