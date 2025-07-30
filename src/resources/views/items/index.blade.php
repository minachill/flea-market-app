@extends('layouts.app')

@section('title', '商品一覧')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endpush


{{-- この画面専用のヘッダー --}}
@section('header')
    {{-- 検索フォーム --}}
    <form action="/" method="GET" class="search-form">
        <input type="text" name="keyword" placeholder="なにをお探しですか？">
    </form>

    {{-- ボタン類 --}}
    <div class="header-actions">
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
            <a href="/mypage">マイページ</a>
            <a href="/sell" class="btn-sell">出品</a>
        @endauth

        @guest
            <a href="{{ route('login') }}">ログイン</a>
            <a href="{{ route('register') }}">会員登録</a>
            <a href="{{ route('login') }}" class="btn-sell">出品</a>
        @endguest
    </div>
</header>
@endsection

@section('content')
<div class="container">

    {{-- タブメニュー --}}
    <div class="tab-menu">
        <a href="/" class="tab {{ $viewType === 'recommend' ? 'active' : '' }}">おすすめ</a>
        @auth
        {{-- ログイン時 --}}
            <a href="/?tab=mylist" class="tab {{ $viewType === 'mylist' ? 'active' : '' }}">マイリスト</a>
        @endauth

        @guest
        {{-- 未ログイン時はログイン画面に遷移 --}}
            <a href="{{ route('login') }}" class="tab">マイリスト</a>
        @endguest
    </div>

    {{-- 商品カードの一覧 --}}
    <div class="item-list">
        @foreach($items as $item)
            <div class="item-card">
            {{-- 商品画像 + Soldラベル --}}
                <div class="item-image-wrapper">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="item-image">

                {{-- 購入済みならSoldを表示 --}}
                    @if($item->is_sold)
                        <div class="sold-label">Sold</div>
                    @endif
                </div>

            {{-- 商品名 --}}
                <p class="item-name">{{ $item->name }}</p>
            </div>
        @endforeach
</div>

</div>
@endsection