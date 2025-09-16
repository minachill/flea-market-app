@extends('layouts.app')

@section('title', 'プロフィール画面')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/mypage/mypage.css') }}">
@endpush

@section('content')
<main class="profile">
    <div class="profile__header">
        <div class="profile__avatar">
            @if($user->image)
                <img src="{{ asset('storage/' . $user->image) }}" alt="プロフィール画像" class="profile__avatar-img">
            @else
                <div class="profile__avatar-placeholder"></div>
            @endif
        </div>
        <div class="profile__info">
            <h1 class="profile__name">{{ $user->name }}</h1>
            <a href="{{ route('mypage.update') }}" class="profile__edit-button">プロフィールを編集</a>
        </div>
    </div>

    <div class="profile__tabs">
        <a href="{{ route('mypage.exhibited') }}" class="profile__tab profile__tab--active">出品した商品</a>
        <a href="{{ route('mypage.purchased') }}" class="profile__tab">購入した商品</a>
    </div>

    <div class="profile__items">
        @foreach($items as $item)
            <div class="profile__item">
                <div class="profile__item-image">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                </div>
                <p class="profile__item-name">{{ $item->name }}</p>
            </div>
        @endforeach
    </div>
</main>
@endsection