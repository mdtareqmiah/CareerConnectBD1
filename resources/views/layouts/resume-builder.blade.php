<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="auth-user-id" content="{{ auth()->id() }}">

        <title>@yield('title', 'Resume Builder Studio · CareerConnectBD')</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        <style>
            html, body { margin: 0; padding: 0; }
            body.rb-page {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                background: #f8fafc;
                color: #0f172a;
                min-height: 100vh;
            }
            .rb-shell {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }
            .rb-main {
                flex: 1 0 auto;
                width: 100%;
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @yield('styles')
    </head>
    <body class="rb-page text-dark">
        <div class="rb-shell">
            <div id="rb-nav-root"></div>

            <main id="main-content" class="rb-main">
                @yield('content')
            </main>

            <div id="rb-footer-root"></div>
        </div>
        @yield('scripts')
    </body>
</html>
