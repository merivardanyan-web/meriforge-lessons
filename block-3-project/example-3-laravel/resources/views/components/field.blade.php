{{-- Ֆորմայի դաշտ՝ label, input, old(), @error, aria-invalid — մեկ տեղում (Դաս 6-ի inputClass/fieldError-ը) --}}
@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'hint' => null])

<div {{ $attributes->only('class')->class(['field']) }}>
    <label for="{{ $name }}" class="field__label">
        {{ $label }} @if ($required)<span class="req" aria-hidden="true">*</span>@endif
    </label>

    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ $value }}"
           {{ $attributes->except('class')->class(['input', 'input--error' => $errors->has($name)]) }}
           @if ($required) required @endif
           @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror>

    @error($name)
        <p class="field__error" id="{{ $name }}-error">{{ $message }}</p>
    @enderror

    @if ($hint)
        <span class="field__hint">{{ $hint }}</span>
    @endif
</div>
