<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

Route::get('/', [ProductController::class, 'index'])->name('products.index');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/item/{id}/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/item/{id}/qty', [CartController::class, 'updateQty'])->name('cart.updateQty');


