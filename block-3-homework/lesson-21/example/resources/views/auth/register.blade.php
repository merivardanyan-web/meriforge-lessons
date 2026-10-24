{{-- Fortify::registerView(...) — Դաս 16-ի pages/register.php-ն Blade-ով, PHP Դաս 21
     Ստուգումը՝ app/Actions/Fortify/CreateNewUser.php, role-ը չի ընդունվում (դասարանական, փորձ 5) --}}
@extends('layouts.app')

@section('title', 'Միանալ ակումբին')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <h1>Միանալ ակումբին</h1>
                <p class="lead">Ամեն ամիս՝ մեկ գիրք և մեկ հանդիպում։ Արդեն անդա՞մ ես՝ <a href="{{ route('login') }}">մուտք</a>։</p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-60 w-md-100">
            @if ($errors->any())
                <div class="alert alert--danger" role="alert">Ուղղիր {{ $errors->count() }} սխալ և փորձիր կրկին։</div>
            @endif

            <form method="post" action="{{ route('register') }}" novalidate>
                @csrf
                <div class="form-grid">
                    <x-field name="name" label="Անուն" required maxlength="60" autocomplete="name" />
                    <x-field name="email" label="Էլ. հասցե" type="email" required autocomplete="email" />

                    <label for="password">Գաղտնաբառ *</label>
                    <div @class(['field', 'has-error' => $errors->has('password')])>
                        <input id="password" name="password" type="password" autocomplete="new-password" @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                        <small class="meta">Առնվազն 8 նիշ</small>
                        @error('password')<small class="field__error" id="password-error">{{ $message }}</small>@enderror
                    </div>

                    <label for="password_confirmation">Կրկնիր *</label>
                    <div class="field">
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                    </div>
                </div>
                <button type="submit" class="btn btn--primary mt-20">Միանալ</button>
            </form>
        </div>
    </div>
</section>
@endsection
