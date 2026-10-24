@extends('layouts.admin')

@section('title', 'Ջնջվածներ')

@section('content')
<section>
    <div class="row">
        <div class="col w-100">
            <h1 class="mb-20 mt-20">Ջնջվածներ ({{ $products->count() }})</h1>
            <p class="text-muted mb-20">Փափուկ ջնջում․ ապրանքը կայքում չկա, բայց հին պատվերները չեն կոտրվում — Դաս 20</p>

            @if ($products->isEmpty())
                <p class="text-muted">Ջնջված ապրանքներ չկան։</p>
            @else
                <div class="scroll-x">
                    <table class="table admin-table">
                        <caption class="visually-hidden">Ջնջված ապրանքներ</caption>
                        <thead><tr><th scope="col">Անվանում</th><th scope="col">Կատեգորիա</th><th scope="col">Ջնջվել է</th><th scope="col"><span class="visually-hidden">Գործողություն</span></th></tr></thead>
                        <tbody>
                            @foreach ($products as $p)
                                <tr>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ $p->category?->name ?? '—' }}</td>
                                    <td>{{ $p->deleted_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <form method="post" action="{{ route('admin.products.restore', $p) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn--ghost btn--sm">Վերականգնել</button>
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
