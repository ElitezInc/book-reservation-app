<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="{{ asset('js/darkmode.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Books</title>
</head>
<body>
<!-- React root DOM -->
<div id="app">
</div>
<!-- React JS -->
<script src="{{ mix('js/index.js') }}" defer></script>
</body>
</html>
