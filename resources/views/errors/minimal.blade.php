<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cal+Sans&display=swap" rel="stylesheet">

    <style>
        *, :after, :before {
            box-sizing: border-box;
        }

        html, body {
            height: 100dvh;
        }

        body {
            background-color: #FFF;
            color: #35353A;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Cal Sans', 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji';
            margin: 0;
            padding: 1.5rem;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        @media (prefers-color-scheme: dark) {
            body {
                background-color: #35353A;
                color: #FFF;
            }
        }

        .error {
            display: flex;
            align-items: center;
            gap: 1em;
            line-height: 1.1;
            max-width: 80em;
        }

        .logo {
            flex-shrink: 0;
            height: 5rem;
        }

        .error-message {
            flex-grow: 1;
        }

        .error-message-code {
            font-size: 2.5rem;
            font-weight: normal;
            margin: 0;
        }

        .error-message-text {
            font-size: 1.2rem;
            font-weight: normal;
        }
    </style>
</head>
<body>
<div class="error">
    <img class="logo" src="{{ asset('img/catawol-icon.svg') }}" alt="">

    <div class="error-message">
        <h1 class="error-message-code">
            @yield('code')
        </h1>

        <div class="error-message-text">
            @yield('message')
        </div>
    </div>
</div>
</body>
</html>
