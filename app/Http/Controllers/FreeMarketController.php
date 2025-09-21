<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class FreeMarketController extends Controller
{
    /**
     * フリマ一覧表示
     */
    public function index(Request $request): View
    {
        // ダミーデータ（実際のプロジェクトではデータベースから取得）
        $items = collect([
            (object)[
                'id' => 1,
                'name' => 'MacBook Pro 13インチ',
                'description' => '2020年モデル、使用感少なく状態良好です。',
                'price' => 120000,
                'category' => 'electronics',
                'image' => null,
                'user_id' => 1,
                'user' => (object)['name' => 'Test User'],
                'created_at' => Carbon::now()->subDays(2)
            ],
            (object)[
                'id' => 2,
                'name' => 'Nike Air Max 270',
                'description' => '28cm、数回使用のみ。',
                'price' => 8000,
                'category' => 'fashion',
                'image' => null,
                'user_id' => 1,
                'user' => (object)['name' => 'Test User'],
                'created_at' => Carbon::now()->subDays(5)
            ],
            (object)[
                'id' => 3,
                'name' => 'プログラミングの本',
                'description' => 'Laravel関連の技術書、ほぼ新品。',
                'price' => 3000,
                'category' => 'books',
                'image' => null,
                'user_id' => 1,
                'user' => (object)['name' => 'Test User'],
                'created_at' => Carbon::now()->subWeek()
            ]
        ]);

        // 検索フィルタ
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $items = $items->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->name), strtolower($search)) ||
                       str_contains(strtolower($item->description), strtolower($search));
            });
        }

        // カテゴリフィルタ
        if ($request->has('category') && $request->category) {
            $items = $items->where('category', $request->category);
        }

        // 価格フィルタ
        if ($request->has('price_min') && $request->price_min) {
            $items = $items->where('price', '>=', $request->price_min);
        }

        // ソート
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $items = $items->sortBy('price');
                break;
            case 'price_high':
                $items = $items->sortByDesc('price');
                break;
            case 'name':
                $items = $items->sortBy('name');
                break;
            default:
                $items = $items->sortByDesc('created_at');
        }

        return view('freemarket.index', compact('items'));
    }

    /**
     * 買取一覧表示
     */
    public function buy(): View
    {
        return view('freemarket.buy');
    }

    /**
     * 商品詳細表示
     */
    public function show(string $id): View
    {
        // ダミーデータ（実際のプロジェクトではデータベースから取得）
        $item = (object)[
            'id' => $id,
            'name' => 'MacBook Pro 13インチ',
            'description' => '2020年モデル、使用感少なく状態良好です。付属品も全て揃っています。',
            'price' => 120000,
            'category' => 'electronics',
            'image' => null,
            'user_id' => 1,
            'user' => (object)['name' => 'Test User'],
            'created_at' => Carbon::now()->subDays(2)
        ];

        return view('freemarket.show', compact('item'));
    }

    /**
     * DM一覧表示
     */
    public function dm(): View
    {
        return view('freemarket.dm');
    }

    /**
     * 出品フォーム表示
     */
    public function create(): View
    {
        return view('freemarket.create');
    }

    /**
     * 出品処理
     */
    public function store(Request $request): RedirectResponse
    {
        // TODO: 出品処理の実装
        return redirect()->route('freemarket.my')
                        ->with('success', '出品しました。');
    }

    /**
     * 自分の出品物一覧
     */
    public function my(): View
    {
        return view('freemarket.my');
    }

    /**
     * 自分の出品物詳細
     */
    public function myShow(string $id): View
    {
        return view('freemarket.my-show', compact('id'));
    }

    /**
     * 自分の出品物編集フォーム
     */
    public function edit(string $id): View
    {
        return view('freemarket.edit', compact('id'));
    }

    /**
     * 自分の出品物更新
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        // TODO: 更新処理の実装
        return redirect()->route('freemarket.my.show', $id)
                        ->with('success', '更新しました。');
    }

    /**
     * 自分の出品物削除
     */
    public function destroy(string $id): RedirectResponse
    {
        // TODO: 削除処理の実装
        return redirect()->route('freemarket.my')
                        ->with('success', '削除しました。');
    }
}
