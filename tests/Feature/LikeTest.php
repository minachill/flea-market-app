<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // DBにいいねが保存されるか
    public function test_it_allows_user_to_like_an_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)->post("/item/{$item->id}/like");

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

            // 画面確認（リダイレクト先をGETしていいね数を確認）
        $response = $this->get("/item/{$item->id}");
        $response->assertSee('1'); // いいね数が「1」と表示されていること
    }

    /** @test */
    // 解除されるか
    public function test_it_allows_user_to_unlike_an_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

    // 先にいいね
        $this->actingAs($user)->post("/item/{$item->id}/like");

        // 再度post → 解除
        $this->actingAs($user)->post("/item/{$item->id}/like");

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

            // 表示確認（再度詳細ページをGETして「0」が出ているか）
        $response = $this->get("/item/{$item->id}");
        $response->assertSee('<span class="item-detail__likes-count">0</span>', false);
    }

    /** @test */
    // アイコンが色変わるか
    public function test_it_displays_filled_icon_for_liked_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // いいね前 → star.png
        $response = $this->actingAs($user)->get("/item/{$item->id}");
        $response->assertSee('star.png');

        // いいね実行
        $this->actingAs($user)->post("/item/{$item->id}/like");

        // いいね後 → star-filled.png
        $response = $this->get("/item/{$item->id}");
        $response->assertSee('star-filled.png');
    }
}