{{-- Գրանցվողը միշտ client է (CreateNewUser)․ role դաշտ չկա և չի կարող լինել --}}
@extends('layouts.app')

@section('title', 'Գրանցում')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <h1>Գրանցում</h1>
            <p class="lead">Հաշիվը պետք է ամրագրելու և չեղարկելու համար։ Հեռախոսը տեսնում է միայն վարսավիրը։</p>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-100">
            <form method="post" action="{{ route('register') }}" class="box" novalidate>
                @csrf
                <div class="form-grid">
                    <x-field name="name" label="Անուն" required autocomplete="name" maxlength="60" />
                    <x-field name="email" label="Էլ. հասցե" type="email" required autocomplete="email" maxlength="190" />
                    <x-field name="phone" label="Հեռախոս" type="tel" required autocomplete="tel" placeholder="+374 91 123456" />

                    <label for="password">Գաղտնաբառ *</label>
                    <div @class(['field', 'has-error' => $errors->has('password')])>
                        <input id="password" name="password" type="password" autocomplete="new-password"
                               @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                        <small class="meta">Առնվազն 8 նիշ</small>
                        @error('password')<small class="field__error" id="password-error">{{ $message }}</small>@enderror
                    </div>

                    <label for="password_confirmation">Կրկնիր գաղտնաբառը *</label>
                    <div class="field">
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                    </div>
                </div>
                <button type="submit" class="btn btn--primary mt-20">Գրանցվել</button>
            </form>
        </div>
    </div>
</section>
@endsection
