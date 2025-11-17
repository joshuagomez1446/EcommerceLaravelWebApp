<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserOrderController;

Route::get('/shop', [ProductController::class, 'shop'])->name('shop.index');
Route::post('/cart/add', [CartController::class, 'store'])->name('cart.store');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');



Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {
    // ... /dashboard, /products, /categories routes ...
    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

// Regular User Dashboard (default Breeze route)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('orders', [UserOrderController::class, 'ordersIndex'])->name('user.orders.index');
    Route::get('orders/{order}', [UserOrderController::class, 'showOrder'])->name('user.orders.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Checkout routes stay here (any logged-in user can check out)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});


// === ADMIN ROUTES ===
// We group all admin routes under 'auth' and our new 'admin' middleware.
// We also add a prefix '/admin' to all their URLs.
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    // Product Management
    // URL will be /admin/products
    Route::resource('products', ProductController::class);
    // Category Management
    // URL will be /admin/categories
    Route::resource('categories', CategoryController::class);
    // Order Management
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::patch('/orders/{order}/status', [
        AdminController::class,
        'updateOrderStatus'
    ])->name('orders.updateStatus');
});



require __DIR__ . '/auth.php';
