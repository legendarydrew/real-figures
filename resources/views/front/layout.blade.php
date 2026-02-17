<!doctype>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page-title', config('app.name', 'Laravel'))</title>
    <meta name="description" content="@yield('page-description')"/>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">
    @viteReactRefresh
    @vite(['resources/css/app.css'])
    <link rel="sitemap" href="{{ route('sitemap') }}" type="application/xml"/>
</head>
<body>
<header class="site-header">
    <div class="site-container">

        <a class="site-header-brand" href="{{ route('home') }}">
            <img src="{{ asset('logo/catawol-logo.svg') }}" alt="CATAWOL Records"/>
        </a>

        <nav class="site-header-nav">
            <button type="button" class="site-header-nav-close" title="Close menu">
                &times;
            </button>

            <a href="{{ route('contest') }}">Contest</a>
            <a href="{{ route('news') }}">News</a>
            <a href="{{ route('acts') }}">Acts</a>
            <a href="{{ route('rules') }}">Rules</a>
            <a class="donate-link" href="{{ route('donate') }}">Donate!</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('contact') }}">Contact</a>
        </nav>

        <button type="button" class="site-header-nav-open" title="Menu">
            open
        </button>
    </div>
</header>

<main class="site-main">
    @yield('content')
</main>

<footer class="site-footer">
    <div class="site-container">
        Copyright &copy; Drew Maughan (SilentMode), all rights reserved.
    </div>
</footer>
</body>
</html>
