@extends('layouts.app')

@section('title', 'Մուտք')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <h1>Մուտք</h1>
            <p class="lead">Հաշիվ չունե՞ս՝ <a href="{{ route('register') }}">գրանցվիր</a>։</p>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-100">
            {{-- Fortify-ը սխալը դնում է email դաշտին՝ lang/hy/auth.php-ի «failed»-ը --}}
            <form method="post" action="{{ route('login') }}" class="box" novalidate>
                @csrf
                <div class="form-grid">
                    <x-field name="email" label="Էլ. հասցե" type="email" required autocomplete="username" autofocus />

                    <label for="password">Գաղտնաբառ *</label>
                    <div class="field">
                        <input id="password" name="password" type="password" autocomplete="current-password">
                    </div>

                    <label class="check">
                        <input type="checkbox" name="remember" value="1"> Հիշել ինձ
                    </label>
                </div>
                <button type="submit" class="btn btn--primary mt-20">Մուտք</button>
            </form>
        </div>
    </div>
</section>
@endsection
