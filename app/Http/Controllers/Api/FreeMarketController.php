<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FreeMarketController extends Controller
{
    /**
     * フリマ一覧取得
     */
    public function index(Request $request): JsonResponse
    {
        // ダミーデータ（実際のプロジェクトではデータベースから取得）
        $items = collect([
            [
                'id' => 1,
                'name' => 'MacBook Pro 13インチ',
                'description' => '2020年モデル、使用感少なく状態良好です。',
                'price' => 120000,
                'category' => 'electronics',
                'image' => null,
                'user_id' => 1,
                'user' => ['name' => 'Test User'],
                'created_at' => Carbon::now()->subDays(2)->toISOString()
            ],
            [
                'id' => 2,
                'name' => 'Nike Air Max 270',
                'description' => '28cm、数回使用のみ。',
                'price' => 8000,
                'category' => 'fashion',
                'image' => null,
                'user_id' => 1,
                'user' => ['name' => 'Test User'],
                'created_at' => Carbon::now()->subDays(5)->toISOString()
            ],
            [
                'id' => 3,
                'name' => 'プログラミングの本',
                'description' => 'Laravel関連の技術書、ほぼ新品。',
                'price' => 3000,
                'category' => 'books',
                'image' => null,
                'user_id' => 1,
                'user' => ['name' => 'Test User'],
                'created_at' => Carbon::now()->subDays(1)->toISOString()
            ]
        ]);

        return response()->json([
            'success' => true,
            'data' => $items,
            'message' => 'フリマ一覧を取得しました'
        ]);
    }

    /**
     * フリマ詳細取得
     */
    public function show(string $free): JsonResponse
    {
        // ダミーデータ
        $item = [
            'id' => (int)$free,
            'name' => 'MacBook Pro 13インチ',
            'description' => '2020年モデル、使用感少なく状態良好です。',
            'price' => 120000,
            'category' => 'electronics',
            'image' => null,
            'user_id' => 1,
            'user' => ['name' => 'Test User'],
            'created_at' => Carbon::now()->subDays(2)->toISOString()
        ];

        return response()->json([
            'success' => true,
            'data' => $item,
            'message' => 'フリマ詳細を取得しました'
        ]);
    }

    /**
     * フリマ出品
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:1',
            'category' => 'required|string|max:50'
        ]);

        // 実際のプロジェクトではデータベースに保存
        $item = [
            'id' => rand(1000, 9999),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'user_id' => Auth::id(),
            'created_at' => Carbon::now()->toISOString()
        ];

        return response()->json([
            'success' => true,
            'data' => $item,
            'message' => '出品が完了しました'
        ], 201);
    }

    /**
     * フリマ更新
     */
    public function update(Request $request, string $free): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|integer|min:1',
            'category' => 'sometimes|string|max:50'
        ]);

        // 実際のプロジェクトではデータベースを更新
        $item = [
            'id' => (int)$free,
            'name' => $request->name ?? 'MacBook Pro 13インチ',
            'description' => $request->description ?? '2020年モデル、使用感少なく状態良好です。',
            'price' => $request->price ?? 120000,
            'category' => $request->category ?? 'electronics',
            'user_id' => Auth::id(),
            'updated_at' => Carbon::now()->toISOString()
        ];

        return response()->json([
            'success' => true,
            'data' => $item,
            'message' => '更新が完了しました'
        ]);
    }

    /**
     * フリマ削除
     */
    public function destroy(string $free): JsonResponse
    {
        // 実際のプロジェクトではデータベースから削除

        return response()->json([
            'success' => true,
            'message' => '削除が完了しました'
        ]);
    }

    /**
     * 購入処理
     */
    public function buy(Request $request, string $free): JsonResponse
    {
        $request->validate([
            'message' => 'sometimes|string|max:500'
        ]);

        // 実際のプロジェクトでは購入処理を実装

        return response()->json([
            'success' => true,
            'message' => '購入リクエストを送信しました'
        ]);
    }

    /**
     * DM機能
     */
    public function dm(string $free): JsonResponse
    {
        // ダミーのDMデータ
        $messages = [
            [
                'id' => 1,
                'sender_id' => 1,
                'receiver_id' => 2,
                'message' => 'こんにちは、商品について質問があります',
                'created_at' => Carbon::now()->subHours(2)->toISOString()
            ],
            [
                'id' => 2,
                'sender_id' => 2,
                'receiver_id' => 1,
                'message' => 'はい、何でしょうか？',
                'created_at' => Carbon::now()->subHours(1)->toISOString()
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'item_id' => (int)$free,
                'messages' => $messages
            ],
            'message' => 'DM履歴を取得しました'
        ]);
    }
}
