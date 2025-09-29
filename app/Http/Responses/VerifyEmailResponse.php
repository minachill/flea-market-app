<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;
use Illuminate\Support\Facades\Auth;

class VerifyEmailResponse implements VerifyEmailResponseContract
{
    /**
     * Handle the response after the user's email has been verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = Auth::user();

        // プロフィール未設定ならプロフィール編集画面へ
        if (! $user->is_profile_set) {
            return redirect()->route('mypage.edit');
        }

        return redirect()->route('items.index');
    }
}