@extends('layouts.app')

@section('title', '404 — ' . config('app.name'))

@section('content')
<section class="status">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <p class="status__code" aria-hidden="true">404</p>
                <h1 class="mb-10">Էջը չի գտնվել</h1>
                {{-- $exception->getMessage()-ը ՉԵՆՔ ցույց տալիս․ այնտեղ կարող է լինել մոդելի անունը կամ անգլերեն տեքստ --}}
                <p class="text-muted mb-30">Հնարավոր է՝ հղումը սխալ է, կամ ապրանքն այլևս չի վաճառվում։</p>
                <div class="flex gap-10 flex-wrap justify-center">
                    <a href="{{ route('home') }}" class="btn btn--primary">Գլխավոր էջ</a>
                    <a href="{{ route('catalog') }}" class="btn btn--ghost">Կատալոգ</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
