@extends('layouts.auth')

@section('title', 'メール認証誘導画面')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endpush

@section('content')
<div class="verify-email">
    <p class="verify-email__message">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </p>

    {{-- 認証画面へのリンク --}}
    <a href="http://localhost:8025" target="_blank" class="verify-email__button">
        認証はこちらから
    </a>

    {{-- 再送リンク --}}
    <form method="POST" action="{{ route('verification.send') }}" class="verify-email__resend">
        @csrf
        <button type="submit" class="verify-email__resend-link">
            認証メールを再送する
        </button>
    </form>
</div>
@endsection