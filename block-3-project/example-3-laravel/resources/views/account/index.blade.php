@extends('layouts.app')

@section('title', 'Իմ հաշիվը — ' . config('app.name'))

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <h1 class="mb-10">{{ $user->name }}</h1>
            <p class="text-muted">{{ $user->email }}</p>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-100">
            <h2 class="section-title mb-30">Իմ պատվերները</h2>

            @if ($orders->isEmpty())
                <div class="empty-state">
                    <p>Դեռ պատվերներ չունես։</p>
                    <a href="{{ route('catalog') }}" class="btn btn--primary">Անցնել կատալոգ</a>
                </div>
            @else
                <div class="scroll-x">
                    <table class="table admin-table">
                        <caption class="visually-hidden">Պատվերները՝ նորերը վերևում</caption>
                        <thead><tr><th scope="col">№</th><th scope="col">Ամսաթիվ</th><th scope="col">Հատ</th><th scope="col">Գումար</th><th scope="col">Կարգավիճակ</th></tr></thead>
                        <tbody>
                            @foreach ($orders as $o)
                                <tr>
                                    <td><a href="{{ route('checkout.thanks', $o) }}" class="link">{{ $o->number() }}</a></td>
                                    <td>{{ $o->created_at->format('d.m.Y') }}</td>
                                    <td>{{ (int) $o->units }}</td>
                                    <td>{{ money($o->total) }}</td>
                                    <td><span class="badge">{{ $o->statusLabel() }}</span></td>
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
