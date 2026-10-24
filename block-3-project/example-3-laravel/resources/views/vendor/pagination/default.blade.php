{{-- php artisan vendor:publish --tag=laravel-pagination, ապա հարմարեցված main.css-ին — Դաս 19-ի տնային
     Նույն markup-ը, ինչ Առաջադրանք 1-ի partials/pagination.php-ում --}}
@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Էջավորում">
        <ul class="flex flex-wrap gap-10">
            @unless ($paginator->onFirstPage())
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">{{ __('pagination.previous') }}</a></li>
            @endunless

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span aria-hidden="true">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li><a href="{{ $url }}" @if ($page == $paginator->currentPage()) aria-current="page" @endif>{{ $page }}</a></li>
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">{{ __('pagination.next') }}</a></li>
            @endif
        </ul>
    </nav>
@endif
