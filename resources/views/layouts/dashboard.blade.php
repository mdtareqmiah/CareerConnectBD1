<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="auth-user-id" content="{{ auth()->id() }}">

        <title>{{ config('app.name', 'CareerConnectBD') }} · Dashboard</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @yield('styles')
    </head>
    <body class="js-dashboard-page text-dark">
        <div class="app-shell min-vh-100 d-flex flex-column">
            <div id="dashboard-nav-root"></div>

            <main id="main-content" class="flex-grow-1">
                @yield('content')
            </main>

            <div id="dashboard-footer-root"></div>
        </div>
        @yield('scripts')
    </body>
</html>
