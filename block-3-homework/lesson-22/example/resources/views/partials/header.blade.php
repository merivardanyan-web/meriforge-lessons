{{-- $listCount-ը՝ View::composer-ից (AppServiceProvider) — PHP Դաս 19, 21 --}}
<header class="header">
    <input type="checkbox" id="nav-toggle" class="nav-toggle">
    <div class="row align-center justify-between">
        <div class="col">
            <a href="{{ route('home') }}" class="logo">{{ config('app.name') }}</a>
        </div>
        <div class="col">
            <label for="nav-toggle" class="burger">
                <span></span><span></span><span></span>
                <span class="visually-hidden">Մենյու</span>
            </label>
            <nav aria-label="Գլխավոր մենյու">
                <ul class="menu flex align-center gap-10">
                    {{-- request()->routeIs()՝ Դաս 15-ի isCurrent()-ի փոխարեն --}}
                    <li><a href="{{ route('home') }}" @if (request()->routeIs('home', 'genre')) aria-current="page" @endif>Գրքեր</a></li>
                    <li>
                        <a href="{{ route('list.index') }}" @if (request()->routeIs('list.*')) aria-current="page" @endif>
                            Կարդալու ցուցակ <span @class(['pill', 'hidden' => $listCount === 0]) data-list-count>{{ $listCount }}</span>
                        </a>
                    </li>
                    <li><a href="{{ route('suggest') }}" class="btn btn--primary btn--sm">Առաջարկիր գիրք</a></li>
                    {{-- @auth / @guest — Դաս 16-ի auth()->check()-ը --}}
                    @auth
                        <li class="hello">Բարև, {{ auth()->user()->name }}</li>
                        <li>
                            {{-- Ելքը՝ POST + @csrf (Դաս 16)․ GET հղումը <img src="/logout">-ով կհաներ ցանկացածին --}}
                            <form method="post" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn--ghost btn--sm">Ելք</button>
                            </form>
                        </li>
                    @endauth
                    @guest
                        <li><a href="{{ route('login') }}" @if (request()->routeIs('login')) aria-current="page" @endif>Մուտք</a></li>
                    @endguest
                </ul>
            </nav>
        </div>
    </div>
</header>
