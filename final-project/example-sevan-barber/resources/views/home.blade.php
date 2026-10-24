@extends('layouts.app')

@section('title', 'Վարսավիրանոց Սևանում')

@section('content')
<section class="hero">
    <div class="row">
        <div class="col w-100">
            <h1>Սանրվածք՝ առանց հերթի և զանգերի</h1>
            <p class="lead">Ընտրիր ծառայությունը, վարսավիրին և ազատ ժամը։ Ամրագրումը երկու րոպե է, չեղարկումը՝ մեկ սեղմում։</p>
            <a href="#services" class="btn btn--primary">Ընտրել ծառայություն</a>
            <ul class="hero__facts">
                <li>✂ {{ $staff->count() }} վարսավիր՝ {{ $staff->pluck('name')->map(fn ($n) => explode(' ', $n)[0])->join(' և ') }}</li>
                <li>🕙 Այսօր ազատ է {{ $freeToday }} ժամ</li>
                <li>📍 Սևան, Նաիրյան 12</li>
            </ul>
        </div>
    </div>
</section>

<section id="services">
    <div class="row">
        <div class="col w-100">
            <h2>Ծառայություններ</h2>
            @if ($services->isEmpty())
                <p class="empty">Ծառայությունները շուտով կավելացվեն։</p>
            @else
                <div class="services">
                    @foreach ($services as $service)
                        <x-service-card :service="$service" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-100">
            <h2>Ինչպես է աշխատում</h2>
            <ol class="steps">
                <li><strong>Ընտրիր ծառայություն</strong><br>Գինը և տևողությունը նշված են նախապես։</li>
                <li><strong>Ընտրիր ժամը</strong><br>Տեսնում ես միայն ազատ ժամերը՝ 7 օր առաջ։</li>
                <li><strong>Եկ ժամանակին</strong><br>Չես կարող գալ՝ չեղարկիր մինչև 2 ժամ առաջ։</li>
            </ol>
        </div>
    </div>
</section>
@endsection
