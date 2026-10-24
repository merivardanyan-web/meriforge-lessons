{{-- Մեկ ֆորմա՝ create և edit — PHP Դաս 19 (old() և @error-ը՝ Դաս 21-ում) --}}
@extends('layouts.app')

@section('title', $book ? 'Խմբագրում' : 'Նոր գիրք')

@section('content')
<section>
    <div class="row">
        <div class="col w-70 w-md-100">
            <h1 class="mt-20">{{ $book ? 'Խմբագրում՝ ' . $book->title : 'Նոր գիրք' }}</h1>

            <form method="post" action="{{ $book ? route('admin.books.update', $book->id) : route('admin.books.store') }}" novalidate>
                @csrf
                @if ($book)
                    @method('PUT')          {{-- update-ը PUT է, store-ը՝ POST --}}
                @endif

                <div class="form-grid">
                    <label for="title">Վերնագիր *</label>
                    <div class="field"><input type="text" id="title" name="title" maxlength="200" value="{{ $book?->title }}"></div>

                    <label for="author">Հեղինակ *</label>
                    <div class="field"><input type="text" id="author" name="author" maxlength="100" value="{{ $book?->author }}"></div>

                    <label for="genre_id">Ժանր</label>
                    <div class="field">
                        <select id="genre_id" name="genre_id">
                            <option value="">— առանց ժանրի —</option>
                            @foreach ($genres as $g)
                                <option value="{{ $g->id }}" @selected((int) $book?->genre_id === (int) $g->id)>{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label for="pages">Էջեր *</label>
                    <div class="field"><input type="number" id="pages" name="pages" min="1" value="{{ $book?->pages }}"></div>

                    <label for="read_pages">Կարդացած</label>
                    <div class="field"><input type="number" id="read_pages" name="read_pages" min="0" value="{{ $book?->read_pages ?? 0 }}"></div>
                </div>

                <div class="flex gap-10 flex-wrap mt-20">
                    <button type="submit" class="btn btn--primary">{{ $book ? 'Պահպանել' : 'Ավելացնել' }}</button>
                    <a href="{{ route('admin.books.index') }}" class="btn btn--ghost">Չեղարկել</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
