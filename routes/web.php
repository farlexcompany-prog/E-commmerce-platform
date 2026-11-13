<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminSectionController;
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
        Route::get('/sales/orders', [AdminOrderController::class, 'index'])->name('sales.orders');
        Route::get('/orders/export', [AdminOrderController::class, 'export'])->name('orders.export');
        Route::get('/pages', [AdminPageController::class, 'index'])->name('pages.index');
        Route::get('/pages/{pageKey}/edit', [AdminPageController::class, 'edit'])->where('pageKey', '[a-z0-9-]+')->name('pages.edit');
        Route::put('/pages/{pageKey}', [AdminPageController::class, 'update'])->where('pageKey', '[a-z0-9-]+')->name('pages.update');
        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/create', [AdminReviewController::class, 'create'])->name('reviews.create');
        Route::post('/reviews', [AdminReviewController::class, 'store'])->name('reviews.store');
        Route::get('/reviews/{review}/edit', [AdminReviewController::class, 'edit'])->name('reviews.edit');
        Route::put('/reviews/{review}', [AdminReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::get('/catalog/products', [AdminProductController::class, 'index'])->name('catalog.products');
        Route::get('/catalog/products/create', [AdminProductController::class, 'create'])->name('catalog.products.create');
        Route::post('/catalog/products', [AdminProductController::class, 'store'])->name('catalog.products.store');
        Route::get('/catalog/products/{product}/edit', [AdminProductController::class, 'edit'])->name('catalog.products.edit');
        Route::put('/catalog/products/{product}', [AdminProductController::class, 'update'])->name('catalog.products.update');
        Route::get('/sections/{section}', [AdminSectionController::class, 'show'])->name('sections.show');
        Route::get('/orders/{orderNumber}', [AdminOrderController::class, 'show'])
            ->where('orderNumber', '[A-Z0-9]+')
            ->name('orders.show');
    });
});
