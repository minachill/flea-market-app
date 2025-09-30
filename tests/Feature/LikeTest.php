<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_allows_user_to_like_an_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)->post("/item/{$item->id}/like");

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);


        $response = $this->get("/item/{$item->id}");
        $response->assertSee('1'); 
    }


    public function test_it_allows_user_to_unlike_an_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();


        $this->actingAs($user)->post("/item/{$item->id}/like");

        $this->actingAs($user)->post("/item/{$item->id}/like");

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get("/item/{$item->id}");
        $response->assertSee('<span class="item-detail__likes-count">0</span>', false);
    }


    public function test_it_displays_filled_icon_for_liked_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->get("/item/{$item->id}");
        $response->assertSee('star.png');

        $this->actingAs($user)->post("/item/{$item->id}/like");

        $response = $this->get("/item/{$item->id}");
        $response->assertSee('star-filled.png');
    }
}