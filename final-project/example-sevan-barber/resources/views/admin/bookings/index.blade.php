{{-- Բոլոր ամրագրումները․ ֆիլտրերը GET-ով, էջավորումը պահում է դրանք (withQueryString) --}}
@extends('layouts.app')

@section('title', 'Ամրագրումներ · ադմին')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <h1>Ամրագրումներ ({{ $bookings->total() }})</h1>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-100">
            <form method="get" class="filters mb-20" role="search">
                <div class="field">
                    <label for="q">Հաճախորդ</label>
                    <input type="search" id="q" name="q" value="{{ $q }}" placeholder="Անուն, էլ. հասցե կամ հեռախոս" maxlength="60">
                </div>
                <div class="field">
                    <label for="date">Օր</label>
                    <input type="date" id="date" name="date" value="{{ $date }}">
                </div>
                <div class="field">
                    <label for="staff">Վարսավիր</label>
                    <select id="staff" name="staff">
                        <option value="">Բոլորը</option>
                        @foreach ($staff as $s)
                            <option value="{{ $s->id }}" @selected($s->id === $staffId)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="status">Կարգավիճակ</label>
                    <select id="status" name="status">
                        <option value="">Բոլորը</option>
                        @foreach (App\Models\Booking::STATUSES as $key => $label)
                            <option value="{{ $key }}" @selected($key === $status)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn--primary">Ցույց տալ</button>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn--ghost">Մաքրել</a>
            </form>

            @if ($bookings->isEmpty())
                <p class="empty">Ամրագրումներ չեն գտնվել։</p>
            @else
                <div class="table-wrap">
                    <table class="data-table" data-bookings>
                        <thead><tr><th>Երբ</th><th>Վարսավիր</th><th>Հաճախորդ</th><th>Ծառայություն</th><th>Գին</th><th>Կարգավիճակ</th><th><span class="visually-hidden">Գործողություն</span></th></tr></thead>
                        <tbody>
                        @foreach ($bookings as $b)
                            <tr data-booking="{{ $b->id }}" @class(['row-muted' => $b->status === 'cancelled'])>
                                <td>{{ $b->slot->starts_at->translatedFormat('j M, D') }} <strong>{{ $b->slot->starts_at->format('H:i') }}</strong></td>
                                <td>{{ $b->slot->staff->name }}</td>
                                <td>{{ $b->user->name }}<br><span class="meta">{{ $b->user->phone }}</span></td>
                                <td>{{ $b->service->name }}@if ($b->service->trashed()) <span class="meta">(ջնջված)</span>@endif</td>
                                <td>{{ dram($b->price) }}</td>
                                <td><x-status :booking="$b" /></td>
                                <td>
                                    @can('cancel', $b)
                                        <form method="post" action="{{ route('bookings.cancel', $b) }}" data-cancel>
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn--danger btn--sm">Չեղարկել</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $bookings->links() }}
            @endif
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script type="module" src="{{ asset('js/bookings.js') }}"></script>
@endpush
