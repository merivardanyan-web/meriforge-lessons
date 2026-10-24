@if ($product->inStock())
    <form method="post" action="{{ route('cart.items.store') }}" data-cart-add>
        @csrf
        <input type="hidden" name="id" value="{{ $product->id }}">
        <button class="btn btn--primary btn--sm">Գնել<span class="visually-hidden"> {{ $product->name }}</span></button>
    </form>
@else
    <button type="button" class="btn btn--primary btn--sm" disabled>Սպառված է</button>
@endif
