@extends('layouts.app')

@section('title', '商品一覧画面')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endpush

@section('content')
<div class="item-index">

    {{-- タブメニュー --}}
    <div class="item-index__tab-menu">
    <a href="{{ route('items.index', array_merge(request()->all(), ['tab' => 'recommend'])) }}"
        class="item-index__tab {{ $viewType === 'recommend' ? 'item-index__tab--active' : '' }}">
        おすすめ
    </a>

    <a href="{{ route('items.index', array_merge(request()->all(), ['tab' => 'mylist'])) }}"
        class="item-index__tab {{ $viewType === 'mylist' ? 'item-index__tab--active' : '' }}">
        マイリスト
    </a>
</div>

    {{-- 商品カードの一覧 --}}
    <div class="item-list">
        @foreach($items as $item)
            <div class="item-card">
            {{-- 商品画像 + Soldラベル --}}
                <div class="item-card__image-wrapper">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="item-card__image">
                    @if($item->purchase()->exists())
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