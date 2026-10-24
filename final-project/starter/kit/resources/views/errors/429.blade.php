{{-- Laravel-ը ինքն է ընտրում errors/429.blade.php-ն։ $exception->getMessage()-ը ցույց չենք տալիս --}}
@extends('layouts.app')

@section('title', '429')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <p class="meta">Սխալ 429</p>
            <h1>Չափազանց շատ փորձեր</h1>
            <p class="lead">Մուտքը ժամանակավորապես արգելափակված է։ Փորձիր մեկ րոպեից։</p>
            <p><a href="{{ route('home') }}" class="btn btn--primary">Գլխավոր էջ</a></p>
        </div>
    </div>
</section>
@endsection
