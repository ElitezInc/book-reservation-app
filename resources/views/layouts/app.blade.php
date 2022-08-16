<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/darkmode.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @include('layouts.navbar')

    @auth()
        @include('layouts.sidebar')

        <main class="mt-3">
            @yield('content')
        </main>
    @endauth()

    @guest()
        <div class="mt-5 pt-3">
            @yield('content')
        </div>
    @endguest

    @stack('scripts')
</body>
</html>
