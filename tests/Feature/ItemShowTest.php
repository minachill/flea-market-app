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

    /** @test */
    // 商品詳細ページに必要な情報が表示される
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

        // カテゴリを複数紐付け
        $category1 = Category::factory()->create(['name' => '家電']);
        $category2 = Category::factory()->create(['name' => '家具']);
        $item->categories()->attach([$category1->id, $category2->id]);

        // コメントを1件作成
        Comment::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => '素敵な商品ですね',
        ]);

        // いいね数は likes リレーション経由でカウントされる想定
        $item->likes()->create(['user_id' => $user->id]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200)
                 ->assertSeeText('テスト商品')                 // 商品名
                 ->assertSeeText('3,000')                        // 価格
                 ->assertSeeText('テストブランド')             // ブランド名
                 ->assertSeeText('これはテスト商品の説明です')   // 商品説明
                 ->assertSeeText('家電')                       // カテゴリ1                    // カテゴリ2
                ->assertSeeText('家具')
                 ->assertSee('商品の状態')                  // 状態ラベル（ビュー側にある想定）
                 ->assertSeeText('コメントユーザー')           // コメントしたユーザー
                 ->assertSeeText('素敵な商品ですね')            // コメント内容
                ->assertSeeText('コメント(1)')
                 ->assertSee('いいね')                      // いいね数のUI
                 ->assertSee('コメント')                  // コメント数のUI
                ->assertSee('storage/dummy.png')               // 商品画像の表示確認
                ->assertSee('storage/dummy-avatar.png');           // コメントユーザーアイコン(imgタグ)
    }

    /** @test */
    // 未ログインのユーザーは「購入手続きへ」を押すとログイン画面へ誘導される
    public function test_guest_sees_login_link_instead_of_purchase_link()
    {
        $item = Item::factory()->create(['name' => 'テスト商品']);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200)
                ->assertSee('購入手続きへ')
                 ->assertSee('/login'); // 遷移先がログイン
    }

    /** @test */
    // ログイン済みのユーザーは商品詳細から購入手続き画面へ進める
    public function test_logged_in_user_sees_purchase_link()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create(['name' => 'テスト商品']);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200)
                ->assertSee('購入手続きへ')
                 ->assertSee("/purchase/{$item->id}"); // 遷移先が購入画面
    }
}