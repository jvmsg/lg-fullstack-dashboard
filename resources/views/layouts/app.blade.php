<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'LG Fullstack Dashboard')</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

        @php
            $hasMixManifest = file_exists(public_path('mix-manifest.json'));
        @endphp

        <link rel="stylesheet" href="{{ $hasMixManifest ? mix('css/app.css') : asset('css/app.css') }}">

        @stack('styles')
    </head>
    <body class="lg-shell-body">
        <div class="lg-shell-overlay" data-sidebar-overlay></div>

        <div class="lg-shell">
            @include('components.layout.sidebar')

            <div class="lg-shell__main">
                @include('components.layout.topbar')

                <main class="lg-content" role="main">
                    @yield('content')
                </main>

                @include('components.layout.footer')
            </div>
        </div>

        <script src="{{ $hasMixManifest ? mix('js/app.js') : asset('js/app.js') }}" defer></script>

        @stack('scripts')
    </body>
</html>
