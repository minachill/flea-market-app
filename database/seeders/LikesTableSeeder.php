<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class LikesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 一般ユーザー & 管理者ユーザーを取得
        $generalUser = User::where('email', 'user2@example.com')->first();
        $adminUser   = User::where('email', 'dummy@example.com')->first();

        // 商品2を取得
        $item2 = Item::skip(1)->first();

        if ($item2) {
            // 一般ユーザーがいいね
            if ($generalUser) {
                Like::firstOrCreate([
                    'user_id' => $generalUser->id,
                    'item_id' => $item2->id,
                ]);
            }

            // 管理者ユーザーがいいね
            if ($adminUser) {
                Like::firstOrCreate([
                    'user_id' => $adminUser->id,
                    'item_id' => $item2->id,
                ]);
            }
        }
    }
}