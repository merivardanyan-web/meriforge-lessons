@extends('layouts.app')

@section('title', 'Պատվերի ձևակերպում — ' . config('app.name'))

@php
    $R = \App\Http\Requests\CheckoutRequest::class;
@endphp

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <nav aria-label="Դուք այստեղ եք">
                <ol class="crumbs">
                    <li><a href="{{ route('home') }}">Գլխավոր</a></li>
                    <li><a href="{{ route('cart.index') }}">Զամբյուղ</a></li>
                    <li aria-current="page">Պատվերի ձևակերպում</li>
                </ol>
            </nav>
            <h1 class="mb-10">Պատվերի ձևակերպում</h1>
            <p class="text-muted">Մնաց մեկ քայլ։ <span class="req" aria-hidden="true">*</span> նշված դաշտերը պարտադիր են։</p>
        </div>
    </div>
</section>

<section id="checkout">
    <div class="row flex-wrap">
        <div class="col w-60 w-md-100">

            @if ($errors->any())
                <x-alert type="danger">{{ $errors->first('cart') ?: 'Ուղղեք ' . $errors->count() . ' սխալ և փորձեք կրկին։' }}</x-alert>
            @endif

            <form action="{{ route('checkout.store') }}" method="post" novalidate>
                @csrf

                <div class="form-section">
                    <fieldset class="fieldset">
                        <legend>1. Կոնտակտային տվյալներ</legend>
                        <div class="form-grid">
                            <x-field name="customer_name" label="Անուն, ազգանուն" required :value="old('customer_name', $user?->name)" autocomplete="name" maxlength="100" />
                            <x-field name="phone" label="Հեռախոս" type="tel" required :value="old('phone')" autocomplete="tel" placeholder="+374 XX XX XX XX" />
                            <x-field name="email" label="Էլ. հասցե" type="email" :value="old('email', $user?->email)" autocomplete="email" class="field--full" hint="Կուղարկենք պատվերի հաստատումը" />
                        </div>
                    </fieldset>
                </div>

                <div class="form-section">
                    <fieldset @class(['fieldset', 'has-error' => $errors->has('delivery')])>
                        <legend>2. Առաքում</legend>
                        <div class="choices">
                            @foreach ($R::DELIVERY as $value => [$label, $hint])
                                <label class="choice">
                                    <input type="radio" name="delivery" value="{{ $value }}" @checked(old('delivery', 'courier') === $value)>
                                    <span class="choice__body"><strong>{{ $label }}</strong><span class="text-muted">{{ $hint }}</span></span>
                                </label>
                            @endforeach
                        </div>
                        <div class="form-grid">
                            <div class="field">
                                <label for="region" class="field__label">Մարզ</label>
                                <select id="region" name="region" @class(['input', 'input--error' => $errors->has('region')]) autocomplete="address-level1">
                                    @foreach ($R::REGIONS as $region)
                                        <option @selected(old('region', 'Երևան') === $region)>{{ $region }}</option>
                                    @endforeach
                                </select>
                                @error('region') <p class="field__error">{{ $message }}</p> @enderror
                            </div>
                            <x-field name="address" label="Հասցե" :value="old('address')" autocomplete="street-address" placeholder="Փողոց, շենք, բնակարան" maxlength="255" hint="Խանութից վերցնելիս պետք չէ" />
                            <div class="field field--full">
                                <label for="comment" class="field__label">Մեկնաբանություն առաքիչին</label>
                                <textarea id="comment" name="comment" rows="3" maxlength="500" @class(['input', 'input--error' => $errors->has('comment')])>{{ old('comment') }}</textarea>
                                @error('comment') <p class="field__error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="form-section">
                    <fieldset class="fieldset">
                        <legend>3. Վճարում</legend>
                        <div class="choices">
                            @foreach ($R::PAYMENT as $value => [$label, $hint])
                                <label class="choice">
                                    <input type="radio" name="payment" value="{{ $value }}" @checked(old('payment', 'cash') === $value)>
                                    <span class="choice__body"><strong>{{ $label }}</strong><span class="text-muted">{{ $hint }}</span></span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                </div>

                <div class="form-section">
                    <label @class(['check', 'has-error' => $errors->has('agree')])>
                        <input type="checkbox" name="agree" value="1" @checked(old('agree')) @error('agree') aria-invalid="true" aria-describedby="agree-error" @enderror>
                        <span>Համաձայն եմ առաքման և վերադարձի պայմաններին</span>
                    </label>
                    @error('agree') <p class="field__error" id="agree-error">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn btn--primary">Հաստատել պատվերը՝ {{ money($summary['total']) }}</button>
            </form>
        </div>

        <div class="col w-40 w-md-100">
            <aside class="summary" aria-labelledby="order-title">
                <h2 id="order-title" class="title-4 mb-20">Ձեր պատվերը</h2>
                <ul class="summary__items mb-20">
                    @foreach ($items as $item)
                    @php($p = $item['product'])
                        <li>
                            <picture class="ratio-1-1"><img class="img-cover" src="{{ $p->imageUrl() }}" alt="" width="600" height="400" loading="lazy"></picture>
                            <span>{{ $p->name }} × {{ $item['qty'] }}</span>
                            <strong>{{ money($item['sum']) }}</strong>
                        </li>
                    @endforeach
                </ul>
                @include('partials.summary')
            </aside>
        </div>
    </div>
</section>
@endsection
