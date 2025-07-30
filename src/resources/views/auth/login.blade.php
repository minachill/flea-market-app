@extends('layouts.app')

@section('title', 'ログイン画面')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endpush

@section('content')
<div class="form-container">
    <h2>ログイン</h2>

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        {{-- メールアドレス --}}
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" autofocus>
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

        {{-- ログインボタン --}}
        <div class="form-group">
            <button type="submit" class="btn-submit">ログインする</button>
        </div>
    </form>

    <div class="form-footer">
        <a href="{{ route('register') }}">会員登録はこちら</a>
    </div>
</div>
@endsection