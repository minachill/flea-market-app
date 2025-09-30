<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Item;

class PurchasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ユーザーと商品を取得
        $user = User::where('email', 'user2@example.com')->first();

        $item = Item::where('is_sold', false)->skip(1)->first();

        if ($user && $item) {
            Purchase::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'shipping_postal' => '123-4567',
                'shipping_address' => '東京都渋谷区1-1-1',
                'shipping_building' => 'テストビル',
                'payment_method' => 'card',
            ]);

            // Item 側も is_sold = true に更新
            $item->update(['is_sold' => true]);
        }
    }
}