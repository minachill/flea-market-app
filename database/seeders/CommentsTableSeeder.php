<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = User::where('email', 'dummy@example.com')->first();
        $generalUser = User::where('email', 'user2@example.com')->first();

        $item = Item::where('name', '腕時計')->first();
        $item2 = Item::skip(1)->first();

        if ($adminUser && $item) {
            Comment::create([
                'user_id' => $adminUser->id,
                'item_id' => $item->id,
                'comment' => 'こちらにコメントが入ります。',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        if ($generalUser && $item2) {
            Comment::create([
                'user_id' => $generalUser->id,
                'item_id' => $item2->id,
                'comment' => '一般ユーザーからのコメントです。',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
