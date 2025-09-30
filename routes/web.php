<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\profileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// 認証不要
Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');

// 認証必須
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/item/{item}/comment', [CommentController::class, 'store'])->name('comment.store');
    Route::post('/item/{item}/like', [LikeController::class, 'toggle'])->name('item.like');
    // 購入関係
    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/address/{item}', [PurchaseController::class, 'editAddress'])->name('purchase.address.edit');
    Route::post('/purchase/address/{item}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');
    Route::post('/purchase/{item}/address', [PurchaseController::class, 'updateAddress']);
    Route::post('/purchase/{item}/checkout', [PurchaseController::class, 'checkout'])->name('purchase.checkout');
    // 出品
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');
    // プロフィール画面
    Route::get('/profile', [profileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [profileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/edit', [profileController::class, 'update'])->name('profile.update');
    Route::get('/profile?page=buy', [profileController::class, 'purchased'])->name('profile.purchased');
    Route::get('/profile?page=sell', [profileController::class, 'exhibited'])->name('profile.exhibited');
});

