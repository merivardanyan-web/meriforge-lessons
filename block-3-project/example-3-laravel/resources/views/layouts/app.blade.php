@php
    // Թեման՝ cookie-ից, սպիտակ ցուցակով — Դաս 7
    $theme = in_array(request()->cookie('theme'), ['light', 'dark'], true) ? request()->cookie('theme') : null;
@endphp
<!DOCTYPE html>
<html lang="hy" @if ($theme) data-theme="{{ $theme }}" @endif>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">     {{-- Դաս 17-ի meta-ն --}}

    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="5000+ ապրանք, անվճար առաքում Երևանում, 2 տարվա երաշխիք">

    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Armenian:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>

<a href="#main" class="skip-link">Անցնել բովանդակությանը</a>

@include('partials.header', ['theme' => $theme])

<main id="main">
    @foreach (['success' => 'success', 'status' => 'success', 'error' => 'danger'] as $key => $type)
        @if (session($key))
            <div class="row"><div class="col w-100">
                <x-alert :type="$type" class="mt-20">{{ session($key) }}</x-alert>
            </div></div>
        @endif
    @endforeach

    @yield('content')       {{-- ⭐ Դաս 5-ի <?= $content ?>-ն --}}
</main>

@include('partials.footer')

<p id="cart-status" class="visually-hidden" role="status"></p>

<script src="{{ asset('js/cart.js') }}" defer></script>
@stack('scripts')
</body>
</html>
