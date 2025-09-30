<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_displays_user_info()
    {
    $user = User::factory()->create([
        'name' => 'テスト太郎',
        'email_verified_at' => now(),
        'profile_image_path' => 'profile_images/test_profile.png',
    ]);


        $exhibitedItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品',
        ]);
        $purchasedItem = Item::factory()->create(['name' => '購入商品']);
        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $purchasedItem->id,
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $responseSell = $this->actingAs($user)->get('/profile?page=sell');
        $responseSell->assertSee('テスト太郎')
                ->assertSee($exhibitedItem->name)
                ->assertDontSee($purchasedItem->name);


        $responseBuy = $this->actingAs($user)->get('/profile?page=buy');
        $responseBuy->assertSee($purchasedItem->name);
    }


    public function test_it_displays_user_edit_form_with_initial_values()
    {
        $user = User::factory()->create([
            'name' => '編集前太郎',
            'email_verified_at' => now(),
            'profile_image_path' => 'profile_images/test_profile.png',
        ]);

        Address::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストマンション101',
        ]);

        $response = $this->actingAs($user)->get('/profile/edit');

        $response->assertSee('編集前太郎')
                ->assertSee('123-4567')
                ->assertSee('東京都渋谷区1-1-1')
                ->assertSee('テストマンション101')
                ->assertSee('storage/profile_images/test_profile.png');
    }
}