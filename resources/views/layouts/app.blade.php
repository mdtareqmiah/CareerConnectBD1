<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CareerConnectBD') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="text-dark">
        <a class="visually-hidden-focusable" href="#main-content">Skip to main content</a>

        <div class="app-shell min-vh-100 d-flex flex-column">
            @include('layouts.navigation')

            @isset($header)
                <header class="app-surface border-bottom">
                    <div class="container-xl py-4 py-lg-5">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main id="main-content" class="flex-grow-1 py-5">
                <div class="container-xl">
                    @include('components.flash-messages')

                    @hasSection('content')
                        @yield('content')
                    @else
                        {{ $slot }}
                    @endif
                </div>
            </main>

            @include('components.newsletter')

            @include('components.footer')
        </div>
    </body>
</html>
