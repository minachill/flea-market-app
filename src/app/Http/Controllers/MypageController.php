<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class MypageController extends Controller
{
    public function index()
    {
        return view('mypage.index');
    }

    public function update(Request $request)
    {
        // プロフィール更新処理
    }

    public function purchased()
    {
        // 購入一覧の表示処理
    }

    public function exhibited()
    {
        // 出品一覧の表示処理
    }
}
