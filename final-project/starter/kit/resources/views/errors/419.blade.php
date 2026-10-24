{{-- Laravel-ը ինքն է ընտրում errors/419.blade.php-ն։ $exception->getMessage()-ը ցույց չենք տալիս --}}
@extends('layouts.app')

@section('title', '419')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <p class="meta">Սխալ 419</p>
            <h1>Էջը հնացել է</h1>
            <p class="lead">Ֆորման բաց էր շատ երկար։ Թարմացրու էջը և փորձիր կրկին։</p>
            <p><a href="{{ route('home') }}" class="btn btn--primary">Գլխավոր էջ</a></p>
        </div>
    </div>
</section>
@endsection
