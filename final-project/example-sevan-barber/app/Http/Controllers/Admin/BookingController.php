<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

/** Բոլոր ամրագրումները՝ ֆիլտրերով և էջավորմամբ — PHP Դաս 10, 20 */
class BookingController extends Controller
{
    public function index(Request $request)
    {
        $staff = User::staff()->get(['id', 'name']);

        // Ֆիլտրերը՝ սպիտակ ցուցակով կամ պարամետրով, ոչ մի փոփոխական SQL տեքստում
        $status = array_key_exists($request->query('status'), Booking::STATUSES) ? $request->query('status') : null;
        $staffId = $staff->contains('id', $request->integer('staff')) ? $request->integer('staff') : null;
        $date = preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $request->query('date')) ? $request->query('date') : null;
        $q = mb_substr(trim((string) $request->query('q')), 0, 60);

        $bookings = Booking::query()
            ->select('bookings.*')
            ->join('time_slots', 'time_slots.id', '=', 'bookings.time_slot_id')
            ->with(['user:id,name,email,phone', 'service', 'slot.staff:id,name'])
            ->when($status, fn ($query) => $query->where('bookings.status', $status))
            ->when($staffId, fn ($query) => $query->where('time_slots.staff_id', $staffId))
            ->when($date, fn ($query) => $query->whereDate('time_slots.starts_at', $date))
            ->when($q !== '', fn ($query) => $query->whereHas('user', fn ($u) => $u->where(fn ($w) => $w
                ->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")->orWhere('phone', 'like', "%{$q}%"))))
            ->orderByDesc('time_slots.starts_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.bookings.index', compact('bookings', 'staff', 'status', 'staffId', 'date', 'q'));
    }
}
