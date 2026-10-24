<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Api\ServiceController as ApiServiceController;
use App\Http\Controllers\Api\SlotController as ApiSlotController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

// ---------- Հանրային ----------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');

// ---------- Հաճախորդ ----------
// Մուտք չգործածը կուղարկվի /login, իսկ հետո՝ հետ (redirect()->intended)
Route::middleware('auth')->group(function () {
    Route::get('/my/bookings', [BookingController::class, 'index'])->name('bookings.mine');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');     // can:book՝ BookingRequest-ում
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');   // BookingPolicy::cancel
});

// ---------- Վարսավիր և ադմին ----------
Route::middleware(['auth', 'can:staff-area'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/', [StaffController::class, 'index'])->name('index');
    Route::patch('/bookings/{booking}/done', [StaffController::class, 'complete'])->name('complete');
    Route::get('/slots', [SlotController::class, 'index'])->name('slots');
    Route::post('/slots', [SlotController::class, 'store'])->name('slots.store');
    Route::delete('/slots/{slot}', [SlotController::class, 'destroy'])->name('slots.destroy');
});

// ---------- Միայն ադմին ----------
Route::middleware(['auth', 'can:manage-services'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/services/trashed', [AdminServiceController::class, 'trashed'])->name('services.trashed');
    Route::patch('/services/{id}/restore', [AdminServiceController::class, 'restore'])->name('services.restore');
    Route::resource('services', AdminServiceController::class)->except('show');
});

// ---------- JSON (միայն կարդալու) ----------
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/services', [ApiServiceController::class, 'index'])->name('services');
    Route::get('/slots', [ApiSlotController::class, 'index'])->name('slots');
});
