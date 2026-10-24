{{-- Ծառայության էջը և ամրագրումը
     Առանց JS-ի՝ «Ցույց տալ ժամերը» կոճակը ուղարկում է GET ֆորման և էջը վերաբեռնվում է։
     JS-ով (slot-picker.js)՝ ընտրությունը փոխելիս ժամերը բերվում են /api/slots-ից --}}
@extends('layouts.app')

@section('title', $service->name)
@section('description', $service->name . ' — ' . dram($service->price) . ', ' . minutes($service->duration_min))

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <ul class="crumbs">
                <li><a href="{{ route('home') }}">Ծառայություններ</a></li>
                <li aria-current="page">{{ $service->name }}</li>
            </ul>
            <h1>{{ $service->name }}</h1>
            <ul class="summary">
                <li><span>Տևողություն՝</span> {{ minutes($service->duration_min) }}</li>
                <li><span>Գին՝</span> {{ dram($service->price) }}</li>
            </ul>
            @if ($service->description)
                <p class="lead">{{ $service->description }}</p>
            @endif
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-100">
            <h2>Ընտրիր ժամը</h2>

            <form method="get" action="{{ route('services.show', $service) }}" class="filters mb-20" data-slot-filter
                  data-api="{{ route('api.slots') }}">
                <div class="field">
                    <label for="date">Օր</label>
                    <select id="date" name="date">
                        @foreach ($days as $value => $day)
                            <option value="{{ $value }}" @selected($value === $date)>{{ $day->isToday() ? 'Այսօր' : ($day->isTomorrow() ? 'Վաղը' : $day->translatedFormat('l')) }}, {{ $day->translatedFormat('j F') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="staff">Վարսավիր</label>
                    <select id="staff" name="staff">
                        <option value="">Ցանկացածը</option>
                        @foreach ($staff as $s)
                            <option value="{{ $s->id }}" @selected($s->id === $staffId)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn--outline" data-no-js>Ցույց տալ ժամերը</button>
            </form>

            <form method="post" action="{{ route('bookings.store') }}" class="box" data-booking-form>
                @csrf
                <input type="hidden" name="service_id" value="{{ $service->id }}">

                <p class="slots-status" role="status" aria-live="polite" data-slots-status>
                    {{ $slots->isEmpty() ? 'Այս օրը ազատ ժամ չկա։ Ընտրիր այլ օր կամ վարսավիր։' : 'Ազատ է ' . $slots->count() . ' ժամ' }}
                </p>

                <fieldset @class(['slots', 'has-error' => $errors->has('slot_id')]) data-slots
                          @error('slot_id') aria-invalid="true" aria-describedby="slot_id-error" @enderror>
                    <legend class="visually-hidden">Ազատ ժամեր</legend>
                    @foreach ($slots as $slot)
                        <div class="slot">
                            <input type="radio" name="slot_id" id="slot-{{ $slot->id }}" value="{{ $slot->id }}" @checked((int) old('slot_id') === $slot->id)>
                            <label for="slot-{{ $slot->id }}">{{ $slot->starts_at->format('H:i') }}<small>{{ $slot->staff->name }}</small></label>
                        </div>
                    @endforeach
                </fieldset>
                @error('slot_id')
                    <p class="field__error" id="slot_id-error" role="alert">{{ $message }}</p>
                @enderror

                @auth
                    @can('book')
                        <div class="form-grid">
                            <x-field name="note" label="Նշում վարսավիրին" type="textarea" maxlength="255" hint="Ոչ պարտադիր, մինչև 255 նիշ" />
                        </div>
                        <button type="submit" class="btn btn--primary mt-20">Ամրագրել</button>
                    @else
                        <p class="meta">Ամրագրում են հաճախորդի հաշվով։ Դու մուտք ես գործել որպես {{ App\Models\User::ROLES[auth()->user()->role] }}։</p>
                    @endcan
                @else
                    <p><a href="{{ route('login') }}" class="btn btn--primary">Մուտք գործիր՝ ամրագրելու համար</a></p>
                    <p class="meta">Հաշիվ չունե՞ս՝ <a href="{{ route('register') }}">գրանցվիր</a>, մեկ րոպե է։</p>
                @endauth
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script type="module" src="{{ asset('js/slot-picker.js') }}"></script>
@endpush
