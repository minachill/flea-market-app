# coachtechフリマ

本アプリはユーザーが商品を出品・購入できるフリマアプリです。
ユーザー登録、商品一覧・詳細表示、マイリスト管理、出品、購入手続きなどの機能を提供します。
---
## 環境構築
---
### Dockerビルド

```bash
git clone https://github.com/minachill/flea-market-app.git  
cd flea-market-app  
docker-compose up -d --build  
```

### Laravel環境構築

```bash
docker-compose exec php bash
composer install
cp .env.example .env  # 環境変数を設定
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## 開発環境
---
// TODO: READMEのURL修正
- 商品一覧画面（トップ画面）： http://localhost:8083/  
- 商品一覧画面（トップ画面）_マイリスト： http://localhost:8083/?tab=mylist  
- 会員登録画面： http://localhost:8083/register  
- ログイン画面： http://localhost:8083/login  
- 商品詳細画面： http://localhost:8083/item/{item_id}  
- 商品購入画面： http://localhost:8083/purchase/{item_id}  
- 送付先住所変更画面： http://localhost:8083/purchase/address/i{tem_id}  
- 商品出品画面： http://localhost:8083/sell  
- プロフィール画面： http://localhost:8083/mypage  
- プロフィール編集画面（設定画面）： http://localhost:8083/mypage/profile  
- プロフィール画面_購入した商品一覧： http://localhost:8083/mypage?page=buy  
- プロフィール画面_出品した商品一覧： http://localhost:8083/mypage?page=sell  
- phpMyAdmin： http://localhost:8084/  
※ {item_id} や {page} は実際のIDやパラメータに置き換えてアクセスしてください。

## 使用技術（実行環境）
---

- PHP 8.3  
- Laravel 8.83.29  
- jQuery 3.7.1.min.js  
- MySQL 8.0.35  
- Nginx 1.21.1  

## ER図
---
<img width="757" height="950" alt="ER図" src="https://github.com/user-attachments/assets/f6812a32-e218-48fc-b9b0-9edb1659dbc4" />

## 運用・保守
- クライアントが運用・保守を行います。  
- 本番環境のサーバーは設置せず、ローカル開発環境で動作する想定です。  
- ドメイン・SSL化は考慮していません。  
