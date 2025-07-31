<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Purchase;

class Address extends Model
{
    use HasFactory;

    // ユーザーとのリレーション（1人のユーザーが複数の住所を持つ）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 購入とのリレーション（1つの住所が複数の購入に使われる可能性がある）
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

}
