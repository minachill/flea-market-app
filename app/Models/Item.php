<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Purchase;
use App\Models\Category;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand_name',
        'detail',
        'price',
        'product_condition',
        'image',
        'is_sold',
    ];

    // ユーザー（出品者）とのリレーション：ItemはUserに属する（多対1）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // コメントとのリレーション：Itemは複数コメントを持つ（1対多）
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // いいねとのリレーション：Itemは複数のLikeを持つ（1対多）
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    // いいねしたユーザーとのリレーション：Itemは複数のUserに「いいね」される（多対多）
    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    // いいね済みか判定（ビューなどで使用）
    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes->contains('user_id', $user->id);
    }

    // 購入履歴とのリレーション：Itemは1つのPurchaseに関連（1対1と想定）
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    // カテゴリとのリレーション：多対多（中間テーブル category_item）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }

    public function getConditionTextAttribute(): string
    {
        return match ($this->product_condition) {
            1 => '良好',
            2 => '目立った傷や汚れなし',
            3 => 'やや傷や汚れあり',
            4 => '状態が悪い',
        };
    }
}
