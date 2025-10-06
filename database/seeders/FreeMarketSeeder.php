<?php

namespace Database\Seeders;

use App\Models\FreeMarket;
use App\Models\User;
use Illuminate\Database\Seeder;

class FreeMarketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // テスト用ユーザーを取得（存在しない場合は作成）
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'テストユーザー',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // サンプル商品データ
        $items = [
            [
                'user_id' => $user->id,
                'title' => '線形代数入門',
                'description' => '第2版。状態良好で、重要な部分にマーカーが少し入っています。経済学概論（2024年度）で使用しました。',
                'price' => 1500,
                'condition' => 'good',
                'category' => 'books',
                'status' => 'active',
            ],
            [
                'user_id' => $user->id,
                'title' => '統計学入門',
                'description' => '第3版。ほぼ新品の状態です。統計学概論（2024年度）で使用し、試験も無事に合格できました。',
                'price' => 1800,
                'condition' => 'like_new',
                'category' => 'books',
                'status' => 'active',
            ],
            [
                'user_id' => $user->id,
                'title' => '微分積分学',
                'description' => '第1版。練習問題に解答が書き込まれていますが、学習に役立ちます。数学基礎（2024年度）で使用しました。',
                'price' => 2000,
                'condition' => 'good',
                'category' => 'books',
                'status' => 'active',
            ],
            [
                'user_id' => $user->id,
                'title' => '経済学概論',
                'description' => '第4版。重要なポイントにマーカーが入っています。経済学入門（2024年度）で使用し、単位も取得できました。',
                'price' => 1200,
                'condition' => 'good',
                'category' => 'books',
                'status' => 'active',
            ],
            [
                'user_id' => $user->id,
                'title' => 'MacBook Pro 13インチ',
                'description' => '2020年モデル。軽微な使用感はありますが、動作に問題はありません。充電器付き。',
                'price' => 80000,
                'condition' => 'good',
                'category' => 'electronics',
                'status' => 'active',
            ],
            [
                'user_id' => $user->id,
                'title' => 'iPhone 12',
                'description' => '128GB。画面に軽微な傷がありますが、動作は正常です。ケースと充電器付き。',
                'price' => 45000,
                'condition' => 'fair',
                'category' => 'electronics',
                'status' => 'active',
            ],
            [
                'user_id' => $user->id,
                'title' => 'ナイキ エアマックス',
                'description' => 'サイズ27cm。数回使用した程度で、ほぼ新品の状態です。',
                'price' => 8000,
                'condition' => 'like_new',
                'category' => 'fashion',
                'status' => 'active',
            ],
            [
                'user_id' => $user->id,
                'title' => 'ユニクロ ダウンジャケット',
                'description' => 'Mサイズ。1シーズン使用しましたが、状態良好です。',
                'price' => 3000,
                'condition' => 'good',
                'category' => 'fashion',
                'status' => 'active',
            ],
        ];

        foreach ($items as $item) {
            FreeMarket::create($item);
        }
    }
}
