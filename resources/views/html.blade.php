<html>
<head>
    <title>@yield('title')</title>
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