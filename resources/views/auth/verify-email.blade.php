@extends('layouts.auth')

@section('title', 'メール認証')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endpush

@section('content')
    <div class="verify-email">
        <h2>メール認証が必要です</h2>
        <p>登録したメールアドレスに確認リンクを送信しました。</p>
        <p>メールのリンクをクリックして認証を完了してください。</p>

        @if (session('status') == 'verification-link-sent')
            <div class="alert-success">
                新しい確認リンクを送信しました！
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit">認証メールを再送する</button>
        </form>
    </div>
@endsection