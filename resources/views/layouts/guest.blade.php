<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap 5 + Alpine.js via Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-light">
        <div class="d-flex justify-content-center align-items-center min-vh-100">
            <div class="w-100" style="max-width: 440px;">
                <div class="text-center mb-4">
                    <a href="/">
                        <x-application-logo class="text-muted" style="width:5rem;height:5rem;" />
                    </a>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
