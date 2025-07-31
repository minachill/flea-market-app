<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;

class Like extends Model
{
    use HasFactory;

    // 「いいね」は1人のユーザーが行う
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 「いいね」は1つの商品に対して行う
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
