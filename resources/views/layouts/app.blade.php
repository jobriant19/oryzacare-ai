<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    <title>OryzaCare AI</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    <script>document.documentElement.classList.add('js')</script>
    <link rel="stylesheet" href="{{ asset('css/padi.css') }}?v=4">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}?v=1">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}?v=1">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}?v=1">
    @stack('styles')
</head>
<body>
@include('partials.header')

<main>
    @yield('content')
</main>

@include('partials.footer')
@include('partials.modal')

<script src="{{ asset('js/padi.js') }}?v=4"></script>
<script src="{{ asset('js/header.js') }}?v=2"></script>
<script src="{{ asset('js/footer.js') }}?v=1"></script>
<script src="{{ asset('js/modal.js') }}?v=1"></script>
@stack('scripts')
</body>
</html>
