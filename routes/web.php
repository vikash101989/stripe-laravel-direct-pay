<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ProductController::class, 'index'])->name('product.index');
Route::get('/{id}/product-buy', [ProductController::class, 'payment'])->name('product.payment');
Route::post('payment/process-payment/{string}/{price}', [ProductController::class, 'processPayment'])->name('processPayment');
Route::post('process-payment/{string}/{price}', [ProductController::class, 'stripeCheckout'])->name('stripeCheckout');
Route::get('checkout/success', [ProductController::class, 'checkoutSuccess'])->name('checkout.success');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
