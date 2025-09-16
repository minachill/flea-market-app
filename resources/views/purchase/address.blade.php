@extends('layouts.app')

@section('title', '送付先住所変更画面')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/purchase/address.css') }}">
@endpush

@section('content')
<main class="address">
    <h1 class="address__title">住所の変更</h1>

    <form action="{{ route('purchase.address.update', $item->id) }}" method="POST" class="address__form">
        @csrf

        <!-- 郵便番号 -->
        <div class="address__group">
            <label for="shipping_postal" class="address__label">郵便番号</label>
            <input
                type="text"
                id="shipping_postal"
                name="shipping_postal"
                value="{{ old('shipping_postal') }}"
                class="address__input"
            >
            @error('shipping_postal')
                <p class="address__error">{{ $message }}</p>
            @enderror
        </div>

        <!-- 住所 -->
        <div class="address__group">
            <label for="shipping_address" class="address__label">住所</label>
            <input
                type="text"
                id="shipping_address"
                name="shipping_address"
                value="{{ old('shipping_address') }}"
                class="address__input"
            >
            @error('shipping_address')
                <p class="address__error">{{ $message }}</p>
            @enderror
        </div>

        <!-- 建物名 -->
        <div class="address__group">
            <label for="shipping_building" class="address__label">建物名</label>
            <input
                type="text"
                id="shipping_building"
                name="shipping_building"
                value="{{ old('shipping_building') }}"
                class="address__input"
            >
            @error('shipping_building')
                <p class="address__error">{{ $message }}</p>
            @enderror
        </div>

        <!-- 更新ボタン -->
        <div class="address__actions">
            <button type="submit" class="address__submit">更新する</button>
        </div>
    </form>
</main>
@endsection