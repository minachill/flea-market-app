<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Http\Requests\ProfileRequest;
use App\Models\Item;



class MypageController extends Controller
{
    // プロフィール表示ページ（タブ切り替え対応）
    public function index(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page', 'sell'); // デフォルトは「出品した商品」

        // 出品した商品
        $exhibitedItems = $user->items()->latest()->get();

        // 購入した商品
        // 購入した商品（Purchaseごと渡す）
        $purchasedItems = $user->purchases()->with('item')->latest()->get();

        return view('mypage.mypage', [
            'user' => $user,
            'page' => $page,
            'exhibitedItems' => $exhibitedItems,
            'purchasedItems' => $purchasedItems,
        ]);
    }

    // プロフィール更新
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $user->update($request->validated());

        // プロフィール画像アップロード
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->image = $path;
        }

        $isFirstSetup = ! $user->is_profile_set;
        // ✅ プロフィール設定済みに更新
        $user->is_profile_set = true;
        $user->save();

        // ✅ 住所：初回は新規作成、2回目以降は更新
        Address::updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $request->postal_code,
                'address'     => $request->address,
                'building'    => $request->building,
            ]
        );

    // ✅ 初回更新 → 商品一覧
        if ($isFirstSetup) {
            return redirect()->route('items.index');
        }

        // ✅ 2回目以降 → マイページ
        return redirect()->route('mypage.index');
    }

    public function edit()
    {
        $user = Auth::user()->load('address');


        return view('mypage.edit', compact('user'));
    }
}
