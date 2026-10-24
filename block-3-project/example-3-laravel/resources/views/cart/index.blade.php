@extends('layouts.app')

@section('title', 'Զամբյուղ — ' . config('app.name'))

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <nav aria-label="Դուք այստեղ եք">
                <ol class="crumbs">
                    <li><a href="{{ route('home') }}">Գլխավոր</a></li>
                    <li aria-current="page">Զամբյուղ</li>
                </ol>
            </nav>
            <h1 class="mb-10">Զամբյուղ</h1>
            <p class="text-muted">{{ $summary['count'] }} ապրանք</p>
        </div>
    </div>
</section>

<section id="cart">
    @if ($items->isEmpty())
        <div class="row"><div class="col w-100">
            <div class="empty-state">
                <p>Զամբյուղը դատարկ է։</p>
                <a href="{{ route('catalog') }}" class="btn btn--primary">Անցնել կատալոգ</a>
            </div>
        </div></div>
    @else
    <div class="row flex-wrap">
        <div class="col w-70 w-md-100">
            {{-- Դաս 19-ի տնային․ տողեր, «Հանել» @method('DELETE')-ով, ընդհանուր գումար --}}
            <table class="table cart-table mb-20">
                <caption class="visually-hidden">Զամբյուղի ապրանքները</caption>
                <thead>
                    <tr>
                        <th scope="col">Ապրանք</th>
                        <th scope="col">Գին</th>
                        <th scope="col">Քանակ</th>
                        <th scope="col">Գումար</th>
                        <th scope="col"><span class="visually-hidden">Գործողություն</span></th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    @php($p = $item['product'])
                    <tr>
                        <td>
                            <div class="cart-table__product">
                                <picture class="ratio-1-1">
                                    <img class="img-cover" src="{{ $p->imageUrl() }}" alt="" width="600" height="400" loading="lazy">
                                </picture>
                                <div>
                                    <a href="{{ route('product.show', $p) }}">{{ $p->name }}</a>
                                    <p class="card__meta">{{ $p->specs }}</p>
                                </div>
                            </div>
                        </td>
                        <td><span class="cell-label">Գին</span> <span>{{ $p->formattedPrice() }}
                            @if ($p->hasDiscount())<del class="text-small text-muted">{{ $p->formattedOldPrice() }}</del>@endif</span></td>
                        <td>
                            <span class="cell-label" aria-hidden="true">Քանակ</span>
                            <form method="post" action="{{ route('cart.items.update', $p->id) }}" class="qty-form" novalidate>
                                @csrf
                                @method('PATCH')
                                <button type="submit" name="action" value="set" class="visually-hidden" tabindex="-1">Թարմացնել</button>
                                <button type="submit" name="action" value="dec" class="qty-btn" aria-label="Պակասեցնել՝ {{ $p->name }}">−</button>
                                <label for="q{{ $p->id }}" class="visually-hidden">Քանակ՝ {{ $p->name }}</label>
                                <input id="q{{ $p->id }}" name="qty" type="number" value="{{ $item['qty'] }}" min="1" max="{{ $p->maxQty() }}" class="input input--sm input--qty">
                                <button type="submit" name="action" value="inc" class="qty-btn" aria-label="Ավելացնել՝ {{ $p->name }}" @disabled($item['qty'] >= $p->maxQty())>+</button>
                            </form>
                        </td>
                        <td><span class="cell-label">Գումար</span> <strong>{{ money($item['sum']) }}</strong></td>
                        <td>
                            <form method="post" action="{{ route('cart.items.destroy', $p->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="link-danger">Հեռացնել<span class="visually-hidden"> {{ $p->name }}</span></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="cart-actions flex justify-between flex-wrap gap-10">
                <a href="{{ route('catalog') }}" class="btn btn--ghost">← Շարունակել գնումները</a>
                <form method="post" action="{{ route('cart.clear') }}" onsubmit="return confirm('Դատարկե՞լ զամբյուղը')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn--ghost">Դատարկել զամբյուղը</button>
                </form>
            </div>
        </div>

        <div class="col w-30 w-md-100">
            <aside class="summary" aria-labelledby="summary-title">
                <h2 id="summary-title" class="title-4 mb-20">Ամփոփում</h2>
                @include('partials.summary')
                <a href="{{ route('checkout') }}" class="btn btn--primary w-100 mt-20">Ձևակերպել պատվերը</a>
            </aside>
        </div>
    </div>
    @endif
</section>
@endsection
