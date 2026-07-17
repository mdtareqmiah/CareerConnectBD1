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
    <body class="auth-body">
        @include('layouts.navigation')

        <div class="auth-shell min-vh-100 d-flex align-items-center justify-content-center py-5">
            <div class="w-100" style="max-width: 38rem;">
                <div class="text-center mb-4">
                    <a href="/" class="text-decoration-none text-dark">
                        <div class="brand-mark d-inline-flex align-items-center justify-content-center rounded-circle text-white mb-3">CC</div>
                    </a>
                    <h1 class="h2 fw-semibold mb-2">{{ config('app.name', 'CareerConnectBD') }}</h1>
                    <p class="text-muted mb-0">Modern recruitment for ambitious teams and talent.</p>
                </div>

                <div class="card auth-card border-0 shadow-soft">
                    <div class="card-body p-4 p-lg-5">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
