@extends('layouts.app')

@section('title', '商品購入画面')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/purchase/purchase.css') }}">
@endpush

@section('content')
    <main class="purchase">
        <form action="{{ route('purchase.store', $item->id) }}" method="POST">
            @csrf

            <div class="purchase__container">
            <!-- 左側：商品情報エリア -->
                <section class="purchase__left">
                    <div class="purchase__item-box">
                        <div class="purchase__item-image">
                            <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
                        </div>
                        <div class="purchase__item-info">
                            <h2 class="purchase__item-name">{{ $item->name }}</h2>
                            <p class="purchase__item-price">¥{{ number_format($item->price) }}</p>
                        </div>
                    </div>

                <!-- 支払い方法 -->
                    <div class="purchase__section">
                        <h3 class="purchase__section-title">支払い方法</h3>
                        <select class="purchase__select" name="payment_method" id="payment_method">
                            <option value="convenience">コンビニ払い</option>
                            <option value="credit">カード払い</option>
                        </select>
                    </div>

                <!-- 配送先 -->
                    <div class="purchase__section">
                        <h3 class="purchase__section-title">配送先</h3>
                        <div class="purchase__address">
                            @if(session('shipping_postal'))
                                <p class="purchase__address-text">
                                    〒{{ session('shipping_postal') }}<br>{{ session('shipping_address') }} {{ session('shipping_building') }}
                                </p>
                            @else
                                <p class="purchase__address-text">
                                    〒{{ $user->address->postal_code }}<br>
                                    {{ $user->address->address }} {{ $user->address->building }}
                                </p>
                            @endif
                            <a href={{ route('purchase.address.edit', $item->id) }} class="purchase__address-edit">変更する</a>
                        </div>
                    </div>
                </section>

            <!-- 右側：購入情報エリア -->
                <div class="purchase__right">
                    <div class="purchase__summary">
                        <div class="purchase__summary-row">
                            <span class="purchase__summary-label">商品代金</span>
                            <span class="purchase__summary-value">¥{{ number_format($item->price) }}</span>
                        </div>
                        <div class="purchase__summary-row">
                            <span class="purchase__summary-label">支払い方法</span>
                            <span class="purchase__summary-value" id="summary_payment">コンビニ払い</span> {{-- 仮置き --}}
                        </div>
                                {{-- バリデーションエラー表示（「何も起きない」を防ぐ） --}}
        @if ($errors->any())
          <div class="form-errors">
            @foreach ($errors->all() as $error)
              <div>・{{ $error }}</div>
            @endforeach
          </div>
        @endif
                        
                    </div>
                    <button type="submit" class="purchase__submit-button">購入する</button>
                </div>
            </div>
        </form>
    </main>
{{-- 支払い方法→サマリーを即時反映 --}}
    <script>
        (function() {
    const select = document.getElementById('payment_method');
    const summary = document.getElementById('summary_payment');
    if (!select || !summary) return;
    const update = () => { summary.textContent = select.options[select.selectedIndex].text; };
    update();            // 初期反映
    select.addEventListener('change', update);
    })();
    </script>
@endsection