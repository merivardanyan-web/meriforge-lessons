<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * ⭐ «Դժվար ֆունկցիան»․ ամրագրում առանց կրկնակի ամրագրման
 *
 * Միամիտ տարբերակը՝ «ստուգիր, որ ժամն ազատ է, ապա ավելացրու», ունի պահ երկու տողի միջև,
 * երբ երկրորդ հարցումը նույնպես տեսնում է ժամը ազատ։ Այստեղ պաշտպանությունը բազայում է՝
 * bookings.active_slot_id UNIQUE (միգրացիան)։ Երկրորդ INSERT-ը բազան մերժում է, մենք բռնում ենք
 * սխալը և ցույց տալիս հասկանալի հաղորդագրություն։
 */
class BookingService
{
    public const MAX_ACTIVE = 3;        // մեկ հաճախորդին՝ առավելագույնը 3 ակտիվ ամրագրում
    public const CANCEL_HOURS = 2;      // հաճախորդը չեղարկում է առնվազն 2 ժամ առաջ

    public function book(User $client, TimeSlot $slot, Service $service, ?string $note = null): Booking
    {
        if ($slot->starts_at->isPast()) {
            throw ValidationException::withMessages(['slot_id' => 'Այս ժամն արդեն անցել է։ Ընտրիր ուրիշը։']);
        }

        return DB::transaction(function () use ($client, $slot, $service, $note) {
            // Հաճախորդի տողի կողպում․ նույն հաճախորդի երկու միաժամանակյա հարցումները հերթով են հաշվում
            // ակտիվ ամրագրումները, ուստի 3-ի սահմանը չի շրջանցվի երկու ներդիրով — PHP Դաս 11
            User::whereKey($client->id)->lockForUpdate()->first();

            if ($client->bookings()->upcoming()->count() >= self::MAX_ACTIVE) {
                throw ValidationException::withMessages([
                    'slot_id' => 'Կարող ես ունենալ առավելագույնը ' . self::MAX_ACTIVE . ' ակտիվ ամրագրում։ Չեղարկիր մեկը կամ սպասիր այցին։',
                ]);
            }

            $booking = new Booking();
            $booking->user_id = $client->id;
            $booking->time_slot_id = $slot->id;
            $booking->active_slot_id = $slot->id;          // ⭐ UNIQUE
            $booking->service_id = $service->id;
            $booking->price = $service->price;             // գինը այս պահին․ հետո փոխվելը չի ազդի
            $booking->note = $note;
            $booking->status = 'booked';

            try {
                $booking->save();
            } catch (UniqueConstraintViolationException) {
                // Ինչ-որ մեկը մեզնից մի քանի միլիվայրկյան առաջ ամրագրեց նույն ժամը
                throw ValidationException::withMessages(['slot_id' => 'Այս ժամը հենց նոր զբաղեցրեցին։ Ընտրիր ուրիշը։']);
            }

            return $booking;
        });
    }

    /** Չեղարկում․ active_slot_id = NULL, և ժամը կրկին ազատ է։ Ով կարող է՝ BookingPolicy::cancel */
    public function cancel(Booking $booking): void
    {
        $booking->status = 'cancelled';
        $booking->active_slot_id = null;
        $booking->cancelled_at = now();
        $booking->save();
    }

    /** Կատարված․ ժամը մնում է զբաղված (active_slot_id-ը չի փոխվում) */
    public function complete(Booking $booking): void
    {
        $booking->status = 'done';
        $booking->save();
    }
}
