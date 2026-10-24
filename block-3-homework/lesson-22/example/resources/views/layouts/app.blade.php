{{-- Դաս 05-ի layout.php-ն՝ Blade-ով — PHP Դաս 19 --}}
<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Գրքեր') — {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('images/favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    {{-- token-ը՝ fetch-ի համար (Laravel-ը կարդում է X-CSRF-TOKEN header-ը) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <a href="#main" class="skip-link">Անցնել բովանդակությանը</a>

    @include('partials.header')

    <main id="main">
        {{-- Դաս 07-ի flash()-ը՝ session('success'), կոմպոնենտով (տնային 3) --}}
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
