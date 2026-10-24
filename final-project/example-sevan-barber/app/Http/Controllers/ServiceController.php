<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /** Ծառայության էջը և ազատ ժամերը․ առանց JS-ի աշխատում է GET ֆորմայով, JS-ով՝ /api/slots-ով */
    public function show(Request $request, Service $service)
    {
        abort_unless($service->is_active, 404);

        $days = TimeSlot::bookingDays();
        $date = array_key_exists($request->query('date'), $days) ? $request->query('date') : array_key_first($days);

        $staff = User::staff()->get(['id', 'name']);
        $staffId = $staff->contains('id', $request->integer('staff')) ? $request->integer('staff') : null;

        return view('services.show', [
            'service' => $service,
            'days'    => $days,
            'date'    => $date,
            'staff'   => $staff,
            'staffId' => $staffId,
            'slots'   => TimeSlot::freeFor($days[$date], $staffId),
        ]);
    }
}
