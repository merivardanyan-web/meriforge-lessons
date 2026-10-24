@extends('layouts.app')

@section('title', 'Գրանցում — ' . config('app.name'))

@section('content')
<section class="auth">
    <div class="row">
        <div class="col w-100">
            <div class="auth__card">
                <h1>Գրանցում</h1>
                <p class="text-muted mb-20">Հաշիվը պետք է պատվերների պատմության համար։ Գնել կարելի է նաև առանց դրա։</p>

                {{-- Դաս 16-ի register.php-ն՝ Blade-ով։ Fortify-ը կանչում է CreateNewUser-ը --}}
                <form method="post" action="{{ route('register') }}" novalidate>
                    @csrf

                    <x-field name="name" label="Անուն" required :value="old('name')" autocomplete="name" maxlength="100" />
                    <x-field name="email" label="Էլ. հասցե" type="email" required :value="old('email')" autocomplete="email" />
                    <x-field name="password" label="Գաղտնաբառ (առնվազն 8 նիշ)" type="password" required autocomplete="new-password" />
                    <x-field name="password_confirmation" label="Կրկնիր գաղտնաբառը" type="password" required autocomplete="new-password" />

                    <button class="btn btn--primary w-100">Ստեղծել հաշիվ</button>
                </form>
                <p class="auth__switch">Արդեն ունե՞ս հաշիվ՝ <a href="{{ route('login') }}" class="link">Մուտք</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
