{{-- <x-book-card :book="$book" :in-list="..." /> — Դաս 07-ի partials/book-card.php-ն, PHP Դաս 19 --}}
@props(['book', 'inList' => false])

@php
    $status = $book->read_pages <= 0 ? 'planned' : ($book->read_pages >= $book->pages ? 'done' : 'reading');
@endphp

<article class="book is-{{ $status }}">
    @if ($book->cover)
        <div class="book__cover book__cover--img"><img src="{{ asset('images/uploads/' . $book->cover) }}" alt="«{{ $book->title }}»-ի շապիկը" width="400" height="200" loading="lazy"></div>
    @else
        <div class="book__cover" style="--cover: {{ $book->color }}"><span>{{ $book->title }}</span></div>
    @endif

    <div class="book__body">
        <h3 class="book__title"><a href="{{ route('book.show', $book->slug) }}">{{ $book->title }}</a></h3>
        <p class="meta">{{ $book->author }} · {{ $book->year ?? 'տարին անհայտ' }}</p>
        <div class="progress"><span class="progress__bar" style="--progress: {{ $book->progress }}%"></span></div>
        <p class="meta">{{ $book->read_pages }} / {{ pages($book->pages) }} · {{ $book->progress }}%</p>

        <div class="book__actions">
            @if ($inList)
                <form method="post" action="{{ route('list.items.destroy', $book->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn--ghost btn--sm">✓ Ցուցակում է</button>
                </form>
            @elseif ($status !== 'done')
                <form method="post" action="{{ route('list.items.store') }}" data-list-add>
                    @csrf
                    <input type="hidden" name="id" value="{{ $book->id }}">
                    <button type="submit" class="btn btn--outline btn--sm">+ Կարդալու ցուցակ</button>
                </form>
            @endif
        </div>
    </div>
</article>
