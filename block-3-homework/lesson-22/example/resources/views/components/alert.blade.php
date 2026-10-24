{{-- <x-alert type="success">Պահպանվեց</x-alert> — PHP Դաս 19, տնային 3 --}}
@props(['type' => 'success'])

<div class="row">
    <div class="col w-100">
        <div {{ $attributes->class(['alert', 'alert--' . $type, 'mt-20']) }} role="{{ $type === 'danger' ? 'alert' : 'status' }}">
            {{ $slot }}
        </div>
    </div>
</div>
