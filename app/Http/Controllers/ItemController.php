<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;


class ItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->query('keyword');
        $tab = $request->query('tab');

        if ($tab === 'mylist') {
            if (!Auth::check()) {
                return redirect()->route('login');
            }

        // マイリスト（ログイン済み）＋検索
            $query = Auth::user()->likedItems()->orderByPivot('created_at', 'desc');

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

    // 出品画面表示、出品処理
    public function create()
    {
        $categories = Category::all();
        return view('exhibition.create', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();
        // 画像の保存処理
        $path = $request->file('image')->store('items', 'public');
        // → storage/app/public/items に保存される

    // 商品登録
        $item = new Item();
        $item->name              = $validated['name'];
        $item->brand_name        = $request->input('brand_name');   // brand_name に合わせる
        $item->detail            = $validated['description'];       // detail カラムに保存
        $item->price             = $validated['price'];
        $item->product_condition = $validated['condition'];         // int (1〜4)
        $item->user_id           = auth()->id();
        $item->image             = $path;                          // 画像パスを保存
        $item->save();

        // カテゴリを紐付け（多対多）
        $item->categories()->attach($validated['categories']);

        // 商品詳細画面へリダイレクト
        return redirect()->route('items.show', $item->id);
    }
}