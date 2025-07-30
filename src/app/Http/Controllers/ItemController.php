<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->tab === 'mylist' && !Auth::check()) {
            // 未ログインでマイリストタブ指定ならログイン画面へ
            return redirect()->route('login');
        }

        if (Auth::check() && $request->tab === 'mylist') {
            // ログイン中 & マイリストタブ
            $items = Auth::user()->likedItems ?? collect([]); // likedItemsは後で実装
            $viewType = 'mylist';
        } else {
            // 未ログイン or おすすめタブ
            // 今はダミーデータ、後でDBのItem::all()に変更
            $items = Item::all();
            $viewType = 'recommend';
        }

        return view('items.index', compact('items', 'viewType'));
    }
}
