-- ユーザーテーブル
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'ユーザー識別子（不変）',
    login_id VARCHAR(120) NOT NULL UNIQUE COMMENT 'ログインID（メール/ユーザー名いずれか運用で統一）',
    email VARCHAR(255) NULL UNIQUE COMMENT '連絡・リセット用。使わない場合はNULL運用',
    password_hash VARCHAR(255) NOT NULL COMMENT 'パスワードのハッシュ（平文は保存しない）',
    password_algo VARCHAR(255) NOT NULL COMMENT 'ハッシュ方式の移行管理用',
    role VARCHAR(255) NOT NULL COMMENT '権限（最小限）',
    is_active TINYINT(1) NOT NULL DEFAULT 1 COMMENT '無効化でログイン不可',
    email_verified_at DATETIME NULL COMMENT 'メール認証時刻（SES運用時）',
    last_login_at DATETIME NULL COMMENT '最終ログイン時刻',
    last_login_ip VARCHAR(45) NULL COMMENT '最終ログインIP（IPv4/IPv6）',
    remember_token VARCHAR(100) NULL COMMENT 'ログイン保持用（Laravel互換）',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成時刻',
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時刻'
);

-- パスワードリセットトークンテーブル
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) NOT NULL PRIMARY KEY COMMENT '宛先（login_idがメール運用なら一致）。FKは張らず論理リンク',
    token_hash CHAR(64) NOT NULL COMMENT 'URLトークンのSHA-256ハッシュ（生値は保存しない）',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '発行時刻（有効期限判定に使用）'
);

-- セッションテーブル
CREATE TABLE sessions (
    id VARCHAR(255) NOT NULL PRIMARY KEY COMMENT 'セッションID',
    user_id BIGINT UNSIGNED NULL COMMENT '紐づくユーザー（未ログインはNULL）',
    ip_address VARCHAR(45) NULL COMMENT 'IPアドレス',
    user_agent TEXT NULL COMMENT 'UA文字列',
    payload LONGTEXT NOT NULL COMMENT 'セッション本体',
    last_activity INT UNSIGNED NOT NULL COMMENT '最終アクティビティ（UNIX時刻）',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ログイン試行ログテーブル
CREATE TABLE login_attempts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'ログID',
    login_id VARCHAR(120) NULL COMMENT '試行されたID（存在しないIDでも記録）',
    ip VARCHAR(45) NULL COMMENT '送信元IP',
    result ENUM('ok','ng') NOT NULL DEFAULT 'ng' COMMENT '結果（成功/失敗）',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '発生時刻'
);

-- 場所テーブル（メインテーブル）
CREATE TABLE places (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'スポットの識別子（不変）、店舗ID',
    name VARCHAR(120) NOT NULL COMMENT '施設/スポット名。検索の主キー語、店名',
    kana VARCHAR(120) NULL COMMENT 'かな表記（任意。五十音ソート用、ふりがな）',
    tel VARCHAR(20) NULL COMMENT '電話番号（任意）',
    address VARCHAR(255) NULL COMMENT '住所（任意）',
    description TEXT NULL COMMENT '紹介文。表示時はXSS対策（エスケープ/サニタイズ）',
    url VARCHAR(255) NULL COMMENT '公式/紹介URL（外部サイトのURL）',
    score INT NOT NULL DEFAULT 0 COMMENT 'おすすめ順（閲覧数やクリック数で加点。おすすめ順に使用）',
    tags VARCHAR(255) NULL COMMENT 'タグCSV（例: 夜景,大型モール,家族向け）',
    rating_avg DECIMAL(3,2) NOT NULL DEFAULT 0 COMMENT '★平均（0.00〜5.00想定）',
    rating_count INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'レビュー件数',
    recommend_score INT NOT NULL DEFAULT 0 COMMENT 'おすすめ順に使う内部スコア',
    reason VARCHAR(255) NULL COMMENT 'おすすめの理由（任意）',
    campus_time_min SMALLINT UNSIGNED NULL COMMENT '大学からの時間（分）のキャッシュ値（近い順に使用）',
    is_active TINYINT(1) NOT NULL DEFAULT 1 COMMENT '公開フラグ、0=非表示 / 1=表示',
    type ENUM('drive','karaoke','izakaya') NOT NULL COMMENT '場所の種類',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成時刻（DBが自動セット）',
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時刻（更新で自動更新）'
);

-- ドライブカテゴリテーブル
CREATE TABLE drives_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'カテゴリ識別子',
    name VARCHAR(60) NOT NULL COMMENT 'カテゴリ名（例: ショッピング/景色/息抜き）',
    icon VARCHAR(60) NULL COMMENT 'アイコン名（任意）',
    sort INT UNSIGNED NOT NULL DEFAULT 1 COMMENT '並び順（小さいほど上位）',
    is_active TINYINT(1) NOT NULL DEFAULT 1 COMMENT '有効フラグ（0=非表示 / 1=表示）',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成時刻（DB自動）',
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時刻（更新で自動）',
    UNIQUE KEY uq_categories_name (name)
);

