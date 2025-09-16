<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Http\Requests\ProfileRequest;


class MypageController extends Controller
{
        // プロフィール表示ページ
    public function index(Request $request)
    {
        $user = Auth::user();

        // 出品した商品
        $exhibitedItems = $user->items()->latest()->get();

        // 購入した商品（purchases 経由で取得）
        $purchasedItems = Item::whereIn('id', $user->purchases()->pluck('item_id'))
                            ->latest()
                            ->get();

        return view('mypage.index', compact('user', 'exhibitedItems', 'purchasedItems'));
    }

    // プロフィール更新
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        // User 情報更新
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        // 住所更新（既存があれば更新、なければ作成）
        Address::updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $request->postal_code,
                'address'     => $request->address,
                'building'    => $request->building,
            ]
        );

        return redirect()->route('mypage.index')->with('success', 'プロフィールを更新しました');
    }

    // 購入商品リスト
    public function purchased()
    {
        $user = Auth::user();
        $purchases = $user->purchases()->with('item')->get();
        return view('mypage.purchased', compact('purchases'));
    }

    // 出品商品リスト
    public function exhibited()
    {
        $user = Auth::user();
        $items = $user->items;
        return view('mypage.exhibited', compact('items'));
    }
}
