<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Comment;

class ItemShowTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_displays_all_item_information_with_multiple_categories()
    {
        $user = User::factory()->create(['name' => 'コメントユーザー','profile_image_path' => 'dummy-avatar.png',]);

        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'price' => 3000,
            'brand_name' => 'テストブランド',
            'detail' => 'これはテスト商品の説明です',
            'product_condition' => 1,
            'image' => 'dummy.png',
        ]);


        $category1 = Category::factory()->create(['name' => '家電']);
        $category2 = Category::factory()->create(['name' => '家具']);
        $item->categories()->attach([$category1->id, $category2->id]);

        Comment::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => '素敵な商品ですね',
        ]);

        $item->likes()->create(['user_id' => $user->id]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200)
                ->assertSeeText('テスト商品')
                ->assertSeeText('3,000')
                ->assertSeeText('テストブランド')
                ->assertSeeText('これはテスト商品の説明です')
                ->assertSeeText('家電')
                ->assertSeeText('家具')
                ->assertSee('商品の状態')
                ->assertSeeText('コメントユーザー')
                ->assertSeeText('素敵な商品ですね')
                ->assertSeeText('コメント(1)')
                ->assertSee('いいね')
                ->assertSee('コメント')
                ->assertSee('storage/dummy.png')
                ->assertSee('storage/dummy-avatar.png');
    }


    public function test_guest_sees_login_link_instead_of_purchase_link()
    {
        $item = Item::factory()->create(['name' => 'テスト商品']);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200)
                ->assertSee('購入手続きへ')
                ->assertSee('/login');
    }


    public function test_logged_in_user_sees_purchase_link()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create(['name' => 'テスト商品']);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200)
                ->assertSee('購入手続きへ')
                ->assertSee("/purchase/{$item->id}");
    }
}