<header class="header">
    <input type="checkbox" id="nav-toggle" class="nav-toggle" aria-label="Մենյու">
    <div class="row align-center justify-between">
        <div class="col">
            <a href="{{ route('home') }}" class="logo">
                <svg viewBox="0 0 28 28" aria-hidden="true">
                    <rect x="8" y="2" width="12" height="24" rx="6" fill="#faf6f0"/>
                    <path d="M8 9 20 3v5L8 14zm0 9 12-6v5L8 23z" fill="#a4572a"/>
                </svg>
                {{ config('app.name') }}
            </a>
        </div>
        <label for="nav-toggle" class="burger">
            <span></span><span></span><span></span>
            <span class="visually-hidden">Մենյու</span>
        </label>
        <nav class="col" aria-label="Գլխավոր մենյու">
            <ul class="menu">
                @cannot('staff-area')
                    <li><a href="{{ route('home') }}" @if (request()->routeIs('home', 'services.show')) aria-current="page" @endif>Ծառայություններ</a></li>
                @endcannot
                @auth
                    @if (auth()->user()->isClient())
                        <li><a href="{{ route('bookings.mine') }}" @if (request()->routeIs('bookings.*')) aria-current="page" @endif>Իմ ամրագրումները</a></li>
                    @endif
                    @can('staff-area')
                        <li><a href="{{ route('staff.index') }}" @if (request()->routeIs('staff.index')) aria-current="page" @endif>Օրվա ցուցակ</a></li>
                        <li><a href="{{ route('staff.slots') }}" @if (request()->routeIs('staff.slots')) aria-current="page" @endif>Ժամեր</a></li>
                    @endcan
                    @can('manage-services')
                        <li><a href="{{ route('admin.bookings.index') }}" @if (request()->routeIs('admin.bookings.*')) aria-current="page" @endif>Ամրագրումներ</a></li>
                        <li><a href="{{ route('admin.services.index') }}" @if (request()->routeIs('admin.services.*')) aria-current="page" @endif>Ծառայություններ</a></li>
                    @endcan
                    <li class="hello">{{ auth()->user()->name }}</li>
                    <li>
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn--ghost btn--sm">Ելք</button>
                        </form>
                    </li>
                @endauth
                @guest
                    <li><a href="{{ route('login') }}" @if (request()->routeIs('login')) aria-current="page" @endif>Մուտք</a></li>
                    <li><a href="{{ route('register') }}" class="btn btn--primary btn--sm">Գրանցվել</a></li>
                @endguest
            </ul>
        </nav>
    </div>
</header>
