{{-- Մեկ ֆորմա՝ create և edit — PHP Դաս 19–21
     Դաս 21․ old()՝ սխալից հետո լրացրածը մնում է, @error՝ սխալը դաշտի տակ, <x-field>՝ նույնը ամեն դաշտում --}}
@extends('layouts.app')

@section('title', $book->exists ? 'Խմբագրում' : 'Նոր գիրք')

@section('content')
<section>
    <div class="row">
        <div class="col w-70 w-md-100">
            <h1 class="mt-20">{{ $book->exists ? 'Խմբագրում՝ ' . $book->title : 'Նոր գիրք' }}</h1>

            @if ($errors->any())
                <div class="alert alert--danger" role="alert">Ուղղիր {{ $errors->count() }} սխալ և փորձիր կրկին։</div>
            @endif

            {{-- enctype՝ առանց սրա $request->file('cover')-ը null է (Դաս 07) --}}
            <form method="post" action="{{ $book->exists ? route('admin.books.update', $book) : route('admin.books.store') }}" enctype="multipart/form-data" novalidate>
                @csrf
                @if ($book->exists)
                    @method('PUT')
                @endif

                <div class="form-grid">
                    <x-field name="title" label="Վերնագիր" :value="$book->title" required maxlength="200" />
                    <x-field name="author" label="Հեղինակ" :value="$book->author" required maxlength="100" />

                    <label for="genre_id">Ժանր</label>
                    <div @class(['field', 'has-error' => $errors->has('genre_id')])>
                        <select id="genre_id" name="genre_id" @error('genre_id') aria-invalid="true" aria-describedby="genre_id-error" @enderror>
                            <option value="">— առանց ժանրի —</option>
                            @foreach ($genres as $g)
                                {{-- old()-ը տող է ("3"), genre_id-ն՝ int․ համեմատում ենք տողերով --}}
                                <option value="{{ $g->id }}" @selected((string) old('genre_id', $book->genre_id) === (string) $g->id)>{{ $g->name }}</option>
                            @endforeach
                        </select>
                        @error('genre_id')<small class="field__error" id="genre_id-error">{{ $message }}</small>@enderror
                    </div>

                    <label for="format">Տեսակ</label>
                    <div @class(['field', 'has-error' => $errors->has('format')])>
                        <select id="format" name="format">
                            @foreach (App\Models\Book::FORMATS as $key => $label)
                                <option value="{{ $key }}" @selected(old('format', $book->format) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('format')<small class="field__error">{{ $message }}</small>@enderror
                    </div>

                    <x-field name="minutes" label="Տևողություն, րոպե" type="number" :value="$book->minutes" min="1" max="6000" hint="Միայն աուդիոգրքի համար" />
                    <x-field name="narrator" label="Ընթերցող" :value="$book->narrator" maxlength="100" />
                    <x-field name="lent_to" label="Տվել եմ" :value="$book->lent_to" maxlength="60" hint="Միայն թղթային գրքի համար" />
                    <x-field name="year" label="Տարի" type="number" :value="$book->year" min="1" :max="date('Y')" />
                    <x-field name="pages" label="Էջեր" type="number" :value="$book->pages" required min="1" max="5000" />
                    <x-field name="read_pages" label="Կարդացած" type="number" :value="$book->read_pages" min="0" />

                    <label for="rating">Գնահատական</label>
                    <div class="field">
                        <select id="rating" name="rating">
                            @for ($i = 0; $i <= 5; $i++)
                                <option value="{{ $i }}" @selected((int) old('rating', $book->rating) === $i)>{{ $i === 0 ? 'Դեռ չեմ գնահատել' : $i . ' ★' }}</option>
                            @endfor
                        </select>
                    </div>

                    <x-field name="color" label="Շապիկի գույն" type="color" :value="$book->color" />
                    <x-field name="link" label="Հղում" :value="$book->link" inputmode="url" placeholder="https://" maxlength="255" />
                    <x-field name="cover" label="Շապիկի նկար" type="file" accept="image/jpeg,image/png,image/webp" :hint="$book->cover ? 'Ընթացիկը՝ ' . $book->cover : 'JPG, PNG, WebP, մինչև 2 ՄԲ'" />

                    <label class="check">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $book->exists ? $book->is_active : true))>
                        Երևում է կայքում
                    </label>
                </div>

                <div class="flex gap-10 flex-wrap mt-20">
                    <button type="submit" class="btn btn--primary">{{ $book->exists ? 'Պահպանել' : 'Ավելացնել' }}</button>
                    <a href="{{ route('admin.books.index') }}" class="btn btn--ghost">Չեղարկել</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
