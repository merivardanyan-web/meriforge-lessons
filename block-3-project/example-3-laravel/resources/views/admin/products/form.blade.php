@extends('layouts.admin')

@section('title', $product ? 'Խմբագրում' : 'Նոր ապրանք')

@section('content')
<section>
    <div class="row">
        <div class="col w-60 w-md-100">
            <h1 class="mb-20 mt-20">{{ $product ? 'Խմբագրում՝ ' . $product->name : 'Նոր ապրանք' }}</h1>

            @if ($errors->any())
                <x-alert type="danger">Ուղղեք {{ $errors->count() }} սխալ և փորձեք կրկին։</x-alert>
            @endif

            <form method="post" enctype="multipart/form-data" novalidate class="form-section"
                  action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}">
                @csrf
                @if ($product)
                    @method('PUT')          {{-- ⭐ միակ տարբերությունը — Դաս 19 --}}
                @endif

                <div class="form-grid">
                    {{-- Սխալից հետո՝ օգտագործողի գրածը, առաջին բացմանը՝ բազայի արժեքը — Դաս 21 --}}
                    <x-field name="name" label="Անվանում" required :value="old('name', $product?->name)" maxlength="200" class="field--full" />

                    <div class="field">
                        <label for="category_id" class="field__label">Կատեգորիա</label>
                        <select id="category_id" name="category_id" @class(['input', 'input--error' => $errors->has('category_id')])>
                            <option value="">— առանց կատեգորիայի —</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}" @selected((int) old('category_id', $product?->category_id) === $c->id)>{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="field__error">{{ $message }}</p> @enderror
                    </div>

                    <x-field name="specs" label="Համառոտ (քարտի վրա)" :value="old('specs', $product?->specs)" maxlength="255" />
                    <x-field name="price" label="Գին, ֏" type="number" required :value="old('price', $product?->price)" min="1" />
                    <x-field name="discount" label="Զեղչ, %" type="number" :value="old('discount', $product?->discount ?? 0)" min="0" max="90" />
                    <x-field name="stock" label="Պահեստում, հատ" type="number" :value="old('stock', $product?->stock ?? 0)" min="0" />

                    <div class="field">
                        <label for="type" class="field__label">Տեսակ</label>
                        <select id="type" name="type" class="input">
                            <option value="physical" @selected(old('type', $product?->type ?? 'physical') === 'physical')>Ֆիզիկական</option>
                            <option value="digital" @selected(old('type', $product?->type) === 'digital')>Թվային (լիցենզիա)</option>
                        </select>
                    </div>

                    <x-field name="weight" label="Քաշ, գրամ" type="number" :value="old('weight', $product?->weight ?? 0)" min="0" />

                    <div class="field">
                        <label for="image" class="field__label">Նկար (JPG, PNG, WebP, մինչև 2 ՄԲ)</label>
                        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" @class(['input', 'input--error' => $errors->has('image')])>
                        @error('image') <p class="field__error">{{ $message }}</p> @enderror
                        @if ($product?->image)
                            <span class="field__hint">Ընթացիկը՝ {{ $product->image }}</span>
                        @endif
                    </div>

                    <div class="field field--full">
                        <label for="description" class="field__label">Նկարագրություն</label>
                        <textarea id="description" name="description" rows="5" maxlength="5000" @class(['input', 'input--error' => $errors->has('description')])>{{ old('description', $product?->description) }}</textarea>
                        @error('description') <p class="field__error">{{ $message }}</p> @enderror
                    </div>

                    <div class="field field--full">
                        <label class="check">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product?->is_active ?? true))>
                            <span>Ակտիվ (երևում է կայքում)</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-10 flex-wrap mt-20">
                    <button class="btn btn--primary">{{ $product ? 'Պահպանել' : 'Ավելացնել' }}</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn--ghost">Չեղարկել</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
