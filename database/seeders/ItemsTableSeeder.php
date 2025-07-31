<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            [
                'name' => '腕時計',
                'brand_name' => 'Rolax',
                'detail' => 'スタイリッシュなデザインのメンズ腕時計',
                'cost' => 15000,
                'product_condition' => 1, // 良好
                'image' => 'items/Clock.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'HDD',
                'brand_name' => '西芝',
                'detail' => '高速で信頼性の高いハードディスク',
                'cost' => 5000,
                'product_condition' => 2, // 目立った傷や汚れなし
                'image' => 'items/Disk.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '玉ねぎ3束',
                'brand_name' => null,
                'detail' => '新鮮な玉ねぎ3束のセット',
                'cost' => 300,
                'product_condition' => 3, // やや傷や汚れあり
                'image' => 'items/Onion.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '革靴',
                'brand_name' => null,
                'detail' => 'クラシックなデザインの革靴',
                'cost' => 4000,
                'product_condition' => 4, // 状態が悪い
                'image' => 'items/Shoes.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ノートPC',
                'brand_name' => null,
                'detail' => '高性能なノートパソコン',
                'cost' => 45000,
                'product_condition' => 1,
                'image' => 'items/Laptop.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'マイク',
                'brand_name' => null,
                'detail' => '高音質のレコーディング用マイク',
                'cost' => 8000,
                'product_condition' => 2,
                'image' => 'items/Mic.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ショルダーバッグ',
                'brand_name' => null,
                'detail' => 'おしゃれなショルダーバッグ',
                'cost' => 3500,
                'product_condition' => 3,
                'image' => 'items/Bag.jpg',
                'is_sold' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'タンブラー',
                'brand_name' => null,
                'detail' => '使いやすいタンブラー',
                'cost' => 500,
                'product_condition' => 4,
                'image' => 'items/Tumbler.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'コーヒーミル',
                'brand_name' => 'Starbacks',
                'detail' => '手動のコーヒーミル',
                'cost' => 4000,
                'product_condition' => 1,
                'image' => 'items/CoffeeMill.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'メイクセット',
                'brand_name' => null,
                'detail' => '便利なメイクアップセット',
                'cost' => 2500,
                'product_condition' => 2,
                'image' => 'items/Makeup.jpg',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
