{{-- <x-product-card :product="$p"> կոճակ </x-product-card> — Դաս 19, 20 --}}
@props(['product'])

<article {{ $attributes->class(['card', 'is-out' => ! $product->inStock()]) }}>
    @if ($product->hasDiscount())
        <span class="card__badge">-{{ $product->discount }}%</span>
    @elseif ($product->isNew())
        <span class="card__badge card__badge--new">Նոր</span>
    @endif

    <picture class="card__media ratio-3-2">
        <img class="img-cover" src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" width="600" height="400" loading="lazy">
    </picture>

    <div class="card__body">
        <h3 class="card__title"><a href="{{ route('product.show', $product) }}">{{ $product->name }}</a></h3>
        <p class="card__meta">{{ $product->specs }}</p>

        @if ($product->isDigital())
            <p class="card__note">Ակնթարթային առաքում էլ. փոստով</p>
        @elseif ($product->isLowStock())
            <p class="card__warning">Մնացել է {{ $product->stock }} հատ</p>
        @endif

        <div class="card__footer">
            <p class="card__price">
                {{ $product->formattedPrice() }}
                @if ($product->hasDiscount())<del>{{ $product->formattedOldPrice() }}</del>@endif
            </p>

            {{ $slot }}
        </div>
    </div>
</article>
