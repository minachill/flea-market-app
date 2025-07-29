<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;


class Comment extends Model
{
    use HasFactory;

    // コメントは1人のユーザーに属する（投稿者）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // コメントは1つの商品に属する
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
