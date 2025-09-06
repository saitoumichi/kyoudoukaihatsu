
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\DriveCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PlaceController extends Controller
{
    /**
     * 場所一覧取得
     */
    public function index(Request $request): JsonResponse
    {
        $query = Place::with(['images', 'drive.category', 'karaoke', 'izakaya']);

        // タイプ別フィルタ
        if ($request->has('type') && $request->type) {
            $query->ofType($request->type);
        }

        // カテゴリ別フィルタ（ドライブのみ）
        if ($request->has('category_id') && $request->category_id) {
            $query->whereHas('drive', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // 検索
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('kana', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        // ソート
        $sort = $request->get('sort', 'recommended');
        switch ($sort) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'rating':
                $query->orderBy('rating_avg', 'desc');
                break;
            case 'campus_time':
                $query->byCampusTime();
                break;
            default:
                $query->recommended();
        }

        $places = $query->paginate($request->get('per_page', 12));
        $categories = DriveCategory::active()->orderBy('sort')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'places' => $places,
                'categories' => $categories,
            ]
        ]);
    }

    /**
     * 場所詳細取得
     */
    public function show(Place $place): JsonResponse
    {
        $place->load(['images', 'drive.category', 'karaoke', 'izakaya']);

        return response()->json([
            'success' => true,
            'data' => $place
        ]);
    }

    /**
     * 場所作成
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'kana' => 'nullable|string|max:120',
            'tel' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|url|max:255',
            'tags' => 'nullable|string|max:255',
            'reason' => 'nullable|string|max:255',
            'campus_time_min' => 'nullable|integer|min:0',
            'type' => 'required|in:drive,karaoke,izakaya',
            'is_active' => 'boolean',
            // ドライブ固有フィールド
            'category_id' => 'required_if:type,drive|exists:drive_categories,id',
            // カラオケ固有フィールド
            'price_min' => 'nullable|integer|min:0',
            'price_max' => 'nullable|integer|min:0|gte:price_min',
            'has_all_you_can_drink' => 'boolean',
            'byo_allowed' => 'boolean',
            'machine_types' => 'nullable|array',
            // 居酒屋固有フィールド
            'alcohol_types' => 'nullable|string|max:120',
        ]);

        // デフォルト値設定
        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['score'] = 0;
        $validated['rating_avg'] = 0;
        $validated['rating_count'] = 0;
        $validated['recommend_score'] = 0;

        $place = Place::create($validated);

        // タイプ別の詳細情報を作成
        if ($validated['type'] === 'drive' && isset($validated['category_id'])) {
            $place->drive()->create(['category_id' => $validated['category_id']]);
        } elseif ($validated['type'] === 'karaoke') {
            $place->karaoke()->create([
                'price_min' => $validated['price_min'] ?? null,
                'price_max' => $validated['price_max'] ?? null,
                'has_all_you_can_drink' => $validated['has_all_you_can_drink'] ?? false,
                'byo_allowed' => $validated['byo_allowed'] ?? false,
                'machine_types' => $validated['machine_types'] ?? [],
            ]);
        } elseif ($validated['type'] === 'izakaya') {
            $place->izakaya()->create([
                'price_min' => $validated['price_min'] ?? null,
                'price_max' => $validated['price_max'] ?? null,
                'has_all_you_can_drink' => $validated['has_all_you_can_drink'] ?? false,
                'byo_allowed' => $validated['byo_allowed'] ?? false,
                'alcohol_types' => $validated['alcohol_types'] ?? null,
            ]);
        }

        $place->load(['images', 'drive.category', 'karaoke', 'izakaya']);

        return response()->json([
            'success' => true,
            'message' => '場所を登録しました。',
            'data' => $place
        ], 201);
    }

    /**
     * 場所更新
     */
    public function update(Request $request, Place $place): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'kana' => 'nullable|string|max:120',
            'tel' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|url|max:255',
            'tags' => 'nullable|string|max:255',
            'reason' => 'nullable|string|max:255',
            'campus_time_min' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            // ドライブ固有フィールド
            'category_id' => 'required_if:type,drive|exists:drive_categories,id',
            // カラオケ固有フィールド
            'price_min' => 'nullable|integer|min:0',
            'price_max' => 'nullable|integer|min:0|gte:price_min',
            'has_all_you_can_drink' => 'boolean',
            'byo_allowed' => 'boolean',
            'machine_types' => 'nullable|array',
            // 居酒屋固有フィールド
            'alcohol_types' => 'nullable|string|max:120',
        ]);

        $place->update($validated);

        // タイプ別の詳細情報を更新
        if ($place->type === 'drive' && isset($validated['category_id'])) {
            $place->drive()->updateOrCreate(
                ['place_id' => $place->id],
                ['category_id' => $validated['category_id']]
            );
        } elseif ($place->type === 'karaoke') {
            $place->karaoke()->updateOrCreate(
                ['place_id' => $place->id],
                [
                    'price_min' => $validated['price_min'] ?? null,
                    'price_max' => $validated['price_max'] ?? null,
                    'has_all_you_can_drink' => $validated['has_all_you_can_drink'] ?? false,
                    'byo_allowed' => $validated['byo_allowed'] ?? false,
                    'machine_types' => $validated['machine_types'] ?? [],
                ]
            );
        } elseif ($place->type === 'izakaya') {
            $place->izakaya()->updateOrCreate(
                ['place_id' => $place->id],
                [
                    'price_min' => $validated['price_min'] ?? null,
                    'price_max' => $validated['price_max'] ?? null,
                    'has_all_you_can_drink' => $validated['has_all_you_can_drink'] ?? false,
                    'byo_allowed' => $validated['byo_allowed'] ?? false,
                    'alcohol_types' => $validated['alcohol_types'] ?? null,
                ]
            );
        }

        $place->load(['images', 'drive.category', 'karaoke', 'izakaya']);

        return response()->json([
            'success' => true,
            'message' => '場所を更新しました。',
            'data' => $place
        ]);
    }

    /**
     * 場所削除
     */
    public function destroy(Place $place): JsonResponse
    {
        $place->delete();

        return response()->json([
            'success' => true,
            'message' => '場所を削除しました。'
        ]);
    }

    /**
     * タイプ別一覧取得
     */
    public function byType(string $type): JsonResponse
    {
        $places = Place::ofType($type)
                      ->active()
                      ->recommended()
                      ->with(['images', 'drive.category', 'karaoke', 'izakaya'])
                      ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $places
        ]);
    }

    /**
     * 検索
     */
    public function search(Request $request): JsonResponse
    {
        $query = Place::active()->with(['images', 'drive.category', 'karaoke', 'izakaya']);

        if ($request->has('q') && $request->q) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('kana', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        $places = $query->recommended()->paginate(12);

        return response()->json([
            'success' => true,
            'data' => [
                'places' => $places,
                'search_query' => $request->q
            ]
        ]);
    }
}
