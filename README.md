# coachtechフリマ
---
## 環境構築
---
### Dockerビルド

```bash
git clone [リポジトリのURL](https://github.com/minachill/flea-market-app/blob/main/README.md)  
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

お問い合わせ画面： http://localhost/  
ユーザー登録画面： http://localhost/register  
phpMyAdmin： http://localhost:8080/  

## 使用技術（実行環境）
---

PHP 8.3  
Laravel 8.x  
jQuery 3.7.1.min.js  
MySQL 8.0.35  
Nginx 1.21.1  

## ER図
---
<img width="757" height="950" alt="スクリーンショット 2025-07-27 23 16 41" src="https://github.com/user-attachments/assets/f6812a32-e218-48fc-b9b0-9edb1659dbc4" />

