@extends('layouts.app')

@section('title', '商品詳細画面')

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
            <p class="item-detail__price">
                <span class="item-detail__yen">¥</span>{{ number_format($item->price) }}
                <span class="item-detail__tax">(税込)</span>
            </p>

        {{-- いいね・コメントアイコン（数値のみ表示） --}}
            <div class="item-detail__icons">
                <form method="POST" action="{{ route('item.like', $item->id) }}" class="item-detail__like-form">
                    @csrf
                    <button type="submit" class="item-detail__like-button">
                        <img src="{{ asset($item->isLikedBy(Auth::user()) ? 'img/icons/star-filled.png' : 'img/icons/star.png') }}" alt="いいね" class="item-detail__like-icon">
                        <span class="item-detail__likes-count">{{ $item->likes_count ?? 0 }}</span>
                    </button>
                </form>
                <div class="item-detail__comment-button">
                    <img src="{{ asset('img/icons/comment.png') }}" alt="コメント"><span class="tem-detail__comments-count">{{ $item->comments_count ?? 0 }}</span>
                </div>
            </div>

        {{-- ログイン済みユーザー向け --}}
            @auth
                <a href="{{ route('purchase.show', $item->id) }}" class="item-detail__purchase-button">購入手続きへ</a>
            @endauth

        {{-- ゲスト（未ログイン）ユーザー向け --}}
            @guest
                <a href="{{ route('login') }}" class="item-detail__purchase-button">購入手続きへ</a>
            @endguest

    {{-- 商品説明 --}}
            <section class="item-detail__section">
                <h2 class="item-detail__section-title">商品説明</h2>
                <p class="item-detail__description">{!! nl2br(e($item->detail)) !!}</p>
            </section>

    {{-- 商品の情報（カテゴリ・状態） --}}
            <section class="item-detail__section">
                <h2 class="item-detail__section-title">商品の情報</h2>
                <div class="item-detail__section-row">
                    <p class="item-detail__section-title-category">カテゴリー</p>
                    @foreach ($item->categories as $category)
                        <span class="item-detail__category-tag">{{ $category->name }}</span>
                    @endforeach
                </div>
                <div class="item-detail__section-row">
                    <p class="item-detail__section-title-condition">商品の状態</p>
                    <span class="item-detail__condition-tag">{{ $item->condition_text }}</span>
                </div>
            </section>

    {{-- コメント（読み取り専用） --}}
            <section class="item-detail__section">
                <h2 class="item-detail__section-title-comment">コメント({{ $item->comments->count() }})</h2>
                @foreach ($item->comments as $comment)
                    <div class="item-detail__comment">
                        <div class="item-detail__comment-user-avatar">
                            @if ($comment->user->profile_image_path)
                                <img src="{{ Storage::url($comment->user->profile_image_path) }}" alt="プロフィール画像">
                            @endif
                        </div>
                        <span class="item-detail__comment-user-name">{{ $comment->user->name }}</span>
                    </div>
                    <div class="item-detail__comment-body">{{ $comment->comment }}</div>
                @endforeach
                <form method="POST" action="{{ route('comment.store', $item->id) }}" class="item-detail__comment-form">
                    @csrf
                <label for="comment" class="item-detail__comment-form-title">商品へのコメント</label>
                <textarea class="item-detail__comment-form-input" name="comment" id="comment">{{ old('comment') }}</textarea>
                @error('comment')
                    <div class="item-detail__comment-form-error">{{ $message }}</div>
                @enderror
                <button type="submit" class="item-detail__comment-form-button" @guest disabled @endguest>コメントを送信する</button>
            </section>
            {{-- 👆 @guest disabled @endguestで、未ログインユーザーはコメント送信ができない仕様、要確認‼️--}}
        </div>
    </div>
</main>
@endsection