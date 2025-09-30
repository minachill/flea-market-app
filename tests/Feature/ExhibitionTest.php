<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExhibitionTest extends TestCase
{
    use RefreshDatabase;


public function test_it_registers_exhibited_item()
{
    Storage::fake('public');

    $user = User::factory()->create(['email_verified_at' => now()]);
    $this->actingAs($user);
    $categories = Category::factory()->count(2)->create();
    $file = UploadedFile::fake()->create('dummy.png', 10);


    $data = [
        'name'        => '新商品',
        'price'       => 1000,
        'brand_name'  => 'テストブランド',
        'detail'      => 'テスト説明文',
        'product_condition'   => '1',
        'categories'  => [1, 2],
        'image'       => $file,
    ];

    $response = $this->post('/sell', $data);


    $item = Item::first();
    $response->assertRedirect(route('items.show', $item->id));


    $this->assertDatabaseHas('items', [
        'name'       => '新商品',
        'brand_name' => 'テストブランド',
        'price'      => 1000,
        'description'     => 'テスト説明文',
        'condition'  => '1',
        'user_id'    => $user->id,
        'categories'  => [1, 2],
        'image'      => $file,
    ]);

        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $categories[0]->id,
        ]);

    Storage::disk('public')->assertExists($item->image);
}
}