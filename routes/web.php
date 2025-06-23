<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ShopController;

Route::get('/', [ShopController::class, 'login']);

Route::get('shop', [ShopController::class, 'index'])->name('shop.index');

Route::get('login', [ShopController::class, 'login']);

Route::post('login', [LoginController::class, 'authenticate']);

Route::get('login/success', [ShopController::class, 'loginSuccess'])->name('login.success');

Route::get('register', [ShopController::class, 'register']);

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/cart', [CartController::class, 'show']);

//require __DIR__.'/settings.php';
//require __DIR__.'/auth.php';
