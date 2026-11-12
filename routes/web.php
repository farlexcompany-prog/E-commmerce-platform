<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StoreController::class, 'index'])->name('store');
Route::get('/shop', [StoreController::class, 'shop'])->name('shop');
Route::get('/products/{product:slug}', [StoreController::class, 'showProduct'])->name('products.show');
Route::get('/search/suggestions', [StoreController::class, 'suggestions'])->middleware('throttle:30,1')->name('search.suggestions');
Route::get('/cart', [StoreController::class, 'cart'])->name('cart.index');
Route::post('/cart/{product}', [StoreController::class, 'addToCart'])->name('cart.store');
Route::patch('/cart/{product}', [StoreController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/{product}', [StoreController::class, 'removeFromCart'])->name('cart.destroy');
Route::post('/coupon', [StoreController::class, 'applyCoupon'])->name('coupon.apply');
Route::delete('/coupon', [StoreController::class, 'removeCoupon'])->name('coupon.destroy');
Route::post('/wishlist/{product}', [StoreController::class, 'toggleWishlist'])->name('wishlist.toggle');
Route::get('/wishlist', [StoreController::class, 'wishlist'])->name('wishlist.index');
Route::get('/checkout', [StoreController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [StoreController::class, 'placeOrder'])->name('checkout.store');
Route::get('/orders/{orderNumber}', [StoreController::class, 'showOrder'])->name('orders.show');
Route::post('/newsletter', [StoreController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/login', [StoreController::class, 'login'])->name('login');
Route::post('/login/otp', [StoreController::class, 'requestOtp'])->middleware('throttle:5,1')->name('login.otp');
Route::get('/account', [StoreController::class, 'account'])->name('account');
Route::get('/health-articles', [StoreController::class, 'articles'])->name('articles');
Route::get('/policies/{policy}', [StoreController::class, 'policy'])->whereIn('policy', ['privacy', 'terms', 'shipping', 'refund'])->name('policies.show');

Route::redirect('/admin', '/admin/dashboard')->name('admin.home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');

    Route::middleware(EnsureAdmin::class)->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminOrderController::class, 'index'])->name('dashboard');
        Route::get('/orders/{orderNumber}', [AdminOrderController::class, 'show'])
            ->where('orderNumber', '[A-Z0-9]+')
            ->name('orders.show');
    });
});
