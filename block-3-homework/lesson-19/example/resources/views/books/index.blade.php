{{-- Դարակ — PHP Դաս 18, 19 --}}
@extends('layouts.app')

@section('title', $genre?->name ?? 'Գրքեր')

@section('content')
<section id="shelf">
    <div class="row">
        <div class="col w-100">
            <h1 class="section-title">{{ $genre?->name ?? 'Իմ գրադարանը' }} ({{ $books->total() }})</h1>

            <ul class="genres">
                <li><a href="{{ route('home') }}" class="btn btn--outline btn--sm" @if (! $genre) aria-current="page" @endif>Բոլորը</a></li>
                @foreach ($genres as $g)
                    <li><a href="{{ route('genre', $g->slug) }}" class="btn btn--outline btn--sm" @if ($genre?->id === $g->id) aria-current="page" @endif>{{ $g->name }}</a></li>
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
                    <x-book-card :book="$book" :in-list="in_array($book->id, $listIds, true)" />
                @empty
                    <p class="empty">Գրքեր չեն գտնվել։</p>
                @endforelse
            </div>

            {{ $books->links() }}
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script type="module" src="{{ asset('js/list.js') }}"></script>
@endpush
