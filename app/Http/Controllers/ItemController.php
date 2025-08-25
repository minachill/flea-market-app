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
            $items = Auth::user()->likedItems;
            $viewType = 'mylist';
        } else {
            // 未ログイン or おすすめタブ
            if (Auth::check()) {
                $items = Item::where(function ($query) {
                    $query->whereNull('user_id')
                        ->orWhere('user_id', '!=', Auth::id());
                })->get();
            } else {
                $items = Item::all();
            }
            $viewType = 'recommended';
        }

        return view('items.index', compact('items', 'viewType'));
    }
}
