<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // 部分一致検索できること
    public function test_it_can_search_items_by_keyword()
    {
        Item::factory()->create(['name' => 'ノートPC']);
        Item::factory()->create(['name' => 'スマートフォン']);

        $response = $this->get('/?keyword=ノート');

        $response->assertSee('ノートPC');
        $response->assertDontSee('スマートフォン');
    }

    /** @test */
    // 検索キーワードが保持されること
    public function test_it_keeps_the_search_keyword_in_the_form()
    {
        $item = Item::factory()->create(['name' => 'カメラ']);
        $response = $this->get('/?keyword=カメラ');

        $response->assertSeeText('カメラ');
    }

    /** @test */
    // マイリストタブ (/?tab=mylist) に切り替えても、検索キーワード「ノート」が効いたまま → マイリストの中でさらに絞り込まれる
    public function test_it_keeps_search_keyword_when_switching_to_mylist()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // アイテムを2つ作成
        $item1 = Item::factory()->create(['name' => 'ノートPC']);
        $item2 = Item::factory()->create(['name' => 'スマートフォン']);

        // user が item1 にいいね
        $user->likes()->create(['item_id' => $item1->id,]);

        // まず「ノート」で検索
        $this->get('/?keyword=ノート');

        // その後マイリストに切り替え
        $response = $this->get('/?tab=mylist&keyword=ノート');

        // マイリストの中でも「ノートPC」だけが表示される
        $response->assertSee('ノートPC');
        $response->assertDontSee('スマートフォン');

        // 検索キーワードがフォームに残っている
        $response->assertSeeText('ノート');
    }
}