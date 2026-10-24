@extends('layouts.app')

@section('title', 'Ջնջված ծառայություններ')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <ul class="crumbs">
                <li><a href="{{ route('admin.services.index') }}">Ծառայություններ</a></li>
                <li aria-current="page">Ջնջվածներ</li>
            </ul>
            <h1>Ջնջված ծառայություններ</h1>
            <p class="lead">Soft delete․ կայքում չեն երևում, բայց հին ամրագրումները պահում են անունը և գինը։</p>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-100">
            @if ($services->isEmpty())
                <p class="empty">Ջնջված ծառայություն չկա։</p>
            @else
                <div class="table-wrap">
                    <table class="data-table">
                        <thead><tr><th>Անվանում</th><th>Ջնջվել է</th><th><span class="visually-hidden">Գործողություն</span></th></tr></thead>
                        <tbody>
                        @foreach ($services as $s)
                            <tr>
                                <td>{{ $s->name }}</td>
                                <td>{{ $s->deleted_at->translatedFormat('j F Y, H:i') }}</td>
                                <td>
                                    <form method="post" action="{{ route('admin.services.restore', $s->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn--outline btn--sm">Վերականգնել</button>
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
