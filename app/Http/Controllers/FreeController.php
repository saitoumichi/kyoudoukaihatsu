<?php

namespace App\Http\Controllers;

use App\Models\FreeMarket;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FreeController extends Controller
{
    /**
     * フリマ商品一覧表示
     */
    public function index(Request $request): View
    {
        $query = FreeMarket::active();

        // カテゴリフィルター
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }

        // 検索機能
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // 価格フィルター（最低価格）
        if ($request->has('price_min') && $request->price_min !== null && $request->price_min !== '') {
            $query->where('price', '>=', $request->price_min);
        }

        // 価格フィルター（最高価格）
        if ($request->has('price_max') && $request->price_max !== null && $request->price_max !== '') {
            $query->where('price', '<=', $request->price_max);
        }

        // ソート機能
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('title', 'asc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $items = $query->with('user')->get();
        $categories = FreeMarket::categories()->pluck('category');

        return view('free.index', compact('items', 'categories'));
    }

    /**
     * 詳細表示
     */
    public function show($id): View
    {
        $free = FreeMarket::with('user')->findOrFail($id);
        return view('free.show', compact('free'));
    }

    /**
     * 買取処理
     */
    public function buy(Request $request, $id)
    {
        return redirect()->route('free.dm', $id)->with('success', '買取リクエストを送信しました。');
    }

    /**
     * DM表示
     */
    public function dm($id): View
    {
        // 簡単なテストデータを返す
        $free = (object)[
            'id' => $id,
            'title' => 'テスト商品',
            'user' => (object)['name' => 'テストユーザー']
        ];
        $messages = collect([]);

        return view('free.dm', compact('free', 'messages'));
    }

    /**
     * DMメッセージ送信
     */
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // 実際の実装では、ここでメッセージをデータベースに保存
        // 現在はテスト用のリダイレクト

        return redirect()->route('free.dm', $id)->with('success', 'メッセージを送信しました。');
    }

    /**
     * DMやり取り終了
     */
    public function closeDm(Request $request, $id)
    {
        return redirect()->route('free.show', $id)->with('success', 'やり取りを終了しました。');
    }

    /**
     * 手続き状況表示
     */
    public function status($id): View
    {
        // 簡単なテストデータを返す
        $free = (object)[
            'id' => $id,
            'title' => 'テスト商品',
            'user' => (object)['name' => 'テストユーザー']
        ];
        $status = 'negotiating'; // 例：交渉中

        return view('free.status', compact('free', 'status'));
    }

    /**
     * 手続き情報更新
     */
    public function updateStatus(Request $request, $id)
    {
        return redirect()->route('free.status', $id)->with('success', '手続き状況を更新しました。');
    }

    /**
     * 商品情報更新
     */
    public function update(Request $request, $id)
    {
        return redirect()->route('free.show', $id)->with('success', '商品情報を更新しました。');
    }
}
