<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
# 協同開発プロジェクト - 大学周辺スポット共有システム

## 📖 プロジェクト概要

大学周辺のドライブスポット、カラオケ、居酒屋などの場所情報を管理し、ユーザーがおすすめスポットを共有・評価できるWebアプリケーションです。

### 🎯 主な機能
- **ユーザー認証・セッション管理**: 安全なログイン・ログアウト機能
- **場所情報管理**: スポットの基本情報、画像、評価の管理
- **カテゴリ別分類**: ドライブ、カラオケ、居酒屋の種類別管理
- **おすすめ順表示**: 評価や閲覧数に基づくスポットの並び替え
- **セキュリティ機能**: ログイン試行の監視・制限

## 🏗️ 技術スタック

### バックエンド
- **PHP 8.0+**
- **Laravel 8.x** - Webフレームワーク
- **MySQL 8.0** - リレーショナルデータベース
- **Redis** - セッション・キャッシュ管理

### フロントエンド
- **HTML/CSS/JavaScript**
- **Blade** - Laravelテンプレートエンジン
- **Vite** - アセットビルドツール

### インフラ
- **Docker** - コンテナ化
- **Laravel Sail** - 開発環境
- **Meilisearch** - 全文検索エンジン

## 🚀 セットアップ手順

### 前提条件
- Docker Desktop
- Git
- Composer（PHP 8.0+）

### 1. リポジトリのクローン
```bash
git clone [リポジトリURL]
cd kyoudoukaihatsu
```

### 2. 環境変数の設定
```bash
cp .env.example .env
# .envファイルを編集してデータベース接続情報を設定
```

### 3. 依存関係のインストール
```bash
composer install
npm install
```

### 4. アプリケーションキーの生成
```bash
php artisan key:generate
```

### 5. Docker環境の起動
```bash
./vendor/bin/sail up -d
```

### 6. データベースの初期化
```bash
# 初期化スクリプトの配置
mkdir -p initdb
cp docs/db/schema.sql initdb/01-schema.sql

# コンテナの再起動
./vendor/bin/sail down
./vendor/bin/sail up -d
```

### 7. マイグレーションの実行
```bash
./vendor/bin/sail artisan migrate
```

### 8. アプリケーションの起動確認
ブラウザで `http://localhost` にアクセス

## 📊 データベース設計

### 主要テーブル構成

#### 認証・ユーザー管理
- `users` - ユーザー基本情報
- `password_reset_tokens` - パスワードリセット
- `sessions` - セッション管理
- `login_attempts` - ログイン試行ログ

#### 場所・スポット管理
- `places` - 場所の基本情報（メインテーブル）
- `drives_categories` - ドライブカテゴリ
- `drives` - ドライブスポット固有情報
- `karaokes` - カラオケ店固有情報
- `izakayas` - 居酒屋固有情報
- `place_images` - 場所の画像管理

### ERD図
詳細なデータベース設計は `docs/db/ERD.md` を参照してください。

## 🔐 認証システム

### ✅ 実装済み機能
- **ユーザー登録・ログイン**: Laravel Breezeによる完全な認証システム
- **パスワードリセット**: メール送信による安全なパスワードリセット
- **メール認証**: ユーザー登録後のメールアドレス確認
- **セッション管理**: 安全なログイン状態の管理
- **CSRF保護**: クロスサイトリクエストフォージェリ対策

### 🛣️ 認証ルート
```
/login          - ログインフォーム・処理
/register      - ユーザー登録
/forgot-password - パスワードリセット
/logout        - ログアウト
```

### 🎯 セキュリティ機能
- パスワードハッシュ化（bcrypt）
- ログイン試行の監視・制限
- セッションハイジャック対策
- パスワード強度の検証
- メール認証によるアカウント確認

## 🔧 開発コマンド

### Sailコマンド（Docker環境）
```bash
# コンテナの起動
./vendor/bin/sail up -d

# コンテナの停止
./vendor/bin/sail down

# コンテナ内でコマンド実行
./vendor/bin/sail artisan [コマンド]
./vendor/bin/sail composer [コマンド]
./vendor/bin/sail npm [コマンド]
```

