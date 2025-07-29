<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        // 初回ログインならプロフィール設定画面にリダイレクト
        if ($user->is_first_login) {
            $user->is_first_login = false;
            $user->save();

            return redirect()->route('profile.setup');
        }

        // 通常のログイン時はデフォルト画面へ
        return redirect()->intended('/');
    }
}