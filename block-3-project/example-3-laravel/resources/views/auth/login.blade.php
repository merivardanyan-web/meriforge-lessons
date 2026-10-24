@extends('layouts.app')

@section('title', 'Մուտք — ' . config('app.name'))

@section('content')
<section class="auth">
    <div class="row">
        <div class="col w-100">
            <div class="auth__card">
                <h1>Մուտք</h1>
                <p class="text-muted mb-20">Մուտք գործիր՝ պատվերներդ տեսնելու համար։</p>

                {{-- Fortify-ը սխալը դնում է email դաշտին՝ «Սխալ էլ. հասցե կամ գաղտնաբառ» (lang/hy/auth.php) --}}
                <form method="post" action="{{ route('login') }}" novalidate>
                    @csrf

                    <x-field name="email" label="Էլ. հասցե" type="email" required :value="old('email')" autocomplete="username" autofocus />

                    <div class="field">
                        <label for="password" class="field__label">Գաղտնաբառ <span class="req" aria-hidden="true">*</span></label>
                        {{-- ⭐ value չկա — գաղտնաբառը երբեք չի վերադառնում --}}
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="input">
                    </div>

                    <div class="auth__row">
                        <label class="check">
                            <input type="checkbox" name="remember" value="1">
                            <span>Հիշել ինձ</span>
                        </label>
                    </div>

                    <button class="btn btn--primary w-100">Մուտք գործել</button>
                </form>
                <p class="auth__switch">Դեռ հաշիվ չունե՞ս՝ <a href="{{ route('register') }}" class="link">Գրանցվիր</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
