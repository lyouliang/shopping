<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ShopController;

Route::get('/', [ShopController::class, 'login'])->name('login');

Route::post('login', [LoginController::class, 'authenticate']);

Route::get('login',  [ShopController::class, 'login'])->name('login');

Route::get('register', [RegisterController::class, 'index'])->name('register.index');

Route::post('register', [RegisterController::class, 'register'])->name('register');

Route::middleware('auth')->group(function () {
    Route::get('shop', [ShopController::class, 'index'])->name('shop.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart/add', [ShopController::class, 'index'])->name('shop.index');

    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/payment/index', [PaymentController::class, 'index'])->name('payment.index');
});
