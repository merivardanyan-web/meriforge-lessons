<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TimeSlot;
use App\Models\User;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/** Վարսավիրի օրը․ վարսավիրը տեսնում է միայն իր ամրագրումները, ադմինը՝ բոլորինը */
class StaffController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $day = $this->day($request);
        $staff = User::staff()->get(['id', 'name']);
        $staffId = $user->isStaff() ? $user->id : ($staff->contains('id', $request->integer('staff')) ? $request->integer('staff') : null);

        $bookings = Booking::with(['user:id,name,phone', 'service', 'slot.staff:id,name'])
            ->whereHas('slot', fn ($q) => $q->onDate($day)->when($staffId, fn ($q) => $q->where('staff_id', $staffId)))
            ->get()
            ->sortBy(fn (Booking $b) => $b->slot->starts_at->timestamp * 1000 + $b->id);

        $free = TimeSlot::free()->onDate($day)->when($staffId, fn ($q) => $q->where('staff_id', $staffId))->count();

        return view('staff.index', compact('day', 'staff', 'staffId', 'bookings', 'free'));
    }

    public function complete(Booking $booking, BookingService $service)
    {
        $booking->load('slot');
        Gate::authorize('complete', $booking);

        $service->complete($booking);

        return back()->with('success', 'Նշված է որպես կատարված։');
    }

    /** ?date=2026-11-02, սխալ արժեքի դեպքում՝ այսօր */
    private function day(Request $request): Carbon
    {
        $date = (string) $request->query('date');

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) && strtotime($date) ? Carbon::parse($date) : today();
    }
}
