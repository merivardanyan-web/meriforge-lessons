{{-- Մենյուն՝ ըստ դերի։ @can-ը միայն թաքցնում է հղումը, պաշտպանությունը՝ ուղիներում և կոնտրոլերում --}}
<header class="header">
    <input type="checkbox" id="nav-toggle" class="nav-toggle" aria-label="Մենյու">
    <div class="row align-center justify-between">
        <div class="col">
            <a href="{{ route('home') }}" class="logo">{{ config('app.name') }}</a>
        </div>
        <label for="nav-toggle" class="burger">
            <span></span><span></span><span></span>
            <span class="visually-hidden">Մենյու</span>
        </label>
        <nav class="col" aria-label="Գլխավոր մենյու">
            <ul class="menu">
                <li><a href="{{ route('home') }}" @if (request()->routeIs('home')) aria-current="page" @endif>Գլխավոր</a></li>
                {{-- Ավելացրու քո էջերը․ <li><a href="{{ route('...') }}" @if (request()->routeIs('...')) aria-current="page" @endif>...</a></li> --}}
                @auth
                    <li class="hello">{{ auth()->user()->name }}</li>
                    <li>
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn--ghost btn--sm">Ելք</button>
                        </form>
                    </li>
                @endauth
                @guest
                    @if (Route::has('login'))
                        <li><a href="{{ route('login') }}" @if (request()->routeIs('login')) aria-current="page" @endif>Մուտք</a></li>
                    @endif
                @endguest
            </ul>
        </nav>
    </div>
</header>
