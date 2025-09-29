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

    /** @test */
    // 「購入する」ボタン押下で購入が完了する
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

    /** @test */
    // 購入した商品は商品一覧画面で「sold」と表示される
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

    /** @test */
    // 購入した商品がプロフィールの購入商品一覧に追加される
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


        $response = $this->get('/mypage?page=buy');

        $response->assertSee($item->name);
        $response->assertSee('storage/' . $item->image);
    }
    /** @test */
    // 決済画面に遷移できる
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

    /** @test */
/** @test */
// 支払い方法を選択すると小計（サマリー）に反映される
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

    // 商品購入画面を開く
    $response = $this->get("/purchase/{$item->id}");

    $response->assertSeeText('コンビニ払い');

    // POSTでカード払いを選択 → checkout に送信
$this->post("/purchase/{$item->id}", [
    'payment_method' => 'credit',
    'shipping_postal' => '1234567',
    'shipping_address' => '東京都渋谷区1-1-1',
    'shipping_building'=> 'テストビル',
]);

    // 再度購入画面を開くと「カード払い」が表示される
    $response = $this->get("/purchase/{$item->id}");

    // 購入履歴で「カード払い」が保存されていることを確認
    $this->assertDatabaseHas('purchases', [
        'user_id' => $user->id,
        'item_id' => $item->id,
        'payment_method' => 'card',
    ]);
}

/** @test */
// 送付先住所変更画面で登録した住所が購入画面に反映される
public function test_changed_shipping_address_is_reflected_in_purchase_page()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $item = Item::factory()->create();

    // 送付先変更
    $this->post("/purchase/address/{$item->id}", [
        'shipping_postal'  => '111-2222',
        'shipping_address' => '大阪市中央区テスト1-2-3',
        'shipping_building'=> 'テストビル101',
    ]);

    // 商品購入画面を再度開く
    $response = $this->get("/purchase/{$item->id}");

    $response->assertSee('111-2222');
    $response->assertSee('大阪市中央区テスト1-2-3');
    $response->assertSee('テストビル101');
}

/** @test */
// 購入した商品に送付先住所が紐づいて保存される
public function test_changed_shipping_address_is_saved_with_purchase()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $item = Item::factory()->create(['is_sold' => false]);

    // 住所を変更
    $this->post("/purchase/address/{$item->id}", [
        'shipping_postal'  => '333-4444',
        'shipping_address' => '名古屋市テスト町4-5-6',
        'shipping_building'=> 'テストマンション202',
    ]);

    // 購入処理を直接叩く
    $this->post("/purchase/{$item->id}", [
        'payment_method'   => 'card',
    ]);

    // DBに保存されていることを確認
    $this->assertDatabaseHas('purchases', [
        'user_id'          => $user->id,
        'item_id'          => $item->id,
        'shipping_postal'  => '333-4444',
        'shipping_address' => '名古屋市テスト町4-5-6',
        'shipping_building'=> 'テストマンション202',
    ]);
}
}