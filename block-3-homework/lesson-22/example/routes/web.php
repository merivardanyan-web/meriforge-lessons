<?php

// Կայքի քարտեզը — PHP Դաս 18–22
// /login, /register, /logout՝ Fortify-ից (php artisan route:list --except-vendor-ը դրանք չի ցույց տա)

use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Api\BookController as ApiBookController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ReadingController;
use App\Http\Controllers\SuggestController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookController::class, 'index'])->name('home');
Route::get('/genre/{genre:slug}', [BookController::class, 'genre'])->name('genre');
Route::get('/book/{book:slug}', [BookController::class, 'show'])->name('book.show');

// Դաս 22 · «What I added»․ գնահատել կարող է միայն մուտք գործած անդամը
Route::post('/book/{book:slug}/rating', [RatingController::class, 'store'])->middleware('auth')->name('book.rate');

Route::get('/list', [ListController::class, 'index'])->name('list.index');
Route::post('/list/items', [ListController::class, 'store'])->name('list.items.store');
Route::delete('/list/items/{id}', [ListController::class, 'destroy'])->whereNumber('id')->name('list.items.destroy');

// ---------- Առաջարկիր գիրք — տնային 2 ----------
Route::get('/suggest', [SuggestController::class, 'create'])->name('suggest');
Route::post('/suggest', [SuggestController::class, 'store'])->name('suggest.store');

// ---------- Ադմին — Դաս 21 ----------
// Ուղու վրա՝ միայն admin և editor։ Ջնջումը՝ նաև Gate::authorize կոնտրոլերում ⭐
// Դաս 16-ի public/index.php-ի «/admin…» ստուգումը՝ middleware-ով
Route::middleware(['auth', 'can:manage-books'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', fn () => redirect()->route('admin.books.index'));

        Route::get('books/trashed', [AdminBookController::class, 'trashed'])->name('books.trashed');
        Route::patch('books/{book}/restore', [AdminBookController::class, 'restore'])->withTrashed()->name('books.restore');
        Route::resource('books', AdminBookController::class)->except(['show']);

        // Դաս 16-ի /admin/book/{slug}/read-ը
        Route::post('books/{book}/read', [ReadingController::class, 'store'])->name('books.read');
    });

// ---------- JSON API ----------
Route::prefix('api')->name('api.')->group(function () {
    Route::get('books', [ApiBookController::class, 'index'])->name('books.index');
    Route::get('books/{book:slug}', [ApiBookController::class, 'show'])->name('books.show');
});
