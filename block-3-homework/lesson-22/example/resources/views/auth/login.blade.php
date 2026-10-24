{{-- Fortify::loginView(fn () => view('auth.login')) — Դաս 16-ի pages/login.php-ն, PHP Դաս 21 --}}
@extends('layouts.app')

@section('title', 'Մուտք')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <h1>Մուտք</h1>
                <p class="lead">Ակումբի անդամ չե՞ս՝ <a href="{{ route('register') }}">միացիր</a>։</p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-50 w-md-100">
            {{-- Fortify-ը սխալը դնում է email դաշտին՝ «Սխալ էլ. հասցե կամ գաղտնաբառ» (lang/hy/auth.php) --}}
            <form method="post" action="{{ route('login') }}" novalidate>
                @csrf
                <div class="form-grid">
                    <x-field name="email" label="Էլ. հասցե" type="email" required autocomplete="username" autofocus />

                    <label for="password">Գաղտնաբառ *</label>
                    <div class="field">
                        {{-- ⭐ value չկա — գաղտնաբառը երբեք չի վերադառնում --}}
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
