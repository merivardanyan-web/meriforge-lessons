<?php

// Կայքի քարտեզը — PHP Դաս 18
// Համեմատիր Դաս 15-ի routes.php-ի հետ․ նույն ուղիները, բայց ԱՆՈՒՆՆԵՐՈՎ (->name)

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookController::class, 'index'])->name('home');
Route::get('/genre/{slug}', [BookController::class, 'genre'])->name('genre');          // տնային 3
Route::get('/book/{slug}', [BookController::class, 'show'])->name('book.show');

// ⚠️ ՓՈՐՁՆԱԿԱՆ — տնային 6․ փորձից հետո ջնջիր ուղին և շաբլոնը
Route::get('/lab/escape', fn () => view('lab.escape', [
    'title' => '<img src=x onerror="alert(\'XSS\')">Սամվել',
]));
