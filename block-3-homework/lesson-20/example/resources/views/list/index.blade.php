{{-- Կարդալու ցուցակ — PHP Դաս 19, տնային 2 (զամբյուղի էջի նման), Դաս 20-ից՝ Book մոդել --}}
@extends('layouts.app')

@section('title', 'Կարդալու ցուցակ')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <h1>Կարդալու ցուցակ</h1>
                <p class="lead">{{ $books->count() }} գիրք · մնացել է {{ pages($pagesLeft) }}</p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-100">
            @if ($books->isEmpty())
                <p class="empty">Ցուցակը դատարկ է։ <a href="{{ route('home') }}">Ընտրիր գիրք դարակից</a></p>
            @else
                <div class="table-wrap">
                    <table class="admin-table">
                        <caption class="visually-hidden">Ցուցակի գրքերը</caption>
                        <thead>
                            <tr><th scope="col">Գիրք</th><th scope="col">Մնաց</th><th scope="col">Օրական</th><th scope="col"><span class="visually-hidden">Գործողություն</span></th></tr>
                        </thead>
                        <tbody>
                        @foreach ($books as $book)
                            <tr>
                                <td><a href="{{ route('book.show', $book) }}">{{ $book->title }}</a></td>
                                <td>{{ pages($book->pagesLeft()) }}</td>
                                <td>{{ $perDay($book->id) }} էջ</td>
                                <td>
                                    {{-- HTML ֆորման ունի միայն GET և POST․ @method('DELETE')-ը թաքնված _method դաշտ է --}}
                                    <form method="post" action="{{ route('list.items.destroy', $book->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn--ghost btn--sm">Հանել</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
