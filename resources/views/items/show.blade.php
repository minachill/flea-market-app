@extends('layouts.app')

@section('title', '商品詳細')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endpush

@section('content')
<main class="item-detail">
    <div class="item-detail__container">

        {{-- 商品画像 --}}
        <div class="item-detail__image">
            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
        </div>

        {{-- 商品メイン情報 --}}
        <div class="item-detail__info">
            <h1 class="item-detail__name">{{ $item->name }}</h1>
            <p class="item-detail__brand">{{ $item->brand_name ?? 'ブランド情報なし' }}</p>
            <p class="item-detail__price">¥{{ number_format($item->price) }} <span class="item-detail__tax">（税込）</span></p>

            {{-- いいね・コメントアイコン（数値のみ表示） --}}
            <div class="item-detail__icons">
                <div class="item-detail__like">
                    <span>♡</span> <span>{{ $item->likes_count ?? 0 }}</span>
                </div>
                <div class="item-detail__comment-count">
                    <span>💬</span> <span>{{ $item->comments_count ?? 0 }}</span>
                </div>
            </div>

            {{-- 購入ボタン（ログイン前は非アクティブ or ログイン誘導） --}}
            <a href="{{ route('login') }}" class="item-detail__purchase-button">購入手続きへ</a>
        </div>
    </div>

    {{-- 商品説明 --}}
    <section class="item-detail__section">
        <h2 class="item-detail__section-title">商品説明</h2>
        <p class="item-detail__description">{!! nl2br(e($item->description)) !!}</p>
    </section>

    {{-- 商品の情報（カテゴリ・状態） --}}
    <section class="item-detail__section">
        <h2 class="item-detail__section-title">商品の情報</h2>
        <p>カテゴリー：
            @foreach ($item->categories as $category)
                <span class="item-detail__category-tag">{{ $category->name }}</span>
            @endforeach
        </p>
        <p>商品の状態：{{ $item->condition }}</p>
    </section>

    {{-- コメント（読み取り専用） --}}
    <section class="item-detail__section">
        <h2 class="item-detail__section-title">コメント({{ $item->comments->count() }})</h2>
        @foreach ($item->comments as $comment)
            <div class="item-detail__comment">
                <div class="item-detail__comment-user">👤 {{ $comment->user->name ?? '匿名' }}</div>
                <div class="item-detail__comment-body">{{ $comment->body }}</div>
            </div>
        @endforeach

        {{-- コメントフォーム（ログイン前は非表示 or 誘導） --}}
        <div class="item-detail__comment-form">
            <p>コメント投稿は<a href="{{ route('login') }}">ログイン</a>が必要です。</p>
        </div>
    </section>

</main>
@endsection