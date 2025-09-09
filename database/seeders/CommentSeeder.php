<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // admin ユーザーを取得（または新規作成した後に取得）
        $adminUser = User::where('email', 'dummy@example.com')->first();

        // 商品を1つ取得（コメント先の商品）
        $item = Item::where('name', '腕時計')->first();// ←最初のItemでOK（もしくはランダムでもOK）

        if ($adminUser && $item) {
            Comment::create([
                'user_id' => $adminUser->id,
                'item_id' => $item->id,
                'comment' => 'こちらにコメントが入ります。',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
