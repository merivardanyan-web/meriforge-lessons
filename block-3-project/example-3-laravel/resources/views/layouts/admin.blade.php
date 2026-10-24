<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') · Ադմին · {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body class="admin">

<a href="#main" class="skip-link">Անցնել բովանդակությանը</a>

<header class="header">
    <div class="row align-center justify-between flex-wrap">
        <div class="col">
            <a href="{{ route('admin.products.index') }}" class="logo">{{ config('app.name') }} · ադմին</a>
        </div>
        <div class="col">
            <nav aria-label="Ադմինի մենյու">
                <ul class="menu flex align-center flex-wrap gap-10">
                    <li><a href="{{ route('admin.products.index') }}" @if (request()->routeIs('admin.products.index')) aria-current="page" @endif>Ապրանքներ</a></li>
                    @can('delete-products')
                        <li><a href="{{ route('admin.products.trashed') }}" @if (request()->routeIs('admin.products.trashed')) aria-current="page" @endif>Ջնջվածներ</a></li>
                    @endcan
                    <li><a href="{{ route('admin.orders.index') }}" @if (request()->routeIs('admin.orders.*')) aria-current="page" @endif>Պատվերներ</a></li>
                    <li><a href="{{ route('admin.products.create') }}" class="btn btn--primary btn--sm">+ Նոր ապրանք</a></li>
                    <li><a href="{{ route('home') }}">Կայք →</a></li>
                    <li>
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn--ghost btn--sm">Ելք ({{ auth()->user()->name }}, {{ auth()->user()->role }})</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main id="main">
    <div class="row"><div class="col w-100">
        @if (session('success'))
            <x-alert class="mt-20">{{ session('success') }}</x-alert>
        @endif
    </div></div>

    @yield('content')
</main>

</body>
</html>
