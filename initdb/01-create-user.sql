-- データベースとユーザーの作成
CREATE DATABASE IF NOT EXISTS kyoudoukaihatsu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- sailユーザーの作成
CREATE USER IF NOT EXISTS 'sail'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON kyoudoukaihatsu.* TO 'sail'@'%';
FLUSH PRIVILEGES;

-- rootユーザーのパスワード設定
ALTER USER 'root'@'%' IDENTIFIED BY 'password';
FLUSH PRIVILEGES;
