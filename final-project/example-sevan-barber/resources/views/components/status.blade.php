{{-- <x-status :booking="$b" />․ data-status՝ որ bookings.js-ը կարողանա փոխել տեքստը --}}
@props(['booking'])

<span class="status status--{{ $booking->status }}" data-status>{{ $booking->statusLabel() }}</span>
