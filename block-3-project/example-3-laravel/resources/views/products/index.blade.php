@extends('layouts.app')

@section('title', ($category->name ?? 'Կատալոգ') . ' — ' . config('app.name'))

@php
    $sorts  = \App\Http\Controllers\ProductController::SORTS;
    $stocks = \App\Http\Controllers\ProductController::STOCK;
    $action = $category ? route('category', $category) : route('catalog');
@endphp

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <nav aria-label="Դուք այստեղ եք">
                <ol class="crumbs">
                    <li><a href="{{ route('home') }}">Գլխավոր</a></li>
                    @if ($category)
                        <li><a href="{{ route('catalog') }}">Կատալոգ</a></li>
                        <li aria-current="page">{{ $category->name }}</li>
                    @else
                        <li aria-current="page">Կատալոգ</li>
                    @endif
                </ol>
            </nav>
            <h1 class="mb-10">{{ $category->name ?? 'Կատալոգ' }}</h1>
            <p class="text-muted">Նոութբուքեր, մոնիտորներ, սմարթֆոններ և աքսեսուարներ՝ 2 տարվա երաշխիքով</p>
        </div>
    </div>
</section>

<section id="catalog">
    <div class="row align-start flex-wrap">
        <aside class="col w-25 w-md-100" aria-label="Ֆիլտրեր">
            <form class="filters" action="{{ route('catalog') }}" method="get">
                <h2 class="title-4 mb-20">Ֆիլտրեր</h2>
                @if ($sort !== 'newest')
                    <input type="hidden" name="sort" value="{{ $sort }}">
                @endif

                <div class="filters__groups">
                    <fieldset class="filters__group">
                        <legend>Կատեգորիա</legend>
                        <ul>
                            <li><label class="check"><input type="radio" name="category" value="" @checked($category === null)> Բոլորը</label></li>
                            @foreach ($categories as $c)
                                <li><label class="check"><input type="radio" name="category" value="{{ $c->slug }}" @checked($category?->id === $c->id)> {{ $c->name }}</label></li>
                            @endforeach
                        </ul>
                    </fieldset>

                    <fieldset class="filters__group">
                        <legend>Գին, ֏</legend>
                        <div class="flex gap-10">
                            <div class="field">
                                <label for="min" class="field__label">Սկսած</label>
                                <input id="min" name="min" type="number" min="0" step="1000" placeholder="0" class="input input--sm" value="{{ $filters['min'] }}">
                            </div>
                            <div class="field">
                                <label for="max" class="field__label">Մինչև</label>
                                <input id="max" name="max" type="number" min="0" step="1000" placeholder="600000" class="input input--sm" value="{{ $filters['max'] }}">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="filters__group">
                        <legend>Ցուցադրել</legend>
                        <ul>
                            @foreach ($stocks as $value => $label)
                                <li><label class="check"><input type="radio" name="stock" value="{{ $value }}" @checked($filters['stock'] === $value)> {{ $label }}</label></li>
                            @endforeach
                        </ul>
                    </fieldset>
                </div>

                <input type="hidden" name="q" value="{{ $filters['q'] }}">
                <div class="flex gap-10 flex-wrap">
                    <button type="submit" class="btn btn--primary">Կիրառել</button>
                    <a href="{{ route('catalog') }}" class="btn btn--ghost">Մաքրել</a>
                </div>
            </form>
        </aside>

        <div class="col w-75 w-md-100">
            <div class="toolbar flex justify-between align-center flex-wrap gap-10">
                <p class="text-muted" role="status">
                    @if ($products->total() > 0)
                        Ցուցադրված է {{ $products->firstItem() }}–{{ $products->lastItem() }}՝ {{ $products->total() }} ապրանքից
                    @else
                        Ոչինչ չգտնվեց
                    @endif
                </p>

                <form class="flex align-center flex-wrap gap-10" action="{{ $action }}" method="get" role="search">
                    @foreach (['min', 'max'] as $key)
                        @if ($filters[$key] !== null)
                            <input type="hidden" name="{{ $key }}" value="{{ $filters[$key] }}">
                        @endif
                    @endforeach
                    @if ($filters['stock'] !== 'all')
                        <input type="hidden" name="stock" value="{{ $filters['stock'] }}">
                    @endif

                    <label for="q" class="visually-hidden">Որոնել</label>
                    <input id="q" name="q" type="search" class="input input--sm toolbar__search" placeholder="Որոնել…" value="{{ $filters['q'] }}" maxlength="100">

                    <label for="sort" class="field__label">Դասավորել</label>
                    <select id="sort" name="sort" class="input input--sm">
                        @foreach ($sorts as $key => [$label])
                            <option value="{{ $key }}" @selected($sort === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn--ghost btn--sm">Կիրառել</button>
                </form>
            </div>

            @if ($products->isEmpty())
                <div class="empty-state">
                    <p>{{ $filters['q'] !== '' ? '«' . $filters['q'] . '» հարցմամբ' : 'Այս ֆիլտրերով' }} ապրանք չկա։</p>
                    <a href="{{ route('catalog') }}" class="btn btn--primary">Մաքրել ֆիլտրերը</a>
                </div>
            @else
                <div class="grid-auto gap-20">
                    @foreach ($products as $p)
                        <x-product-card :product="$p">
                            @include('cart._add-form', ['product' => $p])
                        </x-product-card>
                    @endforeach
                </div>
            @endif

            {{ $products->links() }}         {{-- withQueryString()-ը՝ կոնտրոլերում --}}
        </div>
    </div>
</section>
@endsection