-- ドライブテーブル
CREATE TABLE drives (
    place_id BIGINT UNSIGNED NOT NULL PRIMARY KEY COMMENT '親Place（1:1）',
    category_id BIGINT UNSIGNED NOT NULL COMMENT 'ドライブカテゴリ',
    FOREIGN KEY (place_id) REFERENCES places(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES drives_categories(id)
);

-- カラオケテーブル
CREATE TABLE karaokes (
    place_id BIGINT UNSIGNED NOT NULL PRIMARY KEY COMMENT '親Place（1:1）',
    price_min INT UNSIGNED NULL COMMENT '価格帯の下限',
    price_max INT UNSIGNED NULL COMMENT '価格帯の上限',
    has_all_you_can_drink TINYINT(1) NOT NULL DEFAULT 0 COMMENT '飲み放題（0/1）',
    byo_allowed TINYINT(1) NOT NULL DEFAULT 0 COMMENT '持ち込み可（0/1）',
    machine_types SET('DAM','JOYSOUND') NOT NULL DEFAULT '' COMMENT '機種（複数可）',
    FOREIGN KEY (place_id) REFERENCES places(id) ON DELETE CASCADE
);

-- 居酒屋テーブル
CREATE TABLE izakayas (
    place_id BIGINT UNSIGNED NOT NULL PRIMARY KEY COMMENT '親Place（1:1）',
    price_min INT UNSIGNED NULL COMMENT '価格帯の下限',
    price_max INT UNSIGNED NULL COMMENT '価格帯の上限',
    has_all_you_can_drink TINYINT(1) NOT NULL DEFAULT 0 COMMENT '飲み放題（0/1）',
    byo_allowed TINYINT(1) NOT NULL DEFAULT 0 COMMENT '持ち込み可（0/1）',
    alcohol_types VARCHAR(120) NULL COMMENT 'お酒の種類CSV（例: 麦焼酎,米焼酎,芋焼酎,ビール）',
    FOREIGN KEY (place_id) REFERENCES places(id) ON DELETE CASCADE
);

-- 場所画像テーブル
CREATE TABLE place_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT '画像識別子',
    place_id BIGINT UNSIGNED NOT NULL COMMENT '親Place',
    path VARCHAR(255) NOT NULL COMMENT '画像パス/URL（S3/Drive/ローカル等）',
    alt_text VARCHAR(120) NULL COMMENT '代替テキスト',
    sort_order INT UNSIGNED NOT NULL DEFAULT 1 COMMENT '並び順（1開始）',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成時刻',
    FOREIGN KEY (place_id) REFERENCES places(id) ON DELETE CASCADE
);

-- ユーザーテーブルのインデックス
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_is_active ON users(is_active);
CREATE INDEX idx_users_created_at ON users(created_at);

-- 場所テーブルのインデックス
CREATE INDEX idx_places_name ON places(name);
CREATE INDEX idx_places_kana ON places(kana);
CREATE INDEX idx_places_type ON places(type);
CREATE INDEX idx_places_is_active ON places(is_active);
CREATE INDEX idx_places_score ON places(score);
CREATE INDEX idx_places_rating_avg ON places(rating_avg);
CREATE INDEX idx_places_campus_time_min ON places(campus_time_min);
CREATE INDEX idx_places_created_at ON places(created_at);

-- カテゴリテーブルのインデックス
CREATE INDEX idx_drives_categories_sort ON drives_categories(sort);
CREATE INDEX idx_drives_categories_is_active ON drives_categories(is_active);

-- ドライブテーブルのインデックス
CREATE INDEX idx_drives_category_id ON drives(category_id);

-- カラオケテーブルのインデックス
CREATE INDEX idx_karaokes_price_min ON karaokes(price_min);
CREATE INDEX idx_karaokes_price_max ON karaokes(price_max);
CREATE INDEX idx_karaokes_has_all_you_can_drink ON karaokes(has_all_you_can_drink);

-- 居酒屋テーブルのインデックス
CREATE INDEX idx_izakayas_price_min ON izakayas(price_min);
CREATE INDEX idx_izakayas_price_max ON izakayas(price_max);
CREATE INDEX idx_izakayas_has_all_you_can_drink ON izakayas(has_all_you_can_drink);

-- 画像テーブルのインデックス
CREATE INDEX idx_place_images_place_id ON place_images(place_id);
CREATE INDEX idx_place_images_sort_order ON place_images(sort_order);

-- セッションテーブルのインデックス
CREATE INDEX idx_sessions_user_id ON sessions(user_id);
CREATE INDEX idx_sessions_last_activity ON sessions(last_activity);

-- ログイン試行ログのインデックス
CREATE INDEX idx_login_attempts_login_id ON login_attempts(login_id);
CREATE INDEX idx_login_attempts_ip ON login_attempts(ip);
CREATE INDEX idx_login_attempts_created_at ON login_attempts(created_at);

-- データベース全体の説明
-- このデータベースは、大学周辺のドライブスポット、カラオケ、居酒屋などの
-- 場所情報を管理し、ユーザーがおすすめスポットを共有・評価できるシステムです。
--
-- 主な機能：
-- 1. ユーザー認証・セッション管理
-- 2. 場所情報の管理（基本情報、画像、評価）
-- 3. カテゴリ別の場所分類
-- 4. ログイン試行の監視・セキュリティ
-- 5. おすすめ順の表示機能
