{{-- «Առաջարկիր գիրք» — Դաս 06-ի pages/suggest.php-ն Blade-ով, PHP Դաս 21, տնային 2
     Համեմատիր․ $old['name'] ?? '' → old('name'), isset($errors['name']) → @error('name') --}}
@extends('layouts.app')

@section('title', 'Առաջարկիր գիրք')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <h1>Առաջարկիր գիրք</h1>
                <p class="lead">Ի՞նչ արժե կարդալ ակումբում հաջորդ ամիս։ * նշվածները պարտադիր են։</p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-70 w-md-100">
            @if ($errors->any())
                <div class="alert alert--danger" role="alert">Ուղղիր {{ $errors->count() }} սխալ և փորձիր կրկին։</div>
            @endif

            <form method="post" action="{{ route('suggest.store') }}" novalidate>
                @csrf
                <fieldset>
                    <legend>Դու</legend>
                    <div class="form-grid">
                        <x-field name="name" label="Անուն" required maxlength="60" autocomplete="name" />
                        <x-field name="email" label="Էլ. հասցե" type="email" required autocomplete="email" />
                        <x-field name="phone" label="Հեռախոս" type="tel" placeholder="+374 91 000000" autocomplete="tel" />
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Գիրքը</legend>
                    <div class="form-grid">
                        <x-field name="title" label="Վերնագիր" required maxlength="120" />
                        <x-field name="author" label="Հեղինակ" maxlength="80" />

                        <label for="genre">Ժանր *</label>
                        <div @class(['field', 'has-error' => $errors->has('genre')])>
                            <select id="genre" name="genre">
                                <option value="">— ընտրիր —</option>
                                @foreach ($genres as $genre)
                                    <option value="{{ $genre }}" @selected(old('genre') === $genre)>{{ $genre }}</option>
                                @endforeach
                            </select>
                            @error('genre')<small class="field__error">{{ $message }}</small>@enderror
                        </div>

                        <label for="reason">Ինչու արժե կարդալ *</label>
                        <div @class(['field', 'has-error' => $errors->has('reason')])>
                            <textarea id="reason" name="reason" rows="5" maxlength="1000">{{ old('reason') }}</textarea>
                            @error('reason')<small class="field__error">{{ $message }}</small>@enderror
                        </div>

                        <label class="check">
                            <input type="checkbox" name="agree" value="1" @checked(old('agree'))>
                            Համաձայն եմ, որ Անին կապ հաստատի ինձ հետ
                        </label>
                        @error('agree')<small class="field__error check">{{ $message }}</small>@enderror
                    </div>
                </fieldset>

                <button type="submit" class="btn btn--primary mt-20">Ուղարկել</button>
            </form>
        </div>
    </div>
</section>
@endsection
