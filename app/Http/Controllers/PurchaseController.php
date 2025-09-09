<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;


class PurchaseController extends Controller
{
    public function show(Item $item)
    {
        // ビューが未実装の場合、一旦デバッグ用の確認レスポンスでもOK
        return response()->json([
            'message' => '購入画面に遷移しました（仮）',
            'item_id' => $item->id,
            'item_name' => $item->name,]);
        // return view('purchase.show', compact('item'));
    }
}
