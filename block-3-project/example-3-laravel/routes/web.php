<?php

// Կայքի քարտեզը — PHP Դաս 18–21
// Համեմատիր Առաջադրանք 2-ի routes.php-ի հետ․ նույն ուղիները, բայց անուններով

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// ---------- Հանրային ----------
Route::get('/', [ProductController::class, 'home'])->name('home');
Route::get('/catalog', [ProductController::class, 'index'])->name('catalog');
Route::get('/category/{category:slug}', [ProductController::class, 'category'])->name('category');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.show');     // Դաս 20

Route::get('/contact', [ContactController::class, 'form'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::post('/theme', [ContactController::class, 'theme'])->name('theme');

// ---------- Զամբյուղ և պատվեր ----------
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/items', [CartController::class, 'store'])->name('cart.items.store');
Route::patch('/cart/items/{id}', [CartController::class, 'update'])->whereNumber('id')->name('cart.items.update');
Route::delete('/cart/items/{id}', [CartController::class, 'destroy'])->whereNumber('id')->name('cart.items.destroy');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/thanks/{order}', [CheckoutController::class, 'thanks'])->whereNumber('order')->name('checkout.thanks');

// ---------- Գնորդ — մուտքը և գրանցումը տալիս է Fortify-ը (/login, /register, /logout) ----------
Route::get('/account', [AccountController::class, 'index'])->middleware('auth')->name('account');

// ---------- Ադմին — Դաս 21 ----------
// Ուղու վրա՝ միայն աշխատակիցներ։ Ջնջումը՝ նաև Gate::authorize կոնտրոլերում ⭐
Route::middleware(['auth', 'can:manage-products'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', fn () => redirect()->route('admin.products.index'));

        Route::get('products/trashed', [AdminProductController::class, 'trashed'])->name('products.trashed');
        Route::patch('products/{product}/restore', [AdminProductController::class, 'restore'])
            ->withTrashed()                                     // ջնջվածը նույնպես գտնվում է ⭐ Դաս 20
            ->name('products.restore');
        Route::resource('products', AdminProductController::class)->except(['show']);

        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::patch('orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
    });

// ---------- JSON API — Դաս 17, 20 ----------
// web.php-ում, որ սեսիան և CSRF-ը նույնը լինեն, ինչ ֆորմաներում
Route::prefix('api')->name('api.')->group(function () {
    Route::get('products', [ApiProductController::class, 'index'])->name('products.index');
    Route::get('products/{product:slug}', [ApiProductController::class, 'show'])->name('products.show');
});
