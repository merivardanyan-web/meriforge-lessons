@extends('layouts.app')

@section('title', 'Կապ — ' . config('app.name'))

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <nav aria-label="Դուք այստեղ եք">
                <ol class="crumbs">
                    <li><a href="{{ route('home') }}">Գլխավոր</a></li>
                    <li aria-current="page">Կապ</li>
                </ol>
            </nav>
            <h1 class="mb-10">Կապ մեզ հետ</h1>
            <p class="text-muted">Գրեք {{ config('app.name') }}-ի թիմին։ Կպատասխանենք մեկ աշխատանքային օրվա ընթացքում։</p>
        </div>
    </div>
</section>

<section id="contact-form">
    <div class="row flex-wrap">
        <div class="col w-60 w-md-100">
            {{-- Ընդհանուր հաղորդագրություն ֆորմայի վերևում — Դաս 6-ի role="alert"-ը --}}
            @if ($errors->any())
                <x-alert type="danger">Ուղղեք {{ $errors->count() }} սխալ և փորձեք կրկին։</x-alert>
            @endif

            <form method="post" action="{{ route('contact.submit') }}" class="form-section" novalidate>
                @csrf
                <div class="form-grid">
                    <x-field name="name" label="Անուն" required :value="old('name')" maxlength="60" autocomplete="name" />
                    <x-field name="email" label="Էլ. հասցե" type="email" required :value="old('email')" autocomplete="email" />

                    <div class="field field--full">
                        <label for="topic" class="field__label">Թեմա <span class="req" aria-hidden="true">*</span></label>
                        <select id="topic" name="topic" @class(['input', 'input--error' => $errors->has('topic')])>
                            <option value="">— ընտրեք —</option>
                            @foreach ($topics as $key => $label)
                                <option value="{{ $key }}" @selected(old('topic') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('topic') <p class="field__error">{{ $message }}</p> @enderror
                    </div>

                    <div class="field field--full">
                        <label for="message" class="field__label">Հաղորդագրություն <span class="req" aria-hidden="true">*</span></label>
                        <textarea id="message" name="message" rows="6" maxlength="2000" @class(['input', 'input--error' => $errors->has('message')])>{{ old('message') }}</textarea>
                        @error('message') <p class="field__error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn--primary mt-20">Ուղարկել</button>
            </form>
        </div>
    </div>
</section>
@endsection
