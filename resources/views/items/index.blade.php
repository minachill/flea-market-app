@extends('layouts.app')

@section('title', '商品一覧画面')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endpush

@section('content')
<div class="item-index">

    {{-- タブメニュー --}}
    <div class="item-index__tab-menu">
        <a href="/" class="item-index__tab {{ $viewType === 'recommend' ? 'item-index__tab--active' : '' }}">おすすめ</a>
        @auth
        {{-- ログイン時 --}}
            <a href="/?tab=mylist" class="item-index__tab {{ $viewType === 'mylist' ? 'item-index__tab--active' : '' }}">マイリスト</a>
        @endauth

        @guest
        {{-- 未ログイン時はログイン画面に遷移 --}}
            <a href="{{ route('login') }}" class="item-index__tab">マイリスト</a>
        @endguest
    </div>

    {{-- 商品カードの一覧 --}}
    <div class="item-list">
        @foreach($items as $item)
            <div class="item-card">
            {{-- 商品画像 + Soldラベル --}}
                <div class="item-card__image-wrapper">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="item-card__image">

                {{-- 購入済みならSoldを表示 --}}
                    @if($item->is_sold)
                        <div class="item-card__sold-label">Sold</div>
                    @endif
                </div>

            {{-- 商品名 --}}
                <p class="item-card__name">{{ $item->name }}</p>
            </div>
        @endforeach
    </div>

</div>
@endsection