@extends('layouts.auth')

@section('title', 'ログイン画面')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endpush

@section('content')
<div class="login-page">
    <h2 class="login-page__title">ログイン</h2>

    <form class="login-form" method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        {{-- メールアドレス --}}
        <div class="form-group">
            <label class="form-group__label" for="email">メールアドレス</label>
            <input class="form-group__input" id="email" type="email" name="email" value="{{ old('email') }}" autofocus>
            <div class="form-group__error">
                @error('email')
                    {{ $message }}
                @enderror
            </div>
        </div>

        {{-- パスワード --}}
        <div class="form-group">
            <label class="form-group__label" for="password">パスワード</label>
            <input class="form-group__input"id="password" type="password" name="password">
            <div class="form-group__error">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
        </div>

        {{-- ログインボタン --}}
        <div class="form-group">
            <button type="submit" class="login-form__submit">ログインする</button>
        </div>
    </form>

    <div class="login-page__register-link">
        <a href="{{ route('register') }}">会員登録はこちら</a>
    </div>
</div>
@endsection