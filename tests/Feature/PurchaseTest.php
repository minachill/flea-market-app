<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Address;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;


    public function test_user_can_purchase_an_item()
    {
        $user = User::factory()->create();
        Address::factory()->create(['user_id' => $user->id]);
        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'price' => 2000,
            'is_sold' => false,
        ]);

        $response = $this->actingAs($user)->post("/purchase/{$item->id}", [
            'shipping_postal' => '1234567',
            'shipping_address' => '東京都渋谷区1-1-1',
            'shipping_building' => 'テストビル',
            'payment_method' => 'card',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'is_sold' => true,
        ]);
    }

    public function test_purchased_item_is_marked_as_sold_in_item_list()
    {
        $user = User::factory()->create();
        Address::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $item = Item::factory()->create(['is_sold' => false]);

        $this->post("/purchase/{$item->id}", [
            'shipping_postal' => '1234567',
            'shipping_address' => '東京都渋谷区1-1-1',
            'payment_method' => 'card',
        ]);

        $response = $this->get('/');

        $response->assertSee('sold');
    }


    public function test_purchased_item_is_shown_in_user_profile_buy_list()
    {
        $user = User::factory()->create();
        Address::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $item = Item::factory()->create([
            'name' => 'テスト購入商品',
            'image' => 'dummy.png',
            'is_sold' => false,
        ]);
        $this->post("/purchase/{$item->id}", [
            'shipping_postal'  => '1234567',
            'shipping_address' => '東京都渋谷区1-1-1',
            'shipping_building'=> 'テストビル',
            'payment_method'   => 'card',
        ]);


        $response = $this->get('/profile?page=buy');

        $response->assertSee($item->name);
        $response->assertSee('storage/' . $item->image);
    }

    public function test_it_redirects_to_stripe_checkout_page()
    {
        $user = User::factory()->create();
        Address::factory()->create(['user_id' => $user->id]);
        $item = Item::factory()->create(['price' => 1500]);

        $response = $this->actingAs($user)->post(route('purchase.store', $item->id), [
            'payment_method' => 'card',
        ]);

        $response->assertRedirect();
        $this->assertStringContainsString(
            'https://checkout.stripe.com',
            $response->headers->get('Location')
        );
    }


public function test_selected_payment_method_is_reflected_in_summary()
{
    $user = User::factory()->create();
    Address::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    Address::factory()->create([
    'user_id'     => $user->id,
    'postal_code' => '1234567',
    'address'     => '東京都渋谷区1-1-1',
    'building'    => 'テストビル',
]);

    $this->actingAs($user);

    $item = Item::factory()->create([
        'name' => '支払いテスト商品',
        'price' => 3000,
        'is_sold' => false,
    ]);

    $response = $this->get("/purchase/{$item->id}");

    $response->assertSeeText('コンビニ払い');


$this->post("/purchase/{$item->id}", [
    'payment_method' => 'credit',
    'shipping_postal' => '1234567',
    'shipping_address' => '東京都渋谷区1-1-1',
    'shipping_building'=> 'テストビル',
]);


    $response = $this->get("/purchase/{$item->id}");


    $this->assertDatabaseHas('purchases', [
        'user_id' => $user->id,
        'item_id' => $item->id,
        'payment_method' => 'card',
    ]);
}


public function test_changed_shipping_address_is_reflected_in_purchase_page()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $item = Item::factory()->create();

    $this->post("/purchase/address/{$item->id}", [
        'shipping_postal'  => '111-2222',
        'shipping_address' => '大阪市中央区テスト1-2-3',
        'shipping_building'=> 'テストビル101',
    ]);

    $response = $this->get("/purchase/{$item->id}");

    $response->assertSee('111-2222');
    $response->assertSee('大阪市中央区テスト1-2-3');
    $response->assertSee('テストビル101');
}


public function test_changed_shipping_address_is_saved_with_purchase()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $item = Item::factory()->create(['is_sold' => false]);

    $this->post("/purchase/address/{$item->id}", [
        'shipping_postal'  => '333-4444',
        'shipping_address' => '名古屋市テスト町4-5-6',
        'shipping_building'=> 'テストマンション202',
    ]);

    $this->post("/purchase/{$item->id}", [
        'payment_method'   => 'card',
    ]);

    $this->assertDatabaseHas('purchases', [
        'user_id'          => $user->id,
        'item_id'          => $item->id,
        'shipping_postal'  => '333-4444',
        'shipping_address' => '名古屋市テスト町4-5-6',
        'shipping_building'=> 'テストマンション202',
    ]);
}
}