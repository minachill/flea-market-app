<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;
        /** @test */
        // 全商品が表示される
    public function test_it_displays_all_items()
    {
        $items = Item::factory()->count(3)->create();

        $response = $this->get('/');

        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    /** @test */
    // ゲストユーザーでも一覧が見られる
    public function test_it_displays_item_list_for_guest_users()
    {
        $item = Item::factory()->create([
            'name' => 'テスト商品',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200)
                ->assertSee('テスト商品');
    }

    /** @test */
    // 購入済みの商品に「Sold」ラベルが出る
    public function test_it_displays_sold_label_for_purchased_items()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['name' => '売れた商品','is_sold' => 1,]);
        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/');

        $response->assertSee('Sold')
                ->assertSee('売れた商品');
    }

    /** @test */
    // ログイン済みユーザーでも一覧が見られる
    public function test_it_shows_items_for_authenticated_users()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create(['name' => 'ログイン済ユーザー商品']);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200)
                ->assertSee('ログイン済ユーザー商品');
    }

    // 自分が出品した商品は表示されない
        public function test_it_does_not_display_items_created_by_authenticated_user()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $myItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品'
        ]);
        $otherItem = Item::factory()->create([
            'name' => '他人の商品'
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertDontSee('自分の商品')
                ->assertSee('他人の商品');
    }
}