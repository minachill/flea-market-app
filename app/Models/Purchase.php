<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;

class Purchase extends Model
{
    use HasFactory;

    // この購入はどのユーザーによるものか
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // この購入はどの商品に対するものか
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // この購入にはどの住所が使われたか
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
