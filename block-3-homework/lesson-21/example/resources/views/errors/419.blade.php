{{-- Laravel-ը ինքն է ընտրում errors/419.blade.php-ն — PHP Դաս 21
     $exception->getMessage()-ը ՉԵՆՔ ցույց տալիս․ այնտեղ կարող է լինել մոդելի անունը կամ անգլերեն տեքստ --}}
@extends('layouts.app')

@section('title', '419')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <p class="meta">Սխալ 419</p>
                <h1>Էջը հնացել է</h1>
                <p class="lead">Ֆորման ժամկետանց է (CSRF token)։ Թարմացրու էջը և փորձիր կրկին։</p>
                <p><a href="{{ route('home') }}" class="btn btn--primary">Դարակ</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
