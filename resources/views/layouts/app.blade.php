<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'coachtechフリマ')</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/layouts/common.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz@0,14..32;1,14..32&display=swap" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <header class="site-header">
        <div class="site-header__logo">
            <img src="{{ asset('img/logo.svg') }}" alt="COACHTECH">
        </div>

            {{-- 検索フォーム --}}
        <form action="{{ route('items.index') }}" method="GET" class="site-header__search-form">
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
        {{-- <button type="submit">検索</button> --}}
        </form>

    {{-- ボタン類 --}}
        <div class="site-header__actions">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="site-header__btn-nav">ログアウト</button>
                </form>
                <a class="site-header__btn-nav" href="/mypage" >マイページ</a>
                <a class="site-header__btn-sell" href="/sell" >出品</a>
            @endauth

            @guest
                <a class="site-header__btn-nav" href="{{ route('login') }}" >ログイン</a>
                <a class="site-header__btn-nav" href="{{ route('register') }}" >会員登録</a>
                <a class="site-header__btn-sell" href="{{ route('login') }}" >出品</a>
            @endguest
        </div>
    </header>

    <main class="site-main">
        @yield('content')
    </main>
</body>
</html>