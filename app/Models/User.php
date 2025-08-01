<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Purchase;
use App\Models\Address;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

        // 1人のユーザーは複数の商品を出品できる
        public function items()
        {
            return $this->hasMany(Item::class);
        }

        // 1人のユーザーは複数のコメントを投稿できる
        public function comments()
        {
            return $this->hasMany(Comment::class);
        }

        // 1人のユーザーは複数の商品に「いいね」できる
        public function likes()
        {
            return $this->hasMany(Like::class);
        }

        // 1人のユーザーは複数の購入履歴を持つ
        public function purchases()
        {
            return $this->hasMany(Purchase::class);
        }

        // 1人のユーザーは複数の住所を登録できる
        public function addresses()
        {
            return $this->hasMany(Address::class);
        }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
