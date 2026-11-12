<?php

use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StoreController::class, 'index'])->name('store');
Route::get('/cart', [StoreController::class, 'cart'])->name('cart.index');
Route::post('/cart/{product}', [StoreController::class, 'addToCart'])->name('cart.store');
Route::patch('/cart/{product}', [StoreController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/{product}', [StoreController::class, 'removeFromCart'])->name('cart.destroy');
Route::get('/checkout', [StoreController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [StoreController::class, 'placeOrder'])->name('checkout.store');
Route::get('/orders/{orderNumber}', [StoreController::class, 'showOrder'])->name('orders.show');
