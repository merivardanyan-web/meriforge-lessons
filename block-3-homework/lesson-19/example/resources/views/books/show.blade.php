{{-- Գրքի էջ — PHP Դաս 18, 19 --}}
@extends('layouts.app')

@section('title', $book->title)

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <ol class="crumbs">
                    <li><a href="{{ route('home') }}">Գրքեր</a></li>
                    @if ($book->genre_slug)
                        <li><a href="{{ route('genre', $book->genre_slug) }}">{{ $book->genre }}</a></li>
                    @endif
                    <li aria-current="page">{{ $book->title }}</li>
                </ol>
                <h1>{{ $book->title }}</h1>
                <p class="lead">{{ $book->author }} · {{ $book->year ?? 'տարին անհայտ' }}</p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="row flex-wrap">
        <div class="col w-40 w-md-100">
            <div class="book book--detail">
                <div class="book__cover" style="--cover: {{ $book->color }}"><span>{{ $book->title }}</span></div>
            </div>
        </div>
        <div class="col w-60 w-md-100">
            <div class="wrapper">
                <div class="progress"><span class="progress__bar" style="--progress: {{ $book->progress }}%"></span></div>
                <p class="meta">{{ $book->read_pages }} / {{ pages($book->pages) }} · {{ $book->progress }}%</p>

                @if ($book->link)
                    {{-- {{ }}-ը փախցնում է չակերտները, բայց javascript:-ը չի կանգնեցնում → safe_url (տնային 6) --}}
                    <p><a href="{{ safe_url($book->link) }}" class="btn btn--outline" rel="noopener">Կարդալ գրքի մասին</a></p>
                @endif

                @unless ($inList)
                    <form method="post" action="{{ route('list.items.store') }}" data-list-add>
                        @csrf
                        <input type="hidden" name="id" value="{{ $book->id }}">
                        <button type="submit" class="btn btn--primary">+ Կարդալու ցուցակ</button>
                    </form>
                @endunless

                <p class="mt-20"><a href="{{ route('home') }}" class="btn btn--ghost">← Ամբողջ դարակը</a></p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script type="module" src="{{ asset('js/list.js') }}"></script>
@endpush
