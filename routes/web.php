<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShoppingCartController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/cart', [ShoppingCartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [ShoppingCartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove', [ShoppingCartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [ShoppingCartController::class, 'checkout'])->name('cart.checkout');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
});



require __DIR__.'/auth.php';
