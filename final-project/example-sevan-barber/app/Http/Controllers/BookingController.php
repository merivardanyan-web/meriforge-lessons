<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookings)
    {
    }

    /** Իմ ամրագրումները՝ առաջիկա և անցած */
    public function index(Request $request)
    {
        $all = $request->user()->bookings()->with(['service', 'slot.staff'])->latest('id')->get();

        [$upcoming, $past] = $all->partition(fn (Booking $b) => $b->isBooked() && $b->slot->starts_at->isFuture());

        return view('bookings.index', [
            'upcoming' => $upcoming->sortBy(fn (Booking $b) => $b->slot->starts_at),
            'past'     => $past,
        ]);
    }

    public function store(BookingRequest $request)
    {
        $data = $request->validated();
        $service = Service::findOrFail($data['service_id']);
        $slot = TimeSlot::with('staff')->findOrFail($data['slot_id']);

        // ValidationException-ը (զբաղված, անցած, 3-ի սահմանը) Laravel-ը վերադարձնում է ֆորմային
        $this->bookings->book($request->user(), $slot, $service, $data['note'] ?? null);

        return redirect()->route('bookings.mine')->with('success', sprintf(
            'Ամրագրված է․ %s, %s, %s։ Սպասում ենք քեզ։',
            $service->name,
            $slot->starts_at->translatedFormat('l, j F H:i'),
            $slot->staff->name,
        ));
    }

    /** PATCH /bookings/{booking}/cancel․ ֆորմայով կամ fetch-ով (bookings.js) */
    public function cancel(Request $request, Booking $booking)
    {
        $booking->load('slot');
        $check = Gate::inspect('cancel', $booking);

        if ($check->denied()) {
            return $request->expectsJson()
                ? response()->json(['message' => $check->message()], 403)
                : back()->with('error', $check->message());
        }

        $this->bookings->cancel($booking);

        if ($request->expectsJson()) {
            return response()->json([
                'id'      => $booking->id,
                'status'  => $booking->status,
                'label'   => $booking->statusLabel(),
                'message' => 'Ամրագրումը չեղարկվեց, ժամը կրկին ազատ է։',
            ]);
        }

        return back()->with('success', 'Ամրագրումը չեղարկվեց, ժամը կրկին ազատ է։');
    }
}
