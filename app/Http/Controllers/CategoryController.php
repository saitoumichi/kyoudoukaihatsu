<?php

namespace App\Http\Controllers;

use App\Models\DriveCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * カテゴリ一覧表示
     */
    public function index(): View
    {
        $categories = DriveCategory::ordered()->paginate(20);
        return view('categories.index', compact('categories'));
    }

    /**
     * 新規作成フォーム表示
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * 新規作成処理
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:60|unique:drive_categories,name',
            'icon' => 'nullable|string|max:60',
            'sort' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // デフォルト値設定
        $validated['is_active'] = $validated['is_active'] ?? true;

        DriveCategory::create($validated);

        return redirect()->route('categories.index')
                        ->with('success', 'カテゴリを登録しました。');
    }

    /**
     * 詳細表示
     */
    public function show(DriveCategory $category): View
    {
        $category->load(['drives.place']);
        return view('categories.show', compact('category'));
    }

    /**
     * 編集フォーム表示
     */
    public function edit(DriveCategory $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * 更新処理
     */
    public function update(Request $request, DriveCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:60|unique:drive_categories,name,' . $category->id,
            'icon' => 'nullable|string|max:60',
            'sort' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);

        return redirect()->route('categories.show', $category)
                        ->with('success', 'カテゴリを更新しました。');
    }

    /**
     * 削除処理
     */
    public function destroy(DriveCategory $category): RedirectResponse
    {
        // 関連するドライブスポットがあるかチェック
        if ($category->drives()->count() > 0) {
            return redirect()->route('categories.index')
                            ->with('error', 'このカテゴリに関連するドライブスポットが存在するため、削除できません。');
        }

        $category->delete();

        return redirect()->route('categories.index')
                        ->with('success', 'カテゴリを削除しました。');
    }

    /**
     * 並び順更新
     */
    public function updateSort(Request $request): RedirectResponse
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:drive_categories,id',
            'categories.*.sort' => 'required|integer|min:1',
        ]);

        foreach ($request->categories as $categoryData) {
            DriveCategory::where('id', $categoryData['id'])
                        ->update(['sort' => $categoryData['sort']]);
        }

        return redirect()->route('categories.index')
                        ->with('success', '並び順を更新しました。');
    }

    /**
     * アクティブ状態切り替え
     */
    public function toggleActive(DriveCategory $category): RedirectResponse
    {
        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? '有効' : '無効';
        return redirect()->route('categories.index')
                        ->with('success', "カテゴリを{$status}にしました。");
    }
}
