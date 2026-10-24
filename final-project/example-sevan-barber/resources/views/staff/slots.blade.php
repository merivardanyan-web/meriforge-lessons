{{-- Աշխատանքային ժամեր՝ տեսնել, ավելացնել, հեռացնել ազատը --}}
@extends('layouts.app')

@section('title', 'Աշխատանքային ժամեր')

@php($isAdmin = auth()->user()->isAdmin())

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <h1>Աշխատանքային ժամեր</h1>
            <p class="lead">{{ $staff->firstWhere('id', $staffId)?->name }} · {{ $day->translatedFormat('l, j F') }}</p>
        </div>
    </div>
</section>

<section>
    <div class="row row--top flex-wrap">
        <div class="col w-60 w-md-100">
            <form method="get" class="filters mb-20">
                <div class="field">
                    <label for="date">Օր</label>
                    <input type="date" id="date" name="date" value="{{ $day->toDateString() }}">
                </div>
                @if ($isAdmin)
                    <div class="field">
                        <label for="staff">Վարսավիր</label>
                        <select id="staff" name="staff">
                            @foreach ($staff as $s)
                                <option value="{{ $s->id }}" @selected($s->id === $staffId)>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <button type="submit" class="btn btn--outline">Ցույց տալ</button>
            </form>

            @if ($slots->isEmpty())
                <p class="empty">Այս օրվա համար ժամեր չկան։ Ավելացրու աջ կողմի ֆորմայով։</p>
            @else
                <div class="table-wrap">
                    <table class="data-table">
                        <thead><tr><th>Ժամ</th><th>Վիճակ</th><th><span class="visually-hidden">Գործողություն</span></th></tr></thead>
                        <tbody>
                        @foreach ($slots as $slot)
                            <tr>
                                <td><strong>{{ $slot->starts_at->format('H:i') }}</strong></td>
                                <td>
                                    @if ($slot->activeBooking)
                                        {{ $slot->activeBooking->user->name }} · {{ $slot->activeBooking->service->name }} <x-status :booking="$slot->activeBooking" />
                                    @elseif ($slot->starts_at->isPast())
                                        <span class="meta">Անցած, չի ամրագրվել</span>
                                    @else
                                        Ազատ
                                    @endif
                                </td>
                                <td>
                                    @if (! $slot->activeBooking && $slot->bookings_count === 0)
                                        <form method="post" action="{{ route('staff.slots.destroy', $slot) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn--ghost btn--sm">Հեռացնել</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="col w-40 w-md-100">
            <form method="post" action="{{ route('staff.slots.store') }}" class="box" novalidate>
                @csrf
                <h2>Ավելացնել ժամեր</h2>
                <p class="meta">Ամեն ժամ՝ առանձին պատուհան։ Արդեն եղածները կանտեսվեն։</p>
                <div class="form-grid">
                    @if ($isAdmin)
                        <label for="staff_id">Վարսավիր *</label>
                        <div @class(['field', 'has-error' => $errors->has('staff_id')])>
                            <select id="staff_id" name="staff_id" @error('staff_id') aria-invalid="true" aria-describedby="staff_id-error" @enderror>
                                @foreach ($staff as $s)
                                    <option value="{{ $s->id }}" @selected((int) old('staff_id', $staffId) === $s->id)>{{ $s->name }}</option>
                                @endforeach
                            </select>
                            @error('staff_id')<small class="field__error" id="staff_id-error">{{ $message }}</small>@enderror
                        </div>
                    @endif
                    <x-field name="date" label="Օր" type="date" :value="$day->toDateString()" required />
                    <x-field name="from" label="Սկիզբ (ժամ)" type="number" min="8" max="21" value="10" required />
                    <x-field name="to" label="Ավարտ (ժամ)" type="number" min="9" max="22" value="19" required hint="19 → վերջին պատուհանը 18:00-ին" />
                </div>
                <button type="submit" class="btn btn--primary mt-20">Ավելացնել</button>
            </form>
        </div>
    </div>
</section>
@endsection
