<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LoginController;
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
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
});



// Route::get('login', [ShopController::class, 'login']);

// 

// Route::get('login/success', [ShopController::class, 'loginSuccess'])->name('login.success');

// Route::get('register', [ShopController::class, 'register']);

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/cart', [CartController::class, 'show']);

// require __DIR__.'/settings.php';
// require __DIR__.'/auth.php';
