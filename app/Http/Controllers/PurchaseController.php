<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function show(Item $item)
    {
        $user = auth()->user(); // ログイン中のユーザー
        return view('purchase.purchase', compact('item', 'user'));
    }

    public function editAddress(Item $item)
    {
        // 現在ログイン中のユーザーを取得
        $user = auth()->user();

        // 住所変更画面を表示
        return view('purchase.address', compact('item', 'user'));
    }

    public function updateAddress(AddressRequest $request, Item $item)
    {
        // セッションに保存
        session([
            'shipping_postal'   => $request->shipping_postal,
            'shipping_address'  => $request->shipping_address,
            'shipping_building' => $request->shipping_building,
        ]);

        return redirect()->route('purchase.show', $item->id);
    }

    public function checkout(PurchaseRequest $request, Item $item)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $method = $request->input('payment_method') === 'convenience'
            ? ['konbini']
            : ['card'];

        $session = Session::create([
            'payment_method_types' => $method,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            // Stripeが成功時に飛ばすURL
            'success_url' => route('purchase.success', $item->id),
            'cancel_url'  => route('purchase.show', $item->id),
        ]);

        return redirect($session->url);
    }

    public function success(Item $item)
    {
        $user = auth()->user();

        Purchase::create([
            'user_id'           => $user->id,
            'item_id'           => $item->id,
            'payment_method'    => 'stripe', // 実際はwebhookで管理するのがベスト
            'shipping_postal'   => session('shipping_postal') ?? $user->address->postal_code,
            'shipping_address'  => session('shipping_address') ?? $user->address->address,
            'shipping_building' => session('shipping_building') ?? $user->address->building,
        ]);

        $item->update(['is_sold' => true]);

        session()->forget(['shipping_postal', 'shipping_address', 'shipping_building']);

        return redirect()->route('items.index');
    }
}
