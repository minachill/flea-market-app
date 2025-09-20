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
        $purchasedItems = Item::whereIn('id', $user->purchases()->pluck('item_id'))
                            ->latest()
                            ->get();

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

        // プロフィール画像アップロード
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->image = $path;
            $user->save();
        }

        // ユーザー情報更新
        $user->name = $request->name;
        $user->save();

        $address = Address::where('user_id', $user->id)->first();

        if ($address) {
            // 既存レコードを更新
            $address->update([
                'postal_code' => $request->postal_code,
                'address'     => $request->address,
                'building'    => $request->building,
            ]);
        }

        return redirect()->route('mypage.index');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('mypage.edit', compact('user'));
    }
}
