{{-- Գրքի էջ — PHP Դաս 18–20 --}}
@extends('layouts.app')

@section('title', $book->title)

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <ol class="crumbs">
                    <li><a href="{{ route('home') }}">Գրքեր</a></li>
                    @if ($book->genre)
                        <li><a href="{{ route('genre', $book->genre) }}">{{ $book->genre->name }}</a></li>
                    @endif
                    <li aria-current="page">{{ $book->title }}</li>
                </ol>
                <h1>{{ $book->title }}</h1>
                <p class="lead">{{ $book->author }} · {{ $book->yearLabel() }} · {{ $book->genre?->name ?? 'առանց ժանրի' }}</p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="row flex-wrap">
        <div class="col w-40 w-md-100">
            <div class="book book--detail is-{{ $book->status() }}">
                @if ($book->coverUrl())
                    <div class="book__cover book__cover--img"><img src="{{ $book->coverUrl() }}" alt="«{{ $book->title }}»-ի շապիկը" width="400" height="200"></div>
                @else
                    <div class="book__cover" style="--cover: {{ $book->color }}"><span>{{ $book->title }}</span></div>
                @endif
            </div>
        </div>
        <div class="col w-60 w-md-100">
            <div class="wrapper">
                <p class="flex gap-10 flex-wrap align-center">
                    <span class="pill">{{ $book->statusLabel() }}</span>
                    <span>{{ $book->formatLabel() }}</span>
                </p>

                @if ($book->isAudio())
                    <p>Տևողությունը՝ <strong>{{ duration($book->minutes) }}</strong>@if ($book->narrator) · կարդում է {{ $book->narrator }}@endif</p>
                @endif
                @if ($book->isLent())
                    <p class="alert">Գիրքը հիմա {{ $book->lent_to }}-ի մոտ է։</p>
                @endif

                <div class="progress mt-20"><span class="progress__bar" style="--progress: {{ $book->progress() }}%"></span></div>
                <p class="meta">{{ $book->read_pages }} / {{ pages($book->pages) }} · {{ $book->progress() }}%@if ($book->status() !== 'done') · մնաց {{ duration($book->minutesLeft()) }}@endif</p>

                @if ($book->link)
                    <p><a href="{{ safe_url($book->link) }}" class="btn btn--outline" rel="noopener">Կարդալ գրքի մասին</a></p>
                @endif

                @if (! $inList && $book->status() !== 'done')
                    <form method="post" action="{{ route('list.items.store') }}" data-list-add>
                        @csrf
                        <input type="hidden" name="id" value="{{ $book->id }}">
                        <button type="submit" class="btn btn--primary">+ Կարդալու ցուցակ</button>
                    </form>
                @endif

                @if ($book->sessions->isNotEmpty())
                    <h2 class="mt-20">Ընթերցանության օրեր</h2>
                    <div class="table-wrap">
                        <table>
                            <caption class="visually-hidden">Երբ և քանի էջ եմ կարդացել</caption>
                            <thead><tr><th scope="col">Օր</th><th scope="col">Էջ</th><th scope="col">Նշում</th></tr></thead>
                            <tbody>
                            @foreach ($book->sessions as $s)
                                <tr><td>{{ $s->read_on->format('Y-m-d') }}</td><td>{{ $s->pages }}</td><td>{{ $s->note }}</td></tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <p class="mt-20"><a href="{{ route('home') }}" class="btn btn--ghost">← Ամբողջ դարակը</a></p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script type="module" src="{{ asset('js/list.js') }}"></script>
@endpush
