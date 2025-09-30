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
            $items = collect();
        } else {
            $query = Auth::user()->likedItems()->orderByPivot('created_at', 'desc');

            if (!empty($keyword)) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }

            $items = $query->get();
        }

        $viewType = 'mylist';

    } else {
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
        $item->loadCount(['likes', 'comments']);
        $item->load(['categories', 'comments.user']);


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

        $path = $request->file('image')->store('items', 'public');


    // 商品登録
        $item = new Item();
        $item->name              = $validated['name'];
        $item->brand_name        = $request->input('brand_name');
        $item->detail            = $validated['description'];
        $item->price             = $validated['price'];
        $item->product_condition = $validated['condition'];
        $item->user_id           = auth()->id();
        $item->image             = $path;


        $item->categories()->attach($validated['categories']);


        return redirect()->route('items.show', $item->id);
    }
}