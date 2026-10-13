<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#7f0d0d">
    <title>{{ config('app.name') }} &mdash; @yield('title')</title>

    {{-- PWA --}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/icon-192.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/icon-192.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="MSK Pintar">
    <meta name="mobile-web-app-capable" content="yes">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex">
    @yield('content')
    @stack('scripts')

    {{-- Service Worker: syarat wajib supaya Chrome menawarkan install --}}
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/sw.js')
                .then(function (reg) { console.log('SW registered:', reg.scope); })
                .catch(function (err) { console.log('SW failed:', err); });
        });
    }
    </script>
</body>
</html>