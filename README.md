# 共同開発プロジェクト

## プロジェクト概要
Laravel 11を使用した場所共有・フリマアプリケーション

## 技術スタック
- **Backend**: Laravel 11, PHP 8.4
- **Database**: MySQL (Laravel Sail)
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Development**: Docker, Laravel Sail

## 現在の進捗状況

### ✅ 完了済み機能

#### 1. 認証システム
- ユーザー登録・ログイン・ログアウト
- パスワードリセット機能
- セッション管理

#### 2. 場所管理システム
- ドライブスポット一覧・詳細表示
- カラオケスポット一覧・詳細表示
- 居酒屋スポット一覧・詳細表示
- 場所の登録・編集・削除

#### 3. フリマ機能
- 商品一覧・詳細表示
- 商品の出品・編集・削除
- 購入申請・DM機能
- 取引状況管理

#### 4. マイページ機能
- ユーザー情報管理
- 自分の掲載一覧
- 掲載の作成・編集・削除

### 🔧 実装済みコントローラー

#### AuthController
```php
// 認証関連の機能
- showLogin()     // ログインフォーム表示
- login()         // ログイン処理
- showRegister()  // 登録フォーム表示
- register()      // 登録処理
- showForgotPassword()  // パスワード忘れた方フォーム
- sendResetLink() // パスワードリセットリンク送信
- showResetPassword()   // 新パスワード設定フォーム
- resetPassword() // パスワード更新処理
- logout()        // ログアウト処理
```

#### PlaceController
```php
// 場所管理の機能
- index($type)    // 場所一覧表示（タイプ別）
- show($type, $place)  // 場所詳細表示
```

#### FreeController
```php
// フリマ機能
- index()         // 商品一覧表示
- show($id)       // 商品詳細表示
- buy($id)        // 購入処理
- dm($id)         // DM表示
- closeDm($id)    // DM終了
- status($id)     // 取引状況表示
- updateStatus($id)  // 取引状況更新
- update($id)     // 商品情報更新
```

#### MyController
```php
// マイページ機能
- index()         // マイページ表示
- createPlace()   // 場所作成フォーム
- storePlace()    // 場所保存
- editPlace()     // 場所編集フォーム
- updatePlace()   // 場所更新
- destroyPlace()  // 場所削除
- showPlace()     // 場所詳細
- places()        // 場所一覧
- createFree()    // フリマ商品作成フォーム
- storeFree()     // フリマ商品保存
- editFree()      // フリマ商品編集フォーム
- updateFree()    // フリマ商品更新
- destroyFree()   // フリマ商品削除
- showFree()      // フリマ商品詳細
- free()          // フリマ商品一覧
```

### 🗄️ データベース設計

#### users テーブル
```sql
- id (Primary Key)
- login_id (120文字, ユニーク)
- email (255文字, ユニーク, nullable)
- password_hash (255文字)
- password_algo (255文字)
- role (255文字)
- is_active (tinyint, default: 1)
- email_verified_at (timestamp, nullable)
- last_login_at (datetime, nullable)
- last_login_ip (45文字, nullable)
- remember_token (100文字, nullable)
- created_at, updated_at
```

#### places テーブル
```sql
- id (Primary Key)
- user_id (Foreign Key)
- name (120文字)
- kana (120文字, nullable)
- tel (20文字, nullable)
- address (255文字, nullable)
- description (text, nullable)
- url (255文字, nullable)
- score (integer, default: 0)
- tags (255文字, nullable)
- rating_avg (decimal(3,2), default: 0)
- rating_count (unsigned integer, default: 0)
- recommend_score (integer, default: 0)
- reason (255文字, nullable)
- campus_time_min (unsigned small integer, nullable)
- is_active (tinyint, default: 1)
- type (enum: 'drive', 'karaoke', 'izakaya')
- created_at, updated_at
```

### 🌐 URL設計

#### 認証関連
```
GET  /login              # ログインページ
POST /login              # ログイン処理
GET  /register           # 登録ページ
POST /register           # 登録処理
GET  /forgot-password    # パスワード忘れた方
POST /forgot-password    # パスワードリセットリンク送信
GET  /reset-password/{token}  # 新パスワード設定
POST /reset-password/{token}  # パスワード更新
POST /logout             # ログアウト
```

#### 場所関連
```
GET  /places/{type}           # 場所一覧（タイプ別）
GET  /places/{type}/{place}   # 場所詳細
```

