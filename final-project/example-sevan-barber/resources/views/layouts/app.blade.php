<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Վարսավիրանոց Սևանում') — {{ config('app.name') }}</title>
    <meta name="description" content="@yield('description', 'Ամրագրիր սանրվածք կամ սափրում առցանց՝ ազատ ժամերից։')">
    <link rel="icon" href="{{ asset('images/favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Armenian:wght@400;500;700&family=Noto+Serif+Armenian:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    {{-- fetch-ը կարդում է token-ը այստեղից և ուղարկում X-CSRF-TOKEN header-ով --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <a href="#main" class="skip-link">Անցնել բովանդակությանը</a>

    @include('partials.header')

    <main id="main">
        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if (session('error'))
            <x-alert type="danger">{{ session('error') }}</x-alert>
        @endif

        @yield('content')
    </main>

    @include('partials.footer')

    <div class="toast" role="status" aria-live="polite" data-toast></div>

    @stack('scripts')
</body>
</html>
