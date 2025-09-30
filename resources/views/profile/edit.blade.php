@extends('layouts.app')

@section('title', 'プロフィール編集画面')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile/edit.css') }}">
@endpush

@section('content')
<main class="profile-edit">
    <h1 class="profile-edit__title">プロフィール設定</h1>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-edit__form">
        @csrf

        <!-- プロフィール画像 -->
    <div class="profile-edit__avatar">
        <div class="profile-edit__avatar-circle">
            @if ($user->profile_image_path)
                <img id="image-preview"
                    src="{{asset('storage/' . $user->profile_image_path) }}"
                    alt="プレビュー"
                    class="profile-edit__avatar-img ">
            @else
                <div class="profile-edit__avatar-placeholder"></div>
            @endif
        </div>

        <div class="profile-edit__avatar-actions">
            <label for="profile_image" class="profile-edit__image-button">
                画像を選択する
            </label>
            <input
                type="file"
                id="profile_image"
                name="profile_image"
                class="profile-edit__file-input"
            >

            <p class="profile-edit__error-image">
                @error('profile_image') {{ $message }} @enderror
            </p>
        </div>
    </div>

        <!-- ユーザー名 -->
        <div class="profile-edit__group">
            <label for="name" class="profile-edit__label">ユーザー名</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $user->name) }}"
                class="profile-edit__input"
                placeholder="既存の値が入力されている"
            >
            <p class="profile-edit__error">
                @error('name') {{ $message }} @enderror
            </p>
        </div>

        <!-- 郵便番号 -->
        <div class="profile-edit__group">
            <label for="postal_code" class="profile-edit__label">郵便番号</label>
            <input
                type="text"
                id="postal_code"
                name="postal_code"
                value="{{ old('postal_code', optional($user->address)->postal_code) }}"
                class="profile-edit__input"
                placeholder="既存の値が入力されている"
            >
            <p class="profile-edit__error">
                @error('postal_code') {{ $message }} @enderror
            </p>
        </div>

        <!-- 住所 -->
        <div class="profile-edit__group">
            <label for="address" class="profile-edit__label">住所</label>
            <input
                type="text"
                id="address"
                name="address"
                value="{{ old('address', optional($user->address)->address) }}"
                class="profile-edit__input"
                placeholder="既存の値が入力されている"
            >
            <p class="profile-edit__error">
                @error('address') {{ $message }} @enderror
            </p>
        </div>

        <!-- 建物名 -->
        <div class="profile-edit__group">
            <label for="building" class="profile-edit__label">建物名</label>
            <input
                type="text"
                id="building"
                name="building"
                value="{{ old('building', optional($user->address)->building) }}"
                class="profile-edit__input"
                placeholder="既存の値が入力されている"
            >
            <p class="profile-edit__error">
                @error('building') {{ $message }} @enderror
            </p>
        </div>

        <!-- 更新ボタン -->
        <div class="profile-edit__actions">
            <button type="submit" class="profile-edit__submit">更新する</button>
        </div>
    </form>
</main>
@endsection