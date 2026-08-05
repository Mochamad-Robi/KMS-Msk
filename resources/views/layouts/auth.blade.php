<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} — @yield('title')</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/logoapk2.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex">
    @yield('content')
    @stack('scripts')
</body>
</html>