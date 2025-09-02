<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'coachtechフリマ')</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/layouts/auth.css') }}">
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
        @yield('header')
    </header>

    <main class="site-main">
        @yield('content')
    </main>
</body>
</html>