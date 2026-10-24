{{-- Laravel-ը ինքն է ընտրում errors/404.blade.php-ն — PHP Դաս 21
     $exception->getMessage()-ը ՉԵՆՔ ցույց տալիս․ այնտեղ կարող է լինել մոդելի անունը կամ անգլերեն տեքստ --}}
@extends('layouts.app')

@section('title', '404')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <p class="meta">Սխալ 404</p>
                <h1>Էջը չի գտնվել</h1>
                <p class="lead">Գիրքը կամ էջը գոյություն չունի կամ թաքցված է։</p>
                <p><a href="{{ route('home') }}" class="btn btn--primary">Դարակ</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
