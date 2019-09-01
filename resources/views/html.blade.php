<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <script src="{{ mix('/js/app.js') }}"></script>
    <link href="{{ mix('/styles/app.css') }}" rel="stylesheet">
</head>
<body>
    <nav>
        @foreach ($ancestors as $ancestor)
            &raquo; <a href="{{ $ancestor['contents']['url'] ?? '' }}">{{ $ancestor['contents']['title'] ??'' }}</a>
        @endforeach
    </nav>
    <main>
        @yield('main')
    </main>
</body>
</html>