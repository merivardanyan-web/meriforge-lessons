@extends('layouts.app')

@section('title', 'Ծառայություններ · ադմին')

@section('content')
<section class="page-head">
    <div class="row justify-between align-end flex-wrap">
        <div class="col">
            <h1>Ծառայություններ ({{ $services->count() }})</h1>
        </div>
        <div class="col flex gap-10">
            <a href="{{ route('admin.services.trashed') }}" class="btn btn--ghost">Ջնջվածներ</a>
            <a href="{{ route('admin.services.create') }}" class="btn btn--primary">+ Նոր ծառայություն</a>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-100">
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr><th>Անվանում</th><th>Տևողություն</th><th>Գին</th><th>Ամրագրումներ</th><th>Կայքում</th><th><span class="visually-hidden">Գործողություններ</span></th></tr></thead>
                    <tbody>
                    @foreach ($services as $s)
                        <tr @class(['row-muted' => ! $s->is_active])>
                            <td>{{ $s->name }}</td>
                            <td>{{ minutes($s->duration_min) }}</td>
                            <td>{{ dram($s->price) }}</td>
                            <td>{{ $s->bookings_count }}</td>
                            <td>{{ $s->is_active ? 'Այո' : 'Թաքցված' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.services.edit', $s) }}" class="btn btn--outline btn--sm">Խմբագրել</a>
                                    <form method="post" action="{{ route('admin.services.destroy', $s) }}" onsubmit="return confirm('Ջնջե՞լ ծառայությունը։ Հին ամրագրումները կմնան։')">
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
