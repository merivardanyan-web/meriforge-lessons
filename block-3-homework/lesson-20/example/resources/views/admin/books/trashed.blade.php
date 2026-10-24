{{-- Ջնջվածներ — PHP Դաս 20, տնային 6 --}}
@extends('layouts.app')

@section('title', 'Ադմին · ջնջվածներ')

@section('content')
<section>
    <div class="row">
        <div class="col w-100">
            <h1 class="mt-20">Ջնջվածներ ({{ $books->count() }})</h1>
            <p><a href="{{ route('admin.books.index') }}" class="btn btn--ghost btn--sm">← Բոլոր գրքերը</a></p>

            @forelse ($books as $b)
                <div class="flex justify-between align-center gap-10 mt-10">
                    <span>{{ $b->title }} <span class="meta">· ջնջվել է {{ $b->deleted_at->diffForHumans() }}</span></span>
                    <form method="post" action="{{ route('admin.books.restore', $b) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn--outline btn--sm">Վերականգնել</button>
                    </form>
                </div>
            @empty
                <p class="empty">Ջնջված գիրք չկա։</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
