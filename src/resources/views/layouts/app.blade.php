<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'coachtechフリマ')</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/layouts/common.css') }}">

    @stack('styles')
</head>
<body>
    <header class="site-header">
        <div class="logo">
            <img src="{{ asset('img/logo.svg') }}" alt="COACHTECH">
        </div>
    </header>

    <main class="site-main">
        @yield('content')
    </main>
</body>
</html>