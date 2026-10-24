<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Auth\Access\Response;

/**
 * Ով ինչ կարող է անել ամրագրման հետ։ Laravel-ը գտնում է այս դասը անունով (Booking → BookingPolicy)
 * Կոնտրոլերում՝ Gate::authorize('cancel', $booking), Blade-ում՝ @can('cancel', $booking)
 */
class BookingPolicy
{
    public function cancel(User $user, Booking $booking): Response
    {
        if (! $booking->isBooked()) {
            return Response::deny('Այս ամրագրումն արդեն փակված է։');
        }

        if ($user->isAdmin()) {
            return Response::allow();
        }

        if ($user->isStaff()) {
            return $booking->slot->staff_id === $user->id
                ? Response::allow()
                : Response::deny('Սա ուրիշ վարսավիրի ամրագրումն է։');
        }

        if ($booking->user_id !== $user->id) {
            return Response::deny('Սա քո ամրագրումը չէ։');
        }

        return $booking->slot->starts_at->gt(now()->addHours(BookingService::CANCEL_HOURS))
            ? Response::allow()
            : Response::deny('Չեղարկել կարելի է մինչև այցից ' . BookingService::CANCEL_HOURS . ' ժամ առաջ։ Զանգիր մեզ։');
    }

    /** «Կատարված»՝ միայն այցի օրը կամ հետո, և միայն իր (կամ ադմինը՝ ցանկացած) */
    public function complete(User $user, Booking $booking): bool
    {
        return $booking->isBooked()
            && $booking->slot->starts_at->lte(now()->endOfDay())
            && ($user->isAdmin() || ($user->isStaff() && $booking->slot->staff_id === $user->id));
    }
}
