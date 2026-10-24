{{-- $cartCount և $navCategories՝ View::composer-ից (AppServiceProvider) — Դաս 19 --}}
<header class="header">
    <input type="checkbox" id="nav" class="nav-toggle">

    <div class="row align-center justify-between">
        <div class="col">
            <a href="{{ route('home') }}" class="logo">{{ config('app.name') }}</a>
        </div>

        <div class="col">
            <label for="nav" class="burger">
                <span></span><span></span><span></span>
                <span class="visually-hidden">Մենյու</span>
            </label>

            <nav aria-label="Գլխավոր մենյու">
                <ul class="menu flex align-center gap-10">
                    <li><a href="{{ route('home') }}" @if (request()->routeIs('home')) aria-current="page" @endif>Գլխավոր</a></li>
                    <li class="has-sub">
                        <a href="{{ route('catalog') }}" @if (request()->routeIs('catalog')) aria-current="page" @endif>Կատալոգ <span aria-hidden="true">▾</span></a>
                        <ul class="submenu">
                            @foreach ($navCategories as $c)
                                <li><a href="{{ route('category', $c) }}">{{ $c->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li><a href="{{ route('contact') }}" @if (request()->routeIs('contact')) aria-current="page" @endif>Կապ</a></li>

                    @guest
                        <li><a href="{{ route('login') }}" @if (request()->routeIs('login')) aria-current="page" @endif>Մուտք</a></li>
                    @endguest

                    @auth
                        @can('manage-products')
                            <li><a href="{{ route('admin.products.index') }}">Ադմին</a></li>
                        @endcan
                        <li><a href="{{ route('account') }}" @if (request()->routeIs('account')) aria-current="page" @endif>Բարև, {{ auth()->user()->name }}</a></li>
                        <li>
                            {{-- Դաս 16-ի ելքը՝ POST-ով --}}
                            <form method="post" action="{{ route('logout') }}">
                                @csrf
                                <button class="btn btn--ghost btn--sm">Ելք</button>
                            </form>
                        </li>
                    @endauth

                    <li>
                        <form method="post" action="{{ route('theme') }}">
                            @csrf
                            <input type="hidden" name="theme" value="{{ $theme === 'dark' ? 'light' : 'dark' }}">
                            <button type="submit" class="theme-toggle" aria-label="{{ $theme === 'dark' ? 'Լուսավոր թեմա' : 'Մուգ թեմա' }}">{{ $theme === 'dark' ? '☀' : '☾' }}</button>
                        </form>
                    </li>
                    <li>
                        <a href="{{ route('cart.index') }}" class="btn btn--primary btn--sm" @if (request()->routeIs('cart.*')) aria-current="page" @endif>
                            Զամբյուղ <span @class(['count', 'is-empty' => $cartCount === 0]) data-cart-count>{{ $cartCount }}</span><span class="visually-hidden"> ապրանք</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
