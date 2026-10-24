{{-- Գրքի էջ — PHP Դաս 18 --}}
<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $book->title }} — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
<main id="main">
    <section class="page-head">
        <div class="row">
            <div class="col w-100">
                <ol class="crumbs">
                    <li><a href="{{ route('home') }}">Գրքեր</a></li>
                    @if ($book->genre_slug)
                        <li><a href="{{ route('genre', $book->genre_slug) }}">{{ $book->genre }}</a></li>
                    @endif
                    <li aria-current="page">{{ $book->title }}</li>
                </ol>
                <h1>{{ $book->title }}</h1>
                <p class="lead">{{ $book->author }} · {{ $book->year ?? 'տարին անհայտ' }}</p>
                <div class="progress mt-20"><span class="progress__bar" style="--progress: {{ $book->progress }}%"></span></div>
                <p class="meta">{{ $book->read_pages }} / {{ $book->pages }} էջ · {{ $book->progress }}%</p>
                <p class="mt-20"><a href="{{ route('home') }}" class="btn btn--ghost">← Ամբողջ դարակը</a></p>
            </div>
        </div>
    </section>
</main>
</body>
</html>
