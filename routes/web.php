<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;

Route::get('/', [ShopController::class, 'login'])->name('login');

Route::get('login',  [ShopController::class, 'login'])->name('login');

Route::middleware('auth')->group(function () {
    Route::get('shop', [ShopController::class, 'index'])->name('shop.index');

    Route::post('cart', [CartController::class, 'createcart'])->name('cart.create');

    Route::get('cart', [CartController::class, 'cartIndex'])->name('cart.get');
});
