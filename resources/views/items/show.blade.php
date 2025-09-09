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
            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}">
        </div>

        {{-- 商品メイン情報 --}}
        <div class="item-detail__info">
            <h1 class="item-detail__name">{{ $item->name }}</h1>
            <p class="item-detail__brand">{{ $item->brand_name ?? 'ブランド情報なし' }}</p>
            <p class="item-detail__price">¥{{ number_format($item->price) }} <span class="item-detail__tax">（税込）</span></p>

            {{-- いいね・コメントアイコン（数値のみ表示） --}}
            <div class="item-detail__icons">
                <form method="POST" action="{{ route('item.like', $item->id) }}" class="item-detail__like-form">
                    @csrf
                    <button type="submit" class="item-detail__like-button">
                        <img src="{{ asset($item->isLikedBy(Auth::user()) ? 'img/icons/star-filled.png' : 'img/icons/star.png') }}" alt="いいね" class="like-icon">
                        {{ $item->likes_count ?? 0 }}
                    </button>
                </form>
                <div class="item-detail__comment-count">
                    <img src="{{ asset('img/icons/comment.png') }}" alt="コメント">{{ $item->comments_count ?? 0 }}
                </div>
            </div>

            {{-- ログイン済みユーザー向け --}}
            @auth
                <a href="{{ route('purchase.show', $item->id) }}" class="item-detail__purchase-button">購入手続きへ</a>
            @endauth

            {{-- ゲスト（未ログイン）ユーザー向け --}}
            @guest
                <a href="{{ route('login') }}" class="item-detail__purchase-button">ログインして購入</a>
            @endguest
        </div>
    </div>

    {{-- 商品説明 --}}
    <section class="item-detail__section">
        <h2 class="item-detail__section-title">商品説明</h2>
        <p class="item-detail__description">{!! nl2br(e($item->detail)) !!}</p>
    </section>

    {{-- 商品の情報（カテゴリ・状態） --}}
    <section class="item-detail__section">
        <h2 class="item-detail__section-title">商品の情報</h2>
        <p>カテゴリー
            @foreach ($item->categories as $category)
                <span class="item-detail__category-tag">{{ $category->name }}</span>
            @endforeach
        </p>
        <p>商品の状態 {{ $item->condition_text }}</p>
    </section>

    {{-- コメント（読み取り専用） --}}
    <section class="item-detail__section">
        <h2 class="item-detail__section-title">コメント({{ $item->comments->count() }})</h2>
        @foreach ($item->comments as $comment)
            <div class="item-detail__comment">
                <div class="item-detail__comment-user-avatar">
                    @if ($comment->user->profile_image_path)
                        <img src="{{ Storage::url($comment->user->profile_image_path) }}" alt="プロフィール画像">
                    @endif
                </div>
                <span class="item-detail__comment-user-name">{{ $comment->user->name }}</span>
                <div class="item-detail__comment-body">{{ $comment->comment }}</div>
            </div>
        @endforeach

        <form method="POST" action="{{ route('comment.store', $item->id) }}" class="item-detail__comment-form">
            @csrf
            <label for="comment">商品へのコメント</label>
            <textarea name="comment" id="comment" rows="4" maxlength="255" required>{{ old('comment') }}</textarea>

            @error('comment')
                <div class="error">{{ $messages }}</div>
            @enderror

            <button type="submit" class="item-detail__comment-form-button" @guest disabled @endguest>コメントを送信する</button>
    </section>

</main>
@endsection