#### フリマ関連
```
GET  /free                    # フリマ商品一覧
GET  /free/{id}               # 商品詳細
POST /free/{id}/buy           # 購入申請
GET  /free/{id}/dm            # DM表示
POST /free/{id}/dm/close      # DM終了
GET  /free/{id}/status        # 取引状況
PUT  /free/{id}/status        # 取引状況更新
PUT  /free/{id}               # 商品情報更新
```

#### マイページ関連（認証必須）
```
GET  /my                      # マイページ
GET  /my/places/create        # 場所作成フォーム
POST /my/places               # 場所保存
GET  /my/places/{id}/edit     # 場所編集フォーム
PUT  /my/places/{id}          # 場所更新
DELETE /my/places/{id}        # 場所削除
GET  /my/places/{id}          # 場所詳細
GET  /my/places               # 場所一覧
GET  /my/free/create          # フリマ商品作成フォーム
POST /my/free                 # フリマ商品保存
GET  /my/free/{id}/edit       # フリマ商品編集フォーム
PUT  /my/free/{id}            # フリマ商品更新
DELETE /my/free/{id}          # フリマ商品削除
GET  /my/free/{id}            # フリマ商品詳細
GET  /my/free                 # フリマ商品一覧
```

### 🎨 フロントエンド状況

#### 実装済みビューファイル
- `resources/views/auth/login.blade.php` - ログインフォーム
- `resources/views/auth/register.blade.php` - 登録フォーム
- `resources/views/places/index.blade.php` - 場所一覧
- `resources/views/layouts/navigation.blade.php` - ナビゲーション

#### デザインシステム
- **Tailwind CSS**: モダンなユーティリティファーストCSS
- **Alpine.js**: 軽量なJavaScriptフレームワーク
- **Bootstrap**: 追加のコンポーネントサポート
- **レスポンシブデザイン**: モバイル対応

### 🚀 開発環境

#### Laravel Sail (Docker)
```bash
# 開発環境起動
./vendor/bin/sail up -d

# マイグレーション実行
./vendor/bin/sail artisan migrate

# ルート確認
./vendor/bin/sail artisan route:list

# 開発サーバー起動
./vendor/bin/sail artisan serve
```

#### 環境設定
- **DB_CONNECTION**: mysql
- **DB_HOST**: mysql
- **DB_DATABASE**: laravel
- **DB_USERNAME**: root
- **DB_PASSWORD**: password

### 📋 今後の開発予定

#### バックエンド
- [ ] 画像アップロード機能
- [ ] 検索・フィルタ機能
- [ ] レビュー・評価機能
- [ ] 通知機能
- [ ] API エンドポイントの拡張

#### フロントエンド（resourcesディレクトリ）
- [ ] 詳細なスタイリング調整
- [ ] JavaScript機能の実装
- [ ] レスポンシブデザインの改善
- [ ] ユーザーインターフェースの最適化
- [ ] アニメーション効果の追加

### 🔄 共同開発の進め方

#### バックエンド担当
- コントローラー・モデル・ルートの実装
- データベース設計・マイグレーション
- API設計・実装
- 認証・認可機能

#### フロントエンド担当
- `resources/views/` ディレクトリでのビューファイル実装
- `resources/css/` でのスタイリング
- `resources/js/` でのJavaScript機能
- ユーザーインターフェース設計

### 📝 注意事項

1. **resourcesディレクトリ**: フロントエンド担当者が管理
2. **バックエンドファイル**: コントローラー・モデル・ルートは実装済み
3. **データベース**: マイグレーション済み
4. **URL設計**: 完了済み、すべて動作確認済み

### 🎯 現在の状態

- ✅ バックエンド基盤: 完了
- ✅ 認証システム: 完了
- ✅ 基本的なCRUD操作: 完了
- ✅ URL設計: 完了
- 🔄 フロントエンド: 開発中（resourcesディレクトリ）

## 開発者向け情報

### プロジェクト構造
```
app/
├── Http/Controllers/     # コントローラー（実装済み）
├── Models/              # モデル（実装済み）
database/
├── migrations/          # マイグレーション（実装済み）
routes/
├── web.php              # Webルート（実装済み）
resources/
├── views/               # ビューファイル（開発中）
├── css/                 # スタイルシート（開発中）
└── js/                  # JavaScript（開発中）
```

### 開発開始方法
1. `git clone` でプロジェクトを取得
2. `./vendor/bin/sail up -d` で開発環境起動
3. `./vendor/bin/sail artisan migrate` でマイグレーション実行
4. `http://localhost` でアクセス

---

**最終更新**: 2025年10月2日
**バックエンド進捗**: 100% 完了
**フロントエンド進捗**: 開発中
