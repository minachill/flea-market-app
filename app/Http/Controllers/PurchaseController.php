<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;

class PurchaseController extends Controller
{
    public function show(Item $item)
    {
        $user = auth()->user(); // ログイン中のユーザー
        return view('purchase.purchase', compact('item', 'user'));
    }

    public function store(PurchaseRequest $request, Item $item)
    {
        // dd($request->all(), session()->all());
        if ($item->purchase) {
            return back();
        }

        $user = auth()->user();
        // セッション値があれば優先、無ければ必ずプロフィール住所を使う
        $shipping_postal   = session('shipping_postal') ?? $user->address->postal_code;
        $shipping_address  = session('shipping_address') ?? $user->address->address;
        $shipping_building = session('shipping_building') ?? $user->address->building;

        Purchase::create([
            'user_id'           => $user->id,
            'item_id'           => $item->id,
            'payment_method'    => $request->payment_method,
            'shipping_postal'   => $shipping_postal,
            'shipping_address'  => $shipping_address,
            'shipping_building' => $shipping_building,
        ]);

        $item->update(['is_sold' => true]);

        session()->forget(['shipping_postal', 'shipping_address', 'shipping_building']);
        return redirect()->route('items.index');
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

}
