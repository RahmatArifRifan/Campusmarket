<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC ROUTES (Guest)
// ============================================================
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/katalog', [PublicController::class, 'catalog'])->name('catalog');

// ============================================================
// AUTH ROUTES
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ============================================================
// BUYER ROUTES
// ============================================================
Route::middleware(['auth', 'role:buyer'])->prefix('buyer')->name('buyer.')->group(function () {
    Route::get('/dashboard',          [BuyerController::class, 'dashboard'])->name('dashboard');
    Route::get('/shop',               [BuyerController::class, 'shop'])->name('shop');
    Route::get('/cart',               [BuyerController::class, 'cart'])->name('cart');
    Route::post('/cart/add',          [BuyerController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update',       [BuyerController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove',       [BuyerController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/clear',        [BuyerController::class, 'clearCart'])->name('cart.clear');
    Route::post('/cart/clear-store',  [BuyerController::class, 'clearStoreCart'])->name('cart.clear-store');
    Route::get('/checkout',           [BuyerController::class, 'checkout'])->name('checkout');
    Route::post('/checkout',          [BuyerController::class, 'processCheckout'])->name('checkout.process');
    Route::post('/checkout/bulk',     [BuyerController::class, 'processBulkCheckout'])->name('checkout.bulk');
    Route::get('/orders',             [BuyerController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}/qr',  [BuyerController::class, 'showQr'])->name('orders.qr');
});

// ============================================================
// SELLER ROUTES
// ============================================================
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard',                    [SellerController::class, 'dashboard'])->name('dashboard');
    Route::get('/products',                     [SellerController::class, 'products'])->name('products');
    Route::post('/products',                    [SellerController::class, 'storeProduct'])->name('products.store');
    Route::put('/products/{product}',           [SellerController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}',        [SellerController::class, 'destroyProduct'])->name('products.destroy');
    Route::get('/orders',                       [SellerController::class, 'orders'])->name('orders');
    Route::post('/orders/validate',             [SellerController::class, 'validateOrder'])->name('orders.validate');
    Route::post('/orders/{order}/complete',     [SellerController::class, 'completeOrder'])->name('orders.complete');
    Route::get('/store-profile',                [SellerController::class, 'storeProfile'])->name('store.profile');
    Route::post('/store-profile',               [SellerController::class, 'updateStoreProfile'])->name('store.profile.update');
});

// ============================================================
// ADMIN ROUTES
// ============================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',                    [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users',                        [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/toggle-ban',     [AdminController::class, 'toggleBanUser'])->name('users.toggle-ban');
    Route::get('/stores',                       [AdminController::class, 'stores'])->name('stores');
    Route::post('/stores/{store}/toggle-ban',   [AdminController::class, 'toggleBanStore'])->name('stores.toggle-ban');
    Route::get('/transactions',                 [AdminController::class, 'transactions'])->name('transactions');
    Route::post('/transactions/{order}/cancel', [AdminController::class, 'cancelOrder'])->name('transactions.cancel');
});
