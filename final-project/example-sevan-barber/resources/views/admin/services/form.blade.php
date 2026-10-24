{{-- Նույն ֆորման՝ ստեղծման և խմբագրման համար --}}
@extends('layouts.app')

@section('title', $service->exists ? 'Խմբագրել ծառայությունը' : 'Նոր ծառայություն')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <ul class="crumbs">
                <li><a href="{{ route('admin.services.index') }}">Ծառայություններ</a></li>
                <li aria-current="page">{{ $service->exists ? $service->name : 'Նոր' }}</li>
            </ul>
            <h1>{{ $service->exists ? 'Խմբագրել ծառայությունը' : 'Նոր ծառայություն' }}</h1>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-100">
            @if ($errors->any())
                <p class="alert alert--danger" role="alert">Ուղղիր {{ $errors->count() }} սխալ և փորձիր կրկին։</p>
            @endif

            <form method="post" action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}" class="mt-20" novalidate>
                @csrf
                @if ($service->exists)
                    @method('PUT')
                @endif
                <div class="form-grid">
                    <x-field name="name" label="Անվանում" :value="$service->name" required maxlength="80" />
                    <x-field name="description" label="Նկարագրություն" type="textarea" :value="$service->description" maxlength="500" />
                    <x-field name="duration_min" label="Տևողություն, րոպե" type="number" :value="$service->duration_min" required min="10" max="60" />
                    <x-field name="price" label="Գին, ֏" type="number" :value="$service->price" required min="500" step="100" />
                    <label class="check">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service->is_active))> Երևում է կայքում
                    </label>
                </div>
                <div class="flex gap-10 mt-20">
                    <button type="submit" class="btn btn--primary">{{ $service->exists ? 'Պահպանել' : 'Ավելացնել' }}</button>
                    <a href="{{ route('admin.services.index') }}" class="btn btn--ghost">Չեղարկել</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
