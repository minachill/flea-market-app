<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        if ($request->tab === 'mylist') {
            if (!Auth::check()) {
                return redirect()->route('login');
            }

        // マイリスト（ログイン済み）＋検索
            $query = Auth::user()->likedItems()->orderBy('created_at', 'desc');

            if (!empty($keyword)) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }

            $items = $query->get();
            $viewType = 'mylist';

        } else {
        // 通常一覧（おすすめ）
            $query = Item::query();

            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }

            if (!empty($keyword)) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }
            $items = $query->get();
            $viewType = 'recommend';
        }

        return view('items.index', compact('items', 'viewType', 'keyword'));
    }

    public function show(Item $item)
    {
        $item->loadCount(['likes', 'comments']); // ← 数だけ取る
        $item->load(['categories', 'comments.user']); // ← 関連必要なものだけ


        return view('items.show', compact('item'));
    }
}
