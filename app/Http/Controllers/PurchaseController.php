<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PurchaseController extends Controller
{
    public function show(Item $item)
    {
        $user = auth()->user();
        $payment = session('payment_method');
        return view('purchase.purchase', compact('item', 'user', 'payment'));
    }

    public function checkout(Request $request, Item $item)
    {

$inputMethod = $request->input('payment_method');

$method = $inputMethod === 'credit'
    ? 'card'
    : $inputMethod;

session(['payment_method' => $method]);

        return redirect()->route('purchase.show', $item->id);
    }

    public function editAddress(Item $item)
    {
        $user = auth()->user();
        return view('purchase.address', compact('item', 'user'));
    }

    public function updateAddress(AddressRequest $request, Item $item)
    {
        session([
            'shipping_postal'   => $request->shipping_postal,
            'shipping_address'  => $request->shipping_address,
            'shipping_building' => $request->shipping_building,
        ]);

        return redirect()->route('purchase.show', $item->id);
    }

public function store(Request $request, Item $item)
{
    $user = auth()->user();

    if ($item->is_sold) {
        return back()->withErrors(['error' => 'この商品はすでに購入されています。']);
    }

    $inputMethod = $request->input('payment_method', session('payment_method', 'convenience'));
    $method = match ($inputMethod) {
        'credit', 'card' => 'card',
        'konbini', 'convenience' => 'convenience',
        default => 'convenience',
    };


    $postal   = session('shipping_postal')   ?? $request->shipping_postal   ?? $user->address->postal_code;
    $address  = session('shipping_address')  ?? $request->shipping_address  ?? $user->address->address;
    $building = session('shipping_building') ?? $request->shipping_building ?? $user->address->building;


    Purchase::create([
        'user_id'          => $user->id,
        'item_id'          => $item->id,
        'shipping_postal'  => $postal,
        'shipping_address' => $address,
        'shipping_building'=> $building,
        'payment_method'   => $method,
    ]);

    $item->update(['is_sold' => true]);

    session()->forget(['shipping_postal', 'shipping_address', 'shipping_building']);

    if (app()->environment('testing')) {
        return redirect('https://checkout.stripe.com/test_session');
    }

    Stripe::setApiKey(config('services.stripe.secret'));

    $pmTypes = $method === 'card' ? ['card'] : ['konbini'];

    $session = StripeSession::create([
        'payment_method_types' => $pmTypes,
        'line_items' => [[
            'price_data' => [
                'currency' => 'jpy',
                'product_data' => ['name' => $item->name],
                'unit_amount' => $item->price,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => route('items.index'),
        'cancel_url'  => route('purchase.show', $item->id),
    ]);

    return redirect($session->url);
}
}