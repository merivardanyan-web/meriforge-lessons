{{-- Ֆորմայի դաշտ form-grid-ում․ label + .field՝ old(), @error, aria-invalid մեկ տեղում — PHP Դաս 21
     Դաս 11-ի $has, $aria, $msg փոքր ֆունկցիաների փոխարեն --}}
@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'hint' => null])

<label for="{{ $name }}">{{ $label }}@if ($required) *@endif</label>
<div @class(['field', 'has-error' => $errors->has($name)])>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}"
           {{ $attributes }}
           @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror>
    @if ($hint)
        <small class="meta">{{ $hint }}</small>
    @endif
    @error($name)
        <small class="field__error" id="{{ $name }}-error">{{ $message }}</small>
    @enderror
</div>
