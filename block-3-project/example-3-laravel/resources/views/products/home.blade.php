@extends('layouts.app')

@section('title', 'ՏեխնոՄարկետ — տեխնիկա Երևանում')

@section('content')
<section id="hero">
    <div class="row">
        <div class="col w-70 w-md-100">
            <div class="wrapper">
                <h1 class="mb-10">Տեխնիկա, որին կարելի է վստահել</h1>
                <p class="hero__lead mb-20">Ընտրիր 5000+ ապրանքից · Անվճար առաքում Երևանում · 2 տարվա երաշխիք</p>
                <div class="flex gap-10 flex-wrap">
                    <a href="{{ route('catalog') }}" class="btn btn--light">Դիտել կատալոգը</a>
                    <a href="#sale" class="btn btn--outline-light">Զեղչեր</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="products">
    <div class="row">
        <div class="col w-100">
            <h2 class="section-title mb-30">Նոր ապրանքներ</h2>
            <div class="grid-auto gap-20 mb-30">
                @foreach ($products as $p)
                    <x-product-card :product="$p">
                        @include('cart._add-form', ['product' => $p])
                    </x-product-card>
                @endforeach
            </div>
            <div class="flex justify-center">
                <a href="{{ route('catalog') }}" class="btn btn--ghost">Ամբողջ կատալոգը</a>
            </div>
        </div>
    </div>
</section>

@if ($sale->isNotEmpty())
<section id="sale" class="bg-surface">
    <div class="row">
        <div class="col w-100">
            <h2 class="section-title mb-30">Զեղչեր</h2>
            <div class="grid-auto gap-20">
                @foreach ($sale as $p)
                    <x-product-card :product="$p">
                        @include('cart._add-form', ['product' => $p])
                    </x-product-card>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
@endsection
