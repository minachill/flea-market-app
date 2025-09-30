@extends('layouts.app')

@section('title', 'プロフィール画面')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile/profile.css') }}">
@endpush

@section('content')
<main class="profile">
    <div class="profile__header">
        <div class="profile__avatar">
            @if($user->profile_image_path)
                <img src="{{ asset('storage/' . $user->profile_image_path) }}" alt="プロフィール画像" class="profile__avatar-img">
            @else
                <div class="profile__avatar-placeholder"></div>
            @endif
        </div>
        <div class="profile__info">
            <h1 class="profile__name">{{ $user->name }}</h1>
            <a href="{{ route('profile.update') }}" class="profile__edit-button">プロフィールを編集</a>
        </div>
    </div>

    <div class="profile__tabs">
        <a href="{{ route('profile.index', ['page' => 'sell']) }}"
            class="profile__tab {{ $page === 'sell' ? 'profile__tab--active' : '' }}">
            出品した商品
        </a>
        <a href="{{ route('profile.index', ['page' => 'buy']) }}"
            class="profile__tab {{ $page === 'buy' ? 'profile__tab--active' : '' }}">
            購入した商品
        </a>
    </div>

    <div class="profile__items">
        @if($page === 'sell')
            @foreach($exhibitedItems as $item)
                <div class="profile__item">
                    <div class="profile__item-image">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                    </div>
                    <p class="profile__item-name">{{ $item->name }}</p>
                </div>
            @endforeach
        @elseif($page === 'buy')
            @foreach($purchasedItems as $purchase)
                <div class="profile__item">
                    <div class="profile__item-image">
                        <img src="{{ asset('storage/' . $purchase->item->image) }}" alt="{{ $purchase->item->name}}">
                    </div>
                    <p class="profile__item-name">{{ $purchase->item->name }}</p>
                </div>
            @endforeach
        @endif
    </div>
</main>
@endsection