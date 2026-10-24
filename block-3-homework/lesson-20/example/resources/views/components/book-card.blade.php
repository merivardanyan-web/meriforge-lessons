{{-- <x-book-card :book="$book" :in-list="..." /> — PHP Դաս 19, Դաս 20-ից՝ Book մոդել --}}
@props(['book', 'inList' => false])

<article class="book is-{{ $book->status() }}">
    @if ($book->coverUrl())
        <div class="book__cover book__cover--img"><img src="{{ $book->coverUrl() }}" alt="«{{ $book->title }}»-ի շապիկը" width="400" height="200" loading="lazy"></div>
    @else
        <div class="book__cover" style="--cover: {{ $book->color }}"><span>{{ $book->title }}</span></div>
    @endif

    <div class="book__body">
        <h3 class="book__title"><a href="{{ route('book.show', $book) }}">{{ Str::limit($book->title, 30, '…') }}</a></h3>
        <p class="meta">{{ $book->author }} · {{ $book->yearLabel() }}</p>
        <p class="meta">{{ $book->formatLabel() }}@if ($book->status() !== 'done') · մնաց {{ duration($book->minutesLeft()) }}@endif</p>
        <div class="progress"><span class="progress__bar" style="--progress: {{ $book->progress() }}%"></span></div>
        <p class="meta">{{ $book->read_pages }} / {{ pages($book->pages) }} · {{ $book->progress() }}%</p>
        <p class="flex gap-10 align-center flex-wrap">
            <span class="pill">{{ $book->statusLabel() }}</span>
            @if ($book->isNew())<span class="pill pill--new">Նոր</span>@endif
            @if ($book->isLent())<span class="pill pill--lent">Տրված է՝ {{ $book->lent_to }}</span>@endif
        </p>

        <div class="book__actions">
            @if ($inList)
                <form method="post" action="{{ route('list.items.destroy', $book->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn--ghost btn--sm">✓ Ցուցակում է</button>
                </form>
            @elseif ($book->status() !== 'done')
                <form method="post" action="{{ route('list.items.store') }}" data-list-add>
                    @csrf
                    <input type="hidden" name="id" value="{{ $book->id }}">
                    <button type="submit" class="btn btn--outline btn--sm">+ Կարդալու ցուցակ</button>
                </form>
            @endif
        </div>
    </div>
</article>
