@extends('layouts.app')

@section('title', '会員登録')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endpush

@section('content')
    <h2>会員登録</h2>
    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf
        {{-- ユーザ名 --}}
        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" autofocus>
            <div class="error">
                @error('name')
                    {{ $message }}
                @enderror
            </div>
        </div>

        {{-- メールアドレス --}}
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}">
            <div class="error">
                @error('email')
                    {{ $message }}
                @enderror
            </div>
        </div>

        {{-- パスワード --}}
        <div class="form-group">
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password">
            <div class="error">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
        </div>

        {{-- 確認用パスワード --}}
        <div class="form-group">
            <label for="password_confirmation">確認用パスワード</label>
            <input id="password_confirmation" type="password" name="password_confirmation">
            <div class="error">
                @error('password_confirmation')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <button type="submit" class="btn-submit">登録する</button>
    </form>

    <p class="login-link">
        <a href="{{ route('login') }}">ログインはこちら</a>
    </p>
@endsection