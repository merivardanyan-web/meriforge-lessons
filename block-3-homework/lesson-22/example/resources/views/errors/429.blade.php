{{-- Fortify-ի throttle:login-ը՝ 5 փորձ րոպեում ըստ հասցեի և IP-ի — PHP Դաս 21
     Առանց այս ֆայլի Laravel-ը ցույց կտա անգլերեն «429 | TOO MANY REQUESTS» --}}
@extends('layouts.app')

@section('title', '429')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <p class="meta">Սխալ 429</p>
                <h1>Չափազանց շատ փորձեր</h1>
                <p class="lead">Մուտքը ժամանակավորապես արգելափակված է։ Փորձիր մեկ րոպեից։</p>
                <p><a href="{{ route('home') }}" class="btn btn--primary">Դարակ</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
