<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        // ✅ 未認証ならメール認証誘導画面へ
        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        // ✅ プロフィール未設定ならプロフィール設定画面へ
        if (! $user->is_profile_set) {
            return redirect()->route('mypage.edit');
        }

        // ✅ 通常は商品一覧へ
        return redirect()->route('items.index');
    }
}
