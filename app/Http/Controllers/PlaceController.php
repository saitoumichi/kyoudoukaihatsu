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
    public function index(?string $type = 'all')
    {
        if ($type === null || $type === 'all') {
            return response('OK /places (all)', 200);
        }
        return response("OK /places type={$type}", 200);
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
