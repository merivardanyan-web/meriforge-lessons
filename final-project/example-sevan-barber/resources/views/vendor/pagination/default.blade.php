{{-- php artisan vendor:publish --tag=laravel-pagination, ապա հարմարեցված main.css-ին — PHP Դաս 19, տնային 4
     Դաս 10-ի views/partials/pagination.php-ի նույն markup-ը --}}
@if ($paginator->hasPages())
    <nav class="pager mt-20" aria-label="Էջեր">
        @unless ($paginator->onFirstPage())
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn--ghost btn--sm" rel="prev">← Նախորդ</a>
        @endunless

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="btn btn--ghost btn--sm" aria-disabled="true">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    <a href="{{ $url }}" class="btn btn--outline btn--sm" @if ($page == $paginator->currentPage()) aria-current="page" @endif>{{ $page }}</a>
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn--ghost btn--sm" rel="next">Հաջորդ →</a>
        @endif
    </nav>
@endif
