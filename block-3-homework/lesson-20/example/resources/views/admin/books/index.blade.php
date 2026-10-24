{{-- Ադմին՝ գրքեր — PHP Դաս 19, 20 --}}
@extends('layouts.app')

@section('title', 'Ադմին · գրքեր')

@section('content')
<section>
    <div class="row">
        <div class="col w-100">
            <div class="flex justify-between align-center flex-wrap gap-10 mt-20">
                <h1>Գրքեր ({{ $books->count() }})</h1>
                <form method="get" action="{{ route('admin.books.index') }}" class="flex gap-10" role="search">
                    <label for="q" class="visually-hidden">Որոնել գիրք</label>
                    <input id="q" name="q" type="search" placeholder="Վերնագիր կամ հեղինակ" value="{{ $q }}">
                    <button class="btn btn--ghost btn--sm">Որոնել</button>
                </form>
                <div class="flex gap-10">
                    <a href="{{ route('admin.books.trashed') }}" class="btn btn--ghost btn--sm">Ջնջվածներ</a>
                    <a href="{{ route('admin.books.create') }}" class="btn btn--primary btn--sm">+ Նոր գիրք</a>
                </div>
            </div>

            <div class="table-wrap">
                <table class="admin-table">
                    <caption class="visually-hidden">Բոլոր գրքերը</caption>
                    <thead>
                        <tr>
                            <th scope="col">Վերնագիր</th><th scope="col">Հեղինակ</th><th scope="col">Ժանր</th>
                            <th scope="col">Կարդացած</th><th scope="col">Կարգավիճակ</th><th scope="col"><span class="visually-hidden">Գործողություններ</span></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($books as $b)
                        <tr @class(['row-inactive' => ! $b->is_active])>
                            <td>{{ $b->title }}</td>
                            <td>{{ $b->author }}</td>
                            <td>{{ $b->genre?->name ?? '—' }}</td>
                            <td>{{ $b->read_pages }} / {{ $b->pages }}</td>
                            <td>{{ $b->is_active ? 'Ակտիվ' : 'Թաքցված' }}</td>
                            <td>
                                <div class="flex gap-10">
                                    <a href="{{ route('admin.books.edit', $b) }}" class="btn btn--ghost btn--sm">Խմբագրել</a>
                                    <form method="post" action="{{ route('admin.books.destroy', $b) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn--danger btn--sm">Ջնջել</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
