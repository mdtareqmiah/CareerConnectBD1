<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-light">
        <div class="min-vh-100 d-flex flex-column">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white border-bottom shadow-sm">
                    <div class="container py-4">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-grow-1 py-4">
                <div class="container">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="alert alert-info" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @hasSection('content')
                        @yield('content')
                    @else
                        {{ $slot }}
                    @endif
                </div>
            </main>
        </div>
    </body>
</html>
