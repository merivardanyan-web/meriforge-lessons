{{-- Ֆորմայի դաշտ form-grid-ում․ label + old() + @error + aria-invalid մեկ տեղում
     <x-field name="price" label="Գին, ֏" type="number" :value="$service->price" required /> --}}
@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'hint' => null])

<label for="{{ $name }}">{{ $label }}@if ($required) *@endif</label>
<div @class(['field', 'has-error' => $errors->has($name)])>
    @if ($type === 'textarea')
        <textarea id="{{ $name }}" name="{{ $name }}" {{ $attributes }}
            @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror>{{ old($name, $value) }}</textarea>
    @else
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}" {{ $attributes }}
            @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror>
    @endif
    @if ($hint)
        <small class="meta">{{ $hint }}</small>
    @endif
    @error($name)
        <small class="field__error" id="{{ $name }}-error">{{ $message }}</small>
    @enderror
</div>
