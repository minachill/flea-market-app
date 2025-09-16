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
    // dd($request->all()); // 確認用
        if ($item->purchase) {
            return back()->with('error', 'この商品はすでに購入されています');
        }

        $user = auth()->user();
        Purchase::create([
            'user_id'        => auth()->id(),
            'item_id'        => $item->id,
            'address_id'     => $request->address_id, // デフォルト or 変更後
            'payment_method' => $request->payment_method,
            'shipping_postal'   => session('shipping_postal') ?? $user->address->postal_code,
            'shipping_address'  => session('shipping_address') ?? $user->address->address,
            'shipping_building' => session('shipping_building') ?? $user->address->building,
        ]);

        $item->update(['is_sold' => true]);

        session()->forget(['shipping_postal', 'shipping_address', 'shipping_building']);
        return redirect()->route('items.index')->with('success', '購入が完了しました');
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

        return redirect()->route('purchase.show', $item->id)
                        ->with('success', '住所を変更しました');
    }

}
