@extends('layouts.app')

@section('title', '商品出品画面')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/exhibition/create.css') }}">
@endpush

@section('content')
<div class="sell">
    <h1 class="sell__title">商品の出品</h1>

    <form class="sell-form" action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 画像アップロード -->
        <div class="sell-form__section">
            <label class="sell-form__label">商品画像</label>
            <div class="sell-form__image-box">
                <input type="file" name="image" id="image" class="sell-form__image-input">
                <label for="image" class="sell-form__image-button">画像を選択する</label>
            </div>
            @error('image') <p class="sell-form__error">{{ $message }}</p> @enderror
        </div>

        <!-- 商品の詳細 -->
        <div class="sell-form__section">
            <h2 class="sell-form__subtitle">商品の詳細</h2>

            <!-- カテゴリ -->
            <div class="sell-form__field">
                <label class="sell-form__label">カテゴリー</label>
                <div class="sell-form__categories">
                    @foreach($categories as $category)
                        <label class="sell-form__category">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}">
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('categories') <p class="sell-form__error">{{ $message }}</p> @enderror
            </div>

            <!-- 商品の状態 -->
            <div class="sell-form__field">
                <label class="sell-form__label">商品の状態</label>
                <div class="sell-form__select-wrapper">
                    <select name="condition" class="sell-form__select">
                        <option value="" selected hidden>選択してください</option>
                        <option value="1">良好</option>
                        <option value="2">目立った傷や汚れなし</option>
                        <option value="3">やや傷や汚れあり</option>
                        <option value="4">状態が悪い</option>
                    </select>
                </div>
                @error('condition') <p class="sell-form__error">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- 商品名と説明 -->
        <div class="sell-form__section">
            <h2 class="sell-form__subtitle">商品名と説明</h2>

            <div class="sell-form__field">
                <label class="sell-form__label">商品名</label>
                <input type="text" name="name" class="sell-form__input" value="{{ old('name') }}">
                @error('name') <p class="sell-form__error">{{ $message }}</p> @enderror
            </div>

            <div class="sell-form__field">
                <label class="sell-form__label">ブランド名</label>
                <input type="text" name="brand_name" class="sell-form__input" value="{{ old('brand_name') }}">
            </div>

            <div class="sell-form__field">
                <label class="sell-form__label">商品の説明</label>
                <textarea name="description" class="sell-form__textarea">{{ old('description') }}</textarea>
                @error('description') <p class="sell-form__error">{{ $message }}</p> @enderror
            </div>

            <div class="sell-form__field">
                <label class="sell-form__label">販売価格</label>
                <div class="sell-form__price">
                    <span class="sell-form__price-symbol">¥</span>
                    <input type="text" name="price" class="sell-form__input" value="{{ old('price') }}">
                </div>
                @error('price') <p class="sell-form__error">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- 出品ボタン -->
        <div class="sell-form__actions">
            <button type="submit" class="sell-form__submit">出品する</button>
        </div>
    </form>
</div>
@endsection