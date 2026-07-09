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
        <div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
            <div class="w-100" style="max-width: 32rem;">
                <div class="text-center mb-4">
                    <a href="/" class="text-decoration-none text-dark">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle border shadow-sm bg-white" style="width: 72px; height: 72px;">
                            <span class="fw-bold fs-4 text-primary">CC</span>
                        </div>
                    </a>
                    <h1 class="h4 mt-3 mb-0">CareerConnectBD</h1>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
