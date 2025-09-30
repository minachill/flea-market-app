<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::where('email', 'dummy@example.com')->first();
        $user2 = User::where('email', 'user2@example.com')->first();

        $items = [
            [
                'name' => '腕時計',
                'brand_name' => 'Rolax',
                'detail' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'product_condition' => 1,
                'image' => 'items/Clock.jpg',
                'is_sold' => false,
                'categories' => [1, 5], // ファッション, メンズ
            ],
            [
                'name' => 'HDD',
                'brand_name' => '西芝',
                'detail' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'product_condition' => 2,
                'image' => 'items/Disk.jpg',
                'is_sold' => false,
                'categories' => [2], // 家電
            ],
            [
                'name' => '玉ねぎ3束',
                'brand_name' => null,
                'detail' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'product_condition' => 3,
                'image' => 'items/Onion.jpg',
                'is_sold' => false,
                'categories' => [10], // キッチン
                'owner' => 'user2',
            ],
            [
                'name' => '革靴',
                'brand_name' => null,
                'detail' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'product_condition' => 4,
                'image' => 'items/Shoes.jpg',
                'is_sold' => false,
                'categories' => [1, 5], // ファッション, メンズ
            ],
            [
                'name' => 'ノートPC',
                'brand_name' => null,
                'detail' => '高性能なノートパソコン',
                'price' => 45000,
                'product_condition' => 1,
                'image' => 'items/Laptop.jpg',
                'is_sold' => false,
                'categories' => [2], // 家電
            ],
            [
                'name' => 'マイク',
                'brand_name' => null,
                'detail' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'product_condition' => 2,
                'image' => 'items/Mic.jpg',
                'is_sold' => false,
                'categories' => [2], // 家電
            ],
            [
                'name' => 'ショルダーバッグ',
                'brand_name' => null,
                'detail' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'product_condition' => 3,
                'image' => 'items/Bag.jpg',
                'is_sold' => false,
                'categories' => [1, 4], // ファッション, レディース
            ],
            [
                'name' => 'タンブラー',
                'brand_name' => null,
                'detail' => '使いやすいタンブラー',
                'price' => 500,
                'product_condition' => 4,
                'image' => 'items/Tumbler.jpg',
                'is_sold' => false,
                'categories' => [10], // キッチン
            ],
            [
                'name' => 'コーヒーミル',
                'brand_name' => 'Starbacks',
                'detail' => '手動のコーヒーミル',
                'price' => 4000,
                'product_condition' => 1,
                'image' => 'items/CoffeeMill.jpg',
                'is_sold' => false,
                'categories' => [10, 11], // キッチン, ハンドメイド
            ],
            [
                'name' => 'メイクセット',
                'brand_name' => null,
                'detail' => '便利なメイクアップセット',
                'price' => 2500,
                'product_condition' => 2,
                'image' => 'items/Makeup.jpg',
                'is_sold' => false,
                'categories' => [6], // コスメ
            ],
        ];

        foreach ($items as $index => $item) {
            $ownerId = isset($item['owner']) && $item['owner'] === 'user2'
                ? $user2->id
                : $user1->id;

            $itemId = DB::table('items')->insertGetId([
                'name' => $item['name'],
                'brand_name' => $item['brand_name'],
                'detail' => $item['detail'],
                'price' => $item['price'],
                'product_condition' => $item['product_condition'],
                'image' => $item['image'],
                'is_sold' => $item['is_sold'],
                'user_id' => $ownerId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($item['categories'] as $categoryId) {
                DB::table('category_item')->insert([
                    'item_id' => $itemId,
                    'category_id' => $categoryId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
