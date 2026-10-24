@extends('layouts.app')

@section('title', '403 — ' . config('app.name'))

@section('content')
<section class="status">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <p class="status__code" aria-hidden="true">403</p>
                <h1 class="mb-10">Մուտքն արգելված է</h1>
                {{-- $exception->getMessage()-ը ՉԵՆՔ ցույց տալիս․ այնտեղ կարող է լինել մոդելի անունը կամ անգլերեն տեքստ --}}
                <p class="text-muted mb-30">Այս էջը կամ գործողությունը հասանելի չէ քո դերի համար։</p>
                <div class="flex gap-10 flex-wrap justify-center">
                    <a href="{{ route('home') }}" class="btn btn--primary">Գլխավոր էջ</a>
                    <a href="{{ route('catalog') }}" class="btn btn--ghost">Կատալոգ</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
