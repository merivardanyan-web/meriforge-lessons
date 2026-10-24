<?php

// Կայքի քարտեզը — PHP Դաս 18, 19

use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ListController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookController::class, 'index'])->name('home');
Route::get('/genre/{slug}', [BookController::class, 'genre'])->name('genre');
Route::get('/book/{slug}', [BookController::class, 'show'])->name('book.show');

// ---------- Կարդալու ցուցակ — Դաս 19 ----------
// Դաս 15-ի /list/add և /list/remove-ը՝ REST ձևով․ POST՝ ավելացնել, DELETE՝ հանել
Route::get('/list', [ListController::class, 'index'])->name('list.index');
Route::post('/list/items', [ListController::class, 'store'])->name('list.items.store');
Route::delete('/list/items/{id}', [ListController::class, 'destroy'])->whereNumber('id')->name('list.items.destroy');

// ---------- Ադմին — Դաս 19 (Դաս 21-ում՝ auth և can) ----------
// Մեկ տող՝ յոթ ուղի (index, create, store, edit, update, destroy․ show-ը չենք օգտագործում)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('books', AdminBookController::class)->except(['show']);
});
