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
    public function index(string $type = 'all')
    {
        // タイプが有効かチェック
        if (!in_array($type, ['drive', 'karaoke', 'izakaya', 'all'])) {
            abort(404);
        }

        // タイプに応じてデータ取得
        $query = Place::query();
        if ($type !== 'all') {
            $query->where('type', $type);
        }

        $places = $query->latest()->get();

        return response()->json(compact('places', 'type'));
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
