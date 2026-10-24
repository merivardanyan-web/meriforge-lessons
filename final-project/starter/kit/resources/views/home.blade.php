{{-- Ժամանակավոր գլխավոր էջ․ փոխարինիր քոնով առաջին շաբաթում --}}
@extends('layouts.app')

@section('content')
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <h1>{{ config('app.name') }}</h1>
            <p class="lead">Layout-ը, CSS-ը և սխալների էջերը պատրաստ են։ Հիմա՝ միգրացիաները (Ստուգակետ 1)։</p>
            <a href="#" class="btn btn--primary">Հիմնական գործողություն</a>
            <a href="#" class="btn btn--outline">Երկրորդական</a>
        </div>
    </div>
</section>
<section>
    <div class="row">
        <div class="col w-100">
            <form class="box" novalidate>
                <div class="form-grid">
                    <x-field name="example" label="Օրինակ դաշտ" required hint="x-field կոմպոնենտ՝ old(), @error, aria-invalid" />
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
