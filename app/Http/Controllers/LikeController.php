<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;


class LikeController extends Controller
{
    public function toggle(Item $item)
    {
        $user = auth()->user();

        // すでにいいねしていたら削除、してなければ追加
        $item->likes()->where('user_id', $user->id)->exists()
            ? $item->likes()->where('user_id', $user->id)->delete()
            : $item->likes()->create(['user_id' => $user->id]);

        return redirect()->back();
    }
}
