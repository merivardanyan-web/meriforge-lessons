{{-- Laravel-ը ինքն է ընտրում errors/404.blade.php-ն։ $exception->getMessage()-ը ցույց չենք տալիս --}}
@extends('layouts.app')

@section('title', '404')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <p class="meta">Սխալ 404</p>
            <h1>Էջը չի գտնվել</h1>
            <p class="lead">Ծառայությունը կամ էջը գոյություն չունի կամ թաքցված է։</p>
            <p><a href="{{ route('home') }}" class="btn btn--primary">Գլխավոր էջ</a></p>
        </div>
    </div>
</section>
@endsection
