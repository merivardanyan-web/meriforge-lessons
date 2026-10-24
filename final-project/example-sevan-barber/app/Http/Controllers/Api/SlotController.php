<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SlotResource;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** GET /api/slots?date=2026-11-02&staff=2 — ազատ ժամերը slot-picker.js-ի համար */
class SlotController extends Controller
{
    public function index(Request $request)
    {
        $days = TimeSlot::bookingDays();

        // Accept: application/json-ի դեպքում սխալը՝ 422 JSON
        $data = $request->validate([
            'date'  => ['required', Rule::in(array_keys($days))],
            'staff' => ['nullable', 'integer', Rule::exists('users', 'id')->where('role', 'staff')],
        ], [], ['date' => 'օրը', 'staff' => 'վարսավիրը']);

        $slots = TimeSlot::freeFor($days[$data['date']], $data['staff'] ?? null);

        return SlotResource::collection($slots)->additional(['meta' => [
            'date'  => $data['date'],
            'label' => $days[$data['date']]->translatedFormat('l, j F'),
            'count' => $slots->count(),
        ]]);
    }
}
