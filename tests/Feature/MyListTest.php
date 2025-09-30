<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_displays_only_liked_items_in_mylist()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $likedItem = Item::factory()->create(['name' => 'いいね商品']);
        $unlikedItem = Item::factory()->create(['name' => 'いいねしてない商品']);


        Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertSee('いいね商品')
                ->assertDontSee('いいねしてない商品');
    }


    public function test_it_displays_sold_label_for_purchased_items_in_mylist()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create(['name' => '購入済み商品', 'is_sold' => true]);

            Purchase::factory()->create([
        'user_id' => $user->id,
        'item_id' => $item->id,
    ]);
        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertSee('Sold');
    }


    public function test_it_redirects_guest_users_to_login_when_accessing_mylist()
    {
        $response = $this->get('/?tab=mylist');

        $response->assertRedirect('/login');
    }
}