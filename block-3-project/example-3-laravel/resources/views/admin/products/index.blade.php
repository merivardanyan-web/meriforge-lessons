@extends('layouts.admin')

@section('title', 'Ապրանքներ')

@section('content')
<section>
    <div class="row">
        <div class="col w-100">
            <div class="flex justify-between align-center flex-wrap gap-10 mb-20 mt-20">
                <h1>Ապրանքներ ({{ $products->total() }})</h1>

                <form method="get" action="{{ route('admin.products.index') }}" class="flex gap-10" role="search">
                    <label for="q" class="visually-hidden">Որոնել ապրանք</label>
                    <input id="q" name="q" type="search" class="input input--sm" placeholder="Անվանում…" value="{{ $q }}">
                    <button class="btn btn--ghost btn--sm">Որոնել</button>
                </form>
            </div>

            @cannot('delete-products')
                <p class="alert">Դու մենեջեր ես․ կարող ես ավելացնել և խմբագրել, իսկ ջնջել կարող է միայն ադմինը։</p>
            @endcannot

            <div class="scroll-x">
                <table class="table admin-table">
                    <caption class="visually-hidden">Բոլոր ապրանքները, ներառյալ անջատվածները</caption>
                    <thead>
                        <tr>
                            <th scope="col">Անվանում</th>
                            <th scope="col">Կատեգորիա</th>
                            <th scope="col">Գին</th>
                            <th scope="col">Պահեստ</th>
                            <th scope="col">Կարգավիճակ</th>
                            <th scope="col"><span class="visually-hidden">Գործողություններ</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $p)
                            <tr @class(['row-inactive' => ! $p->is_active])>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->category?->name ?? '—' }}</td>
                                <td>{{ $p->formattedPrice() }} @if ($p->hasDiscount())<span class="text-muted text-small">−{{ $p->discount }}%</span>@endif</td>
                                <td>{{ $p->isDigital() ? '∞' : $p->stock }}</td>
                                <td>{{ $p->is_active ? 'Ակտիվ' : 'Անջատված' }}</td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('admin.products.edit', $p) }}" class="btn btn--ghost btn--sm">Խմբագրել</a>
                                        {{-- @can-ը միայն ցուցադրում է։ Իրական ստուգումը՝ destroy()-ում ⭐ Դաս 21 --}}
                                        @can('delete-products')
                                            <form method="post" action="{{ route('admin.products.destroy', $p) }}" onsubmit="return confirm('Ջնջե՞լ ապրանքը')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn--danger btn--sm">Ջնջել</button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $products->links() }}
        </div>
    </div>
</section>
@endsection
