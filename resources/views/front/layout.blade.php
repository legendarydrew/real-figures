<!doctype>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page-title', config('app.name'))</title>
    <meta name="description" content="@yield('page-description')"/>
    <meta name="paypal-client" content="{{ config('services.paypal.client_id')}}"/>
    @yield('meta')

    {{-- Stylesheets. --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/components.tsx', 'resources/js/tracking.tsx'])
    <link rel="sitemap" href="{{ route('sitemap') }}" type="application/xml"/>
    @yield('vite')

    {{-- Open Graph tags. --}}
    <meta property="og:title" content="@yield('page-title', config('app.name'))"/>
    <meta property="og:type" content="@yield('page-type', 'website')"/>
    <meta property="og:url" content="@yield('page-url', url()->current())"/>
    <meta property="og:description" content="@yield('page-description')"/>
    <meta property="og:locale" content="{{ app()->getLocale() }}"/>
    <meta property="og:site_name" content="SilentMode.tv">
    @hasSection('page-image')
        <meta property="og:image" content="@yield('page-image')"/>
        <meta property="og:image:width" content="1200"/>
        <meta property="og:image:height" content="600"/>
    @endif
    @hasSection('page-image-alt')
        <meta property="og:image:alt" content="@yield('page-image-alt')">
    @endif
    {{-- General recommendation: 2:1 ratio, 1200px wide. (https://www.opengraph.io/open-graph-meta-tags) --}}
    @yield('open-graph')

    {{-- Google Analytics. --}}
    <meta name="analytics-testing" content="{{ config('services.analytics.testMode')}}"/>
    <meta name="analytics-id" content="{{ config('services.analytics.measurement_id')}}"/>
    <meta name="analytics-event-category" content="@yield('event-category')"/>

    {{-- Google AdSense (for those all-important ads). --}}
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>

</head>
<body>
<header class="site-header">
    <div class="site-container">

        <a class="site-header-brand" href="{{ route('home') }}">
            <svg title="CATAWOL Records">
                <use href="{{ asset('logo/catawol-logo.svg') }}" x="-25%" height="100%"></use>
            </svg>
        </a>

        <input type="checkbox" id="nav-open">

        <nav class="site-header-nav">
            <label class="site-header-nav-close" for="nav-open" title="Close menu">
                <i class="fa-solid fa-close"></i>
            </label>

            <a href="{{ route('contest') }}">Contest</a>
            @if(\App\Facades\ContestFacade::shouldShowNews())
                <a href="{{ route('news') }}">News</a>
            @endif
            @if(\App\Facades\ContestFacade::shouldShowActs())
            <a href="{{ route('acts') }}">Acts</a>
            @endif
            <a href="{{ route('rules') }}">Rules</a>
            <a class="donate-link" href="{{ route('donate') }}">Donate!</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('contact') }}">Contact</a>
        </nav>

        <label class="site-header-nav-open" for="nav-open" title="Menu">
            <i class="fa-solid fa-bars"></i>
        </label>
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
