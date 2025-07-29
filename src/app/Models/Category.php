<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class Category extends Model
{
    use HasFactory;

    // 1つのカテゴリは複数の商品を持つ（多対多）
    public function items()
    {
        return $this->belongsToMany(Item::class, 'category_item');
    }
}
