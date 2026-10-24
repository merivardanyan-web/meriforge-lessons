<?php

// Կայքի քարտեզը — PHP Դաս 18, 19, 20

use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Api\BookController as ApiBookController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ListController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookController::class, 'index'])->name('home');

// Route model binding․ {genre:slug} → Genre::where('slug', ...)->firstOrFail() (տնային 4)
Route::get('/genre/{genre:slug}', [BookController::class, 'genre'])->name('genre');
Route::get('/book/{book:slug}', [BookController::class, 'show'])->name('book.show');

Route::get('/list', [ListController::class, 'index'])->name('list.index');
Route::post('/list/items', [ListController::class, 'store'])->name('list.items.store');
Route::delete('/list/items/{id}', [ListController::class, 'destroy'])->whereNumber('id')->name('list.items.destroy');

// ---------- Ադմին (Դաս 21-ում՝ auth և can) ----------
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('books/trashed', [AdminBookController::class, 'trashed'])->name('books.trashed');          // տնային 6
    Route::patch('books/{book}/restore', [AdminBookController::class, 'restore'])
        ->withTrashed()                                       // ջնջվածը նույնպես գտնվում է ⭐
        ->name('books.restore');
    Route::resource('books', AdminBookController::class)->except(['show']);
});

// ---------- JSON API — տնային 7 ----------
// web.php-ում, որ սեսիան և CSRF-ը նույնը լինեն, ինչ ֆորմաներում
Route::prefix('api')->name('api.')->group(function () {
    Route::get('books', [ApiBookController::class, 'index'])->name('books.index');
    Route::get('books/{book:slug}', [ApiBookController::class, 'show'])->name('books.show');
});
