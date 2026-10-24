@extends('layouts.app')

@section('title', '429 — ' . config('app.name'))

@section('content')
<section class="status">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <p class="status__code" aria-hidden="true">429</p>
                <h1 class="mb-10">Չափազանց շատ փորձեր</h1>
                {{-- Fortify-ի throttle:login-ը՝ 5 փորձ րոպեում ըստ հասցեի և IP-ի — PHP Դաս 21 --}}
                <p class="text-muted mb-30">Մուտքը ժամանակավորապես արգելափակված է։ Փորձեք մեկ րոպեից։</p>
                <div class="flex gap-10 flex-wrap justify-center">
                    <a href="{{ route('home') }}" class="btn btn--primary">Գլխավոր էջ</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
