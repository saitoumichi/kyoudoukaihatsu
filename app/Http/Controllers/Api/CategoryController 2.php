<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DriveCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * カテゴリ一覧取得
     */
    public function index(Request $request): JsonResponse
    {
        $query = DriveCategory::with(['drives.place']);

        // アクティブのみフィルタ
        if ($request->has('active_only') && $request->active_only) {
            $query->active();
        }

        // ソート
        $sort = $request->get('sort', 'sort');
        switch ($sort) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'created':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->ordered();
        }

        $categories = $query->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * カテゴリ詳細取得
     */
    public function show(DriveCategory $category): JsonResponse
    {
        $category->load(['drives.place']);

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * カテゴリ作成
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:60|unique:drive_categories,name',
            'icon' => 'nullable|string|max:60',
            'sort' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // デフォルト値設定
        $validated['is_active'] = $validated['is_active'] ?? true;

        $category = DriveCategory::create($validated);
        $category->load(['drives.place']);

        return response()->json([
            'success' => true,
            'message' => 'カテゴリを登録しました。',
            'data' => $category
        ], 201);
    }

    /**
     * カテゴリ更新
     */
    public function update(Request $request, DriveCategory $category): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:60|unique:drive_categories,name,' . $category->id,
            'icon' => 'nullable|string|max:60',
            'sort' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);
        $category->load(['drives.place']);

        return response()->json([
            'success' => true,
            'message' => 'カテゴリを更新しました。',
            'data' => $category
        ]);
    }

    /**
     * カテゴリ削除
     */
    public function destroy(DriveCategory $category): JsonResponse
    {
        // 関連するドライブスポットがあるかチェック
        if ($category->drives()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'このカテゴリに関連するドライブスポットが存在するため、削除できません。'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'カテゴリを削除しました。'
        ]);
    }

    /**
     * 並び順更新
     */
    public function updateSort(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:drive_categories,id',
            'categories.*.sort' => 'required|integer|min:1',
        ]);

        foreach ($validated['categories'] as $categoryData) {
            DriveCategory::where('id', $categoryData['id'])
                        ->update(['sort' => $categoryData['sort']]);
        }

        return response()->json([
            'success' => true,
            'message' => '並び順を更新しました。'
        ]);
    }

    /**
     * アクティブ状態切り替え
     */
    public function toggleActive(DriveCategory $category): JsonResponse
    {
        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? '有効' : '無効';

        return response()->json([
            'success' => true,
            'message' => "カテゴリを{$status}にしました。",
            'data' => $category
        ]);
    }

    /**
     * アクティブなカテゴリ一覧取得（選択肢用）
     */
    public function active(): JsonResponse
    {
        $categories = DriveCategory::active()
                                 ->ordered()
                                 ->select('id', 'name', 'icon')
                                 ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}
