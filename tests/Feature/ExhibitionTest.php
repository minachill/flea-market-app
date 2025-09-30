<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExhibitionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_registers_exhibited_item()
    {
        Storage::fake('public');

        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);

        // カテゴリ2件作成
        $categories = Category::factory()->count(2)->create();

        // GDなしでも通る偽ファイル（拡張子とmimeをpngにする）
        $file = UploadedFile::fake()->create('photo.png', 10, 'image/png');

        // ペイロードは Request のキー名に合わせる
        $payload = [
            'name'        => '新商品',
            'brand_name'  => 'テストブランド',
            'description' => 'テスト説明文', // ← Request に合わせる
            'price'       => 1000,
            'condition'   => 1,             // ← Request に合わせる
            'categories'  => $categories->pluck('id')->toArray(),
            'image'       => $file,
        ];

        $response = $this->post('/sell', $payload);

        // 作成された商品を取得
        $item = Item::firstOrFail();

        // 商品詳細へリダイレクト
        $response->assertRedirect(route('items.show', $item->id));

        // items テーブルの保存内容（DBカラム名に合わせる）
        $this->assertDatabaseHas('items', [
            'id'                => $item->id,
            'name'              => '新商品',
            'brand_name'        => 'テストブランド',
            'detail'            => 'テスト説明文', // ← detail に保存される
            'product_condition' => 1,
            'price'             => 1000,
            'user_id'           => $user->id,
        ]);

        // pivot テーブルにカテゴリが紐づいていること
        foreach ($categories as $cat) {
            $this->assertDatabaseHas('category_item', [
                'item_id'     => $item->id,
                'category_id' => $cat->id,
            ]);
        }

        // 画像は public ディスクに保存され、items/ から始まるパスがDBに入る
        $this->assertStringStartsWith('items/', $item->image);
        Storage::disk('public')->assertExists($item->image);
    }
}