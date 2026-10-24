{{-- Laravel-ը ինքն է ընտրում errors/403.blade.php-ն։ $exception->getMessage()-ը ցույց չենք տալիս --}}
@extends('layouts.app')

@section('title', '403')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <p class="meta">Սխալ 403</p>
            <h1>Մուտքը փակ է</h1>
            <p class="lead">Այս էջը կամ գործողությունը հասանելի չէ քո դերին։</p>
            <p><a href="{{ route('home') }}" class="btn btn--primary">Գլխավոր էջ</a></p>
        </div>
    </div>
</section>
@endsection
