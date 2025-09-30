 # coachtechフリマ

本アプリはユーザーが商品を出品・購入できるフリマアプリです。
ユーザー登録、商品一覧・詳細表示、マイリスト管理、出品、購入手続きなどの機能を提供します。
---
## 環境構築
---
### 1. リポジトリをクローン

```bash
git clone https://github.com/minachill/flea-market-app.git  
cd flea-market-app  
```

### 2. Dockerビルド & コンテナ起動

```bash
docker-compose up -d --build
```

### 3. Laravel環境構築
```bash
# コンテナに入る
docker-compose exec php bash

# 依存パッケージインストール
composer install

# .env ファイル作成 & アプリキー生成
cp .env.example .env
php artisan key:generate

# マイグレーション & ダミーデータ投入
php artisan migrate --seed
```

### 4. ストレージリンク（画像アップロード用）
```bash
php artisan storage:link
```


## 使用技術（実行環境）
---
- 言語：PHP 8.4.8  
- フレームワーク：Laravel 8.83.29  
- フロントエンド：JavaScript  
- DB：MySQL 8.0.35  
- Webサーバー：Nginx 1.25 (alpine)
- 開発環境：Docker
- バージョン管理：GitHub
- 認証：Laravel Fortify / メール認証機能
- 決済：Stripe（テスト環境で利用）


## ER図
---
<img width="743" height="1028" alt="ER図" src="https://github.com/user-attachments/assets/780ace96-12dd-4cb7-80a8-063048c97b97" />

## 開発環境
---
- 商品一覧画面（トップ画面）： http://localhost:8083/  
- 商品一覧画面（トップ画面）_マイリスト： http://localhost:8083/?tab=mylist  
- 会員登録画面： http://localhost:8083/register  
- ログイン画面： http://localhost:8083/login  
- 商品詳細画面： http://localhost:8083/item/{item_id}  
- 商品購入画面： http://localhost:8083/purchase/{item_id}  
- 送付先住所変更画面： http://localhost:8083/purchase/address/{item_id}  
- 商品出品画面： http://localhost:8083/sell  
- プロフィール画面： http://localhost:8083/profile  
- プロフィール編集画面（設定画面）： http://localhost:8083/profile/edit  
- プロフィール画面_購入した商品一覧： http://localhost:8083/profile?page=buy  
- プロフィール画面_出品した商品一覧： http://localhost:8083/profile?page=sell  
- phpMyAdmin： http://localhost:8084/  
※ {item_id} や {page} は実際のIDやパラメータに置き換えてアクセスしてください。

## ダミーデータについて
php artisan migrate --seed を実行すると以下のデータが投入されます。
1.ユーザー情報
- 一般ユーザー / 管理者ユーザーを含む
2.商品情報
- 商品データ一覧に準拠した 10 件のダミー商品
3.商品カテゴリ情報
- 家電 / ファッション / 食品 など 14 件のカテゴリ
4.出品・購入・コメント・いいね情報
- 動作確認用の基本データを含む
- item1 / item2 / item3 に出品履歴・購入履歴・コメント・いいねを追加済み(一般ユーザー)

## テスト実行
基本的な動作確認テストを実行するには以下を実行してください：

```bash
./vendor/bin/phpunit
```

## ログイン情報
提出用に以下のユーザーを用意しています。
- 管理者ユーザー
メールアドレス：admin@example.com
パスワード：password123
- 一般ユーザー
メールアドレス：user@example.com
パスワード：password123
※ログインの際は一般ユーザーを使用ください。

## 注意事項
- メール認証機能は Mailhog を利用しています。
Mailhog（メール確認用ツール）： http://localhost:8025/
- Stripe 決済は テストモード で実装されています。
