{{-- Դարակ — PHP Դաս 18․ layout-ը Դաս 19-ում է, առայժմ ամբողջ էջը մեկ ֆայլում --}}
<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $genre?->name ?? 'Գրքեր' }} — {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('images/favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
<main id="main">
    <section>
        <div class="row">
            <div class="col w-100">
                <h1 class="section-title">{{ $genre?->name ?? 'Իմ գրադարանը' }} ({{ $books->total() }})</h1>

                <ul class="genres">
                    <li><a href="{{ route('home') }}" class="btn btn--outline btn--sm">Բոլորը</a></li>
                    @foreach ($genres as $g)
                        <li><a href="{{ route('genre', $g->slug) }}" class="btn btn--outline btn--sm">{{ $g->name }}</a></li>
                    @endforeach
                </ul>

                <form method="get" class="filter-bar">
                    <div class="field">
                        <label for="sort">Դասավորել</label>
                        <select id="sort" name="sort">
                            @foreach ($sorts as $key => $info)
                                <option value="{{ $key }}" @selected($sort === $key)>{{ $info[0] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-bar__actions"><button class="btn btn--primary btn--sm">Ցույց տալ</button></div>
                </form>

                <div class="grid-auto gap-20">
                    @forelse ($books as $book)
                        <article class="book">
                            <div class="book__cover" style="--cover: {{ $book->color }}"><span>{{ $book->title }}</span></div>
                            <div class="book__body">
                                <h3 class="book__title"><a href="{{ route('book.show', $book->slug) }}">{{ $book->title }}</a></h3>
                                <p class="meta">{{ $book->author }} · {{ $book->year ?? 'տարին անհայտ' }}</p>
                                <div class="progress"><span class="progress__bar" style="--progress: {{ $book->progress }}%"></span></div>
                                <p class="meta">{{ $book->read_pages }} / {{ $book->pages }} էջ · {{ $book->progress }}%</p>
                            </div>
                        </article>
                    @empty
                        <p class="empty">Գրքեր չեն գտնվել։</p>
                    @endforelse
                </div>

                {{-- Laravel-ի լռելյայն էջավորումը՝ Tailwind դասերով։ Մերը՝ Դաս 19-ի տնային 4 --}}
                {{ $books->links() }}
            </div>
        </div>
    </section>
</main>
</body>
</html>
