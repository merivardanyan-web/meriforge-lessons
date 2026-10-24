@extends('layouts.app')

@section('title', 'Իմ ամրագրումները')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <h1>Իմ ամրագրումները</h1>
            <p class="lead">Չեղարկել կարող ես մինչև այցից {{ App\Services\BookingService::CANCEL_HOURS }} ժամ առաջ։ Միաժամանակ՝ առավելագույնը {{ App\Services\BookingService::MAX_ACTIVE }} ակտիվ ամրագրում։</p>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-100">
            <h2>Առաջիկա</h2>
            @if ($upcoming->isEmpty())
                <p class="empty">Առաջիկա ամրագրում չունես։ <a href="{{ route('home') }}">Ընտրիր ծառայություն</a>։</p>
            @else
                <div class="table-wrap">
                    <table class="data-table" data-bookings>
                        <thead><tr><th>Երբ</th><th>Ծառայություն</th><th>Վարսավիր</th><th>Գին</th><th>Կարգավիճակ</th><th><span class="visually-hidden">Գործողություն</span></th></tr></thead>
                        <tbody>
                        @foreach ($upcoming as $b)
                            <tr data-booking="{{ $b->id }}">
                                <td>{{ $b->slot->starts_at->translatedFormat('l, j F') }}, <strong>{{ $b->slot->starts_at->format('H:i') }}</strong></td>
                                <td>{{ $b->service->name }}</td>
                                <td>{{ $b->slot->staff->name }}</td>
                                <td>{{ dram($b->price) }}</td>
                                <td><x-status :booking="$b" /></td>
                                <td>
                                    {{-- Կոճակը թաքցնելը պաշտպանություն չէ․ իսկականը՝ Gate::inspect('cancel') կոնտրոլերում --}}
                                    @can('cancel', $b)
                                        <form method="post" action="{{ route('bookings.cancel', $b) }}" data-cancel>
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn--danger btn--sm">Չեղարկել</button>
                                        </form>
                                    @else
                                        <span class="meta">Զանգիր՝ չեղարկելու համար</span>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <h2 class="mt-20">Պատմություն</h2>
            @if ($past->isEmpty())
                <p class="empty">Դեռ այցեր չես ունեցել։</p>
            @else
                <div class="table-wrap">
                    <table class="data-table">
                        <thead><tr><th>Երբ</th><th>Ծառայություն</th><th>Վարսավիր</th><th>Գին</th><th>Կարգավիճակ</th></tr></thead>
                        <tbody>
                        @foreach ($past as $b)
                            <tr @class(['row-muted' => $b->status === 'cancelled'])>
                                <td>{{ $b->slot->starts_at->translatedFormat('j F Y, H:i') }}</td>
                                <td>{{ $b->service->name }}</td>
                                <td>{{ $b->slot->staff->name }}</td>
                                <td>{{ dram($b->price) }}</td>
                                <td><x-status :booking="$b" /></td>
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
