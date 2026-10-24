<?php

namespace App\Http\Controllers;

use App\Http\Requests\SlotRequest;
use App\Models\TimeSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/** Աշխատանքային ժամեր․ վարսավիրը՝ իրենը, ադմինը՝ ցանկացածինը */
class SlotController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $staff = User::staff()->get(['id', 'name']);
        $staffId = $user->isStaff() ? $user->id : ($staff->contains('id', $request->integer('staff')) ? $request->integer('staff') : $staff->first()?->id);

        $date = (string) $request->query('date');
        $day = preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) && strtotime($date) ? Carbon::parse($date) : today();

        $slots = TimeSlot::where('staff_id', $staffId)
            ->onDate($day)
            ->with(['activeBooking.user:id,name', 'activeBooking.service'])
            ->withCount('bookings')
            ->orderBy('starts_at')
            ->get();

        return view('staff.slots', compact('staff', 'staffId', 'day', 'slots'));
    }

    public function store(SlotRequest $request)
    {
        $data = $request->validated();
        $staffId = $request->user()->isStaff() ? $request->user()->id : (int) $data['staff_id'];
        $day = Carbon::parse($data['date']);

        $rows = [];
        for ($h = (int) $data['from']; $h < (int) $data['to']; $h++) {
            $startsAt = $day->copy()->setTime($h, 0);
            if ($startsAt->isFuture()) {
                $rows[] = ['staff_id' => $staffId, 'starts_at' => $startsAt, 'created_at' => now(), 'updated_at' => now()];
            }
        }

        // UNIQUE(staff_id, starts_at)․ արդեն եղած ժամերը բազան լուռ բաց է թողնում
        $added = $rows ? TimeSlot::insertOrIgnore($rows) : 0;
        $skipped = count($rows) - $added;

        return redirect()
            ->route('staff.slots', ['date' => $day->toDateString(), 'staff' => $staffId])
            ->with('success', "Ավելացվեց {$added} ժամ" . ($skipped ? ", {$skipped}-ն արդեն կար" : '') . '։');
    }

    public function destroy(Request $request, TimeSlot $slot)
    {
        $user = $request->user();
        abort_unless($user->isAdmin() || $slot->staff_id === $user->id, 403);

        // FK restrict-ը միևնույն է թույլ չէր տա․ բայց հաղորդագրությունը պիտի լինի հասկանալի, ոչ 500
        if ($slot->bookings()->exists()) {
            return back()->with('error', 'Այս ժամն ունի ամրագրումներ (նաև չեղարկված)․ այն չի ջնջվում, որ պատմությունը մնա։');
        }

        $slot->delete();

        return back()->with('success', 'Ժամը հեռացվեց։');
    }
}
