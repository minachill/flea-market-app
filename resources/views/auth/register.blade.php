@extends('layouts.auth')

@section('title', '会員登録画面')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endpush

@section('content')
<div class="register-page">
    <h2 class="register-page__title">会員登録</h2>

    <form class="register-form" method="POST" action="{{ route('register') }}" novalidate>
        @csrf
        {{-- ユーザ名 --}}
        <div class="form-group">
            <label class="form-group__label" for="name">ユーザー名</label>
            <input class="form-group__input" id="name" type="text" name="name" value="{{ old('name') }}" autofocus>
            <div class="form-group__error">
                @error('name')
                    {{ $message }}
                @enderror
            </div>
        </div>

        {{-- メールアドレス --}}
        <div class="form-group">
            <label class="form-group__label" for="email">メールアドレス</label>
            <input class="form-group__input" id="email" type="email" name="email" value="{{ old('email') }}">
            <div class="form-group__error">
                @error('email')
                    {{ $message }}
                @enderror
            </div>
        </div>

        {{-- パスワード --}}
        <div class="form-group">
            <label class="form-group__label" for="password">パスワード</label>
            <input class="form-group__input" id="password" type="password" name="password">
            <div class="form-group__error">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
        </div>

        {{-- 確認用パスワード --}}
        <div class="form-group">
            <label class="form-group__label" for="password_confirmation">確認用パスワード</label>
            <input class="form-group__input" id="password_confirmation" type="password" name="password_confirmation">
            <div class="form-group__error">
                @error('password_confirmation')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <button type="submit" class="register-form__submit">登録する</button>
    </form>

    <p class="register-page__login-link">
        <a href="{{ route('login') }}">ログインはこちら</a>
    </p>

</div>
@endsection