### Artisanコマンド
```bash
# マイグレーション
php artisan migrate
php artisan migrate:rollback

# シーダー
php artisan db:seed

# キャッシュクリア
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## 📁 プロジェクト構造

```
kyoudoukaihatsu/
├── app/                    # アプリケーションロジック
│   ├── Http/Controllers/  # コントローラー
│   ├── Models/           # Eloquentモデル
│   └── Providers/        # サービスプロバイダー
├── config/               # 設定ファイル
├── database/             # データベース関連
│   ├── migrations/      # マイグレーションファイル
│   ├── seeders/         # シーダーファイル
│   └── factories/       # ファクトリーファイル
├── docs/                # ドキュメント
│   └── db/             # データベース設計書
├── resources/           # フロントエンドリソース
│   ├── views/          # Bladeテンプレート
│   ├── css/            # スタイルシート
│   └── js/             # JavaScript
├── routes/              # ルート定義
├── storage/             # ファイルストレージ
├── tests/               # テストファイル
└── vendor/              # Composer依存関係
```

## 🧪 テスト

### テストの実行
```bash
# 全テストの実行
./vendor/bin/sail artisan test

# 特定のテストファイルの実行
./vendor/bin/sail artisan test tests/Feature/ExampleTest.php

# テストカバレッジの確認
./vendor/bin/sail artisan test --coverage
```

## 📝 開発ガイドライン

### コーディング規約
- PSR-12準拠
- Laravel Pintによる自動フォーマット
- 日本語コメントの使用

### コミットメッセージ
```
feat: 新機能の追加
fix: バグ修正
docs: ドキュメント更新
style: コードスタイルの変更
refactor: リファクタリング
test: テストの追加・修正
```

### ブランチ戦略
- `main` - 本番環境
- `develop` - 開発環境
- `feature/*` - 機能開発
- `hotfix/*` - 緊急修正

## 🚀 デプロイ

### 本番環境へのデプロイ
1. 環境変数の設定
2. データベースのマイグレーション
3. アセットのビルド
4. キャッシュのクリア

```bash
# 本番環境での実行
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🤝 コントリビューション

1. このリポジトリをフォーク
2. 機能ブランチを作成 (`git checkout -b feature/AmazingFeature`)
3. 変更をコミット (`git commit -m 'Add some AmazingFeature'`)
4. ブランチにプッシュ (`git push origin feature/AmazingFeature`)
5. プルリクエストを作成

## 📄 ライセンス

このプロジェクトは [MIT License](LICENSE) の下で公開されています。

## 📞 サポート

質問や問題がある場合は、以下の方法でお問い合わせください：

- Issueの作成
- プロジェクトメンバーへの直接連絡
- 開発チームのSlack/Teams

## 📈 プロジェクト進捗状況

### ✅ 完了済み
- **インフラ環境**: Laravel Sail + Docker環境構築
- **データベース設計**: 完全なスキーマ設計完了
- **認証システム**: Laravel Breezeによる完全実装
- **プロジェクトドキュメント**: README、DB設計書

### 🚧 進行中
- **データベース初期化**: スキーマ適用準備中

### 📋 今後の予定
- **Laravelマイグレーション**: スキーマのLaravel化
- **Eloquentモデル**: User、Place、Category等の作成
- **場所管理機能**: CRUD操作の実装
- **フロントエンド**: Bladeテンプレートの開発
- **API開発**: RESTful APIの実装

### 📊 進捗率
- **インフラ・環境**: 90%完了
- **データベース設計**: 100%完了
- **認証システム**: 100%完了
- **アプリケーション機能**: 0%完了
- **全体**: 約60%完了

## 🔄 更新履歴

- **v1.0.0** - 初期リリース
  - 基本的な認証機能
  - 場所管理機能
  - カテゴリ分類機能

---

**協同開発プロジェクトチーム** - より良い大学ライフをサポートします 🎓✨
>>>>>>> local-reinit-backup
