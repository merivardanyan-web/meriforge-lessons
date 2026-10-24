{{-- Վարսավիրի օրը․ վարսավիրը տեսնում է միայն իրենը (StaffController), ադմինը կարող է ընտրել --}}
@extends('layouts.app')

@section('title', 'Օրվա ցուցակ')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <h1>{{ $day->isToday() ? 'Այսօր' : Str::ucfirst($day->translatedFormat('l')) }}, {{ $day->translatedFormat('j F') }}</h1>
            <p class="lead">{{ $bookings->where('status', '!=', 'cancelled')->count() }} այց · ազատ է {{ $free }} ժամ</p>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-100">
            <form method="get" class="filters mb-20">
                <div class="field">
                    <label for="date">Օր</label>
                    <input type="date" id="date" name="date" value="{{ $day->toDateString() }}">
                </div>
                @if (auth()->user()->isAdmin())
                    <div class="field">
                        <label for="staff">Վարսավիր</label>
                        <select id="staff" name="staff">
                            <option value="">Բոլորը</option>
                            @foreach ($staff as $s)
                                <option value="{{ $s->id }}" @selected($s->id === $staffId)>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <button type="submit" class="btn btn--outline">Ցույց տալ</button>
                <a href="{{ route('staff.slots', ['date' => $day->toDateString()]) }}" class="btn btn--ghost">Աշխատանքային ժամեր →</a>
            </form>

            @if ($bookings->isEmpty())
                <p class="empty">Այս օրը ամրագրում չկա։</p>
            @else
                <div class="table-wrap">
                    <table class="data-table" data-bookings>
                        <thead><tr><th>Ժամ</th>@unless (auth()->user()->isStaff())<th>Վարսավիր</th>@endunless<th>Հաճախորդ</th><th>Ծառայություն</th><th>Նշում</th><th>Կարգավիճակ</th><th><span class="visually-hidden">Գործողություններ</span></th></tr></thead>
                        <tbody>
                        @foreach ($bookings as $b)
                            <tr data-booking="{{ $b->id }}" @class(['row-muted' => $b->status === 'cancelled'])>
                                <td><strong>{{ $b->slot->starts_at->format('H:i') }}</strong></td>
                                @unless (auth()->user()->isStaff())<td>{{ $b->slot->staff->name }}</td>@endunless
                                <td>{{ $b->user->name }}<br><a href="{{ safe_url('tel:' . str_replace(' ', '', (string) $b->user->phone)) }}" class="meta">{{ $b->user->phone }}</a></td>
                                <td>{{ $b->service->name }} · {{ dram($b->price) }}</td>
                                <td>{{ $b->note ?? '—' }}</td>
                                <td><x-status :booking="$b" /></td>
                                <td>
                                    <div class="actions">
                                        @can('complete', $b)
                                            <form method="post" action="{{ route('staff.complete', $b) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn--outline btn--sm">Կատարված</button>
                                            </form>
                                        @endcan
                                        @can('cancel', $b)
                                            <form method="post" action="{{ route('bookings.cancel', $b) }}" data-cancel>
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn--danger btn--sm">Չեղարկել</button>
                                            </form>
                                        @endcan
                                    </div>
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

@push('scripts')
    <script type="module" src="{{ asset('js/bookings.js') }}"></script>
@endpush
