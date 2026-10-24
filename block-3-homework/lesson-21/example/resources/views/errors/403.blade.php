{{-- Laravel-ը ինքն է ընտրում errors/403.blade.php-ն — PHP Դաս 21
     $exception->getMessage()-ը ՉԵՆՔ ցույց տալիս․ այնտեղ կարող է լինել մոդելի անունը կամ անգլերեն տեքստ --}}
@extends('layouts.app')

@section('title', '403')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <p class="meta">Սխալ 403</p>
                <h1>Մուտքը փակ է</h1>
                <p class="lead">Այս էջը կամ գործողությունը հասանելի չէ քո դերի համար։</p>
                <p><a href="{{ route('home') }}" class="btn btn--primary">Դարակ</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
