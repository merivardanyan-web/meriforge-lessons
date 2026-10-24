@extends('layouts.admin')

@section('title', 'Պատվերներ')

@section('content')
<section>
    <div class="row">
        <div class="col w-100">
            <h1 class="mb-20 mt-20">Պատվերներ ({{ $orders->total() }})</h1>

            @if ($orders->isEmpty())
                <p class="text-muted">Պատվերներ դեռ չկան։</p>
            @else
                <div class="scroll-x">
                    <table class="table admin-table">
                        <caption class="visually-hidden">Բոլոր պատվերները՝ նորերը վերևում</caption>
                        <thead><tr><th scope="col">№</th><th scope="col">Գնորդ</th><th scope="col">Տողեր / հատ</th><th scope="col">Գումար</th><th scope="col">Կարգավիճակ</th><th scope="col">Ամսաթիվ</th></tr></thead>
                        <tbody>
                            @foreach ($orders as $o)
                                <tr>
                                    <td>{{ $o->number() }}</td>
                                    <td>{{ $o->customer_name }}<br><span class="text-muted text-small">{{ $o->phone }}</span></td>
                                    <td>{{ $o->items_count }} / {{ (int) $o->units }}</td>
                                    <td>{{ money($o->total) }}</td>
                                    <td>
                                        <form method="post" action="{{ route('admin.orders.update', $o) }}" class="flex gap-10">
                                            @csrf
                                            @method('PATCH')
                                            <label for="status-{{ $o->id }}" class="visually-hidden">Կարգավիճակ</label>
                                            <select id="status-{{ $o->id }}" name="status" class="input input--sm">
                                                @foreach (\App\Models\Order::STATUS as $key => $label)
                                                    <option value="{{ $key }}" @selected($o->status === $key)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn--ghost btn--sm">Պահել</button>
                                        </form>
                                    </td>
                                    <td>{{ $o->created_at->format('d.m.Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $orders->links() }}
            @endif
        </div>
    </div>
</section>
@endsection
