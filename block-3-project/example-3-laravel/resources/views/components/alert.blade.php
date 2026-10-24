{{-- <x-alert type="success">...</x-alert> — Դաս 19-ի տնային --}}
@props(['type' => 'success'])

<div {{ $attributes->class(['alert', 'alert--' . $type]) }} role="{{ $type === 'danger' ? 'alert' : 'status' }}">
    {{ $slot }}
</div>
