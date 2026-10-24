@extends('layouts.app')

{{-- Inline արժեքը @section-ում նույնպես փախցվում է --}}
@section('title', $product->name . ' — ' . config('app.name'))

@section('content')
<section id="product">
    <div class="row">
        <div class="col w-100">
            <nav aria-label="Դուք այստեղ եք">
                <ol class="crumbs">
                    <li><a href="{{ route('home') }}">Գլխավոր</a></li>
                    <li><a href="{{ route('catalog') }}">Կատալոգ</a></li>
                    @if ($product->category)
                        <li><a href="{{ route('category', $product->category) }}">{{ $product->category->name }}</a></li>
                    @endif
                    <li aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row flex-wrap align-start">
        <div class="col w-50 w-md-100">
            <div class="gallery">
                <picture class="gallery__main ratio-3-2">
                    <img class="img-cover" src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" width="600" height="400">
                </picture>
            </div>
        </div>

        <div class="col w-50 w-md-100">
            <div class="wrapper">
                @if ($product->hasDiscount())
                    <span class="badge mb-10">-{{ $product->discount }}%</span>
                @endif
                <h1 class="mb-10">{{ $product->name }}</h1>
                <p class="text-muted mb-20">
                    Կոդ՝ TM-{{ 1000 + $product->id }} · {{ $product->isDigital() ? 'Թվային լիցենզիա' : 'Ապրանք' }} ·
                    <span class="stock">{{ $product->inStock() ? 'Առկա է' : 'Ժամանակավորապես չկա' }}</span>
                </p>

                <p class="product__price mb-20">
                    {{ $product->formattedPrice() }}
                    @if ($product->hasDiscount())<del>{{ $product->formattedOldPrice() }}</del>@endif
                </p>

                @if ($product->description)
                    {{-- nl2br(e(...))՝ նախ փախցնում ենք, հետո ավելացնում <br> — {!! !!}-ը միայն այսպես --}}
                    <p class="mb-20">{!! nl2br(e($product->description)) !!}</p>
                @endif

                @if ($product->inStock())
                    <form class="flex align-end gap-10 flex-wrap mb-30" method="post" action="{{ route('cart.items.store') }}" data-cart-add>
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <div class="field">
                            <label for="qty" class="field__label">Քանակ</label>
                            <input id="qty" name="qty" type="number" value="1" min="1" max="{{ $product->maxQty() }}" class="input input--qty">
                        </div>
                        <button type="submit" class="btn btn--primary">Ավելացնել զամբյուղ</button>
                    </form>
                @else
                    <p class="mb-30"><button type="button" class="btn btn--primary" disabled>Սպառված է</button></p>
                @endif
            </div>
        </div>
    </div>
</section>

<section id="details">
    <div class="row flex-wrap">
        <div class="col w-60 w-md-100">
            <h2 class="section-title mb-30">Ապրանքի մասին</h2>
            <table class="table specs">
                <caption class="visually-hidden">{{ $product->name }}-ի տվյալները</caption>
                <tbody>
                    <tr><th scope="row">Համառոտ</th><td>{{ $product->specs ?? '—' }}</td></tr>
                    <tr><th scope="row">Կատեգորիա</th><td>{{ $product->category?->name ?? 'Առանց կատեգորիայի' }}</td></tr>
                    @unless ($product->isDigital())
                        <tr><th scope="row">Պահեստում</th><td>{{ $product->stock }} հատ</td></tr>
                        <tr><th scope="row">Առաքում</th><td>{{ money($product->shippingCost()) }} · Երևանում անվճար {{ money(App\Services\Cart::FREE_FROM) }}-ից</td></tr>
                    @endunless
                    <tr><th scope="row">Խանութում է</th><td>{{ $product->created_at?->format('d.m.Y') }}-ից</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

@if ($related->isNotEmpty())
<section id="related">
    <div class="row">
        <div class="col w-100">
            <h2 class="section-title mb-30">Նման ապրանքներ</h2>
            <div class="grid-auto gap-20">
                @foreach ($related as $r)
                    <x-product-card :product="$r" />         {{-- առանց կոճակի — Դաս 19 --}}
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
@endsection
