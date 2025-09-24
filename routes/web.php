<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;
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
Route::post('/item/{item}/comment', [CommentController::class, 'store'])->middleware(['auth'])->name('comment.store');
Route::post('/item/{item}/like', [LikeController::class, 'toggle'])->middleware('auth')->name('item.like');

// 認証必須
Route::middleware(['auth', 'verified'])->group(function () {
    // マイリスト
    Route::get('/?tab=mylist', [ItemController::class, 'index'])->name('items.mylist');

    // 購入関係
    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::get('/purchase/address/{item}', [PurchaseController::class, 'editAddress'])->name('purchase.address.edit');
    Route::post('/purchase/address/{item}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');
    Route::post('/purchase/{item}/checkout', [PurchaseController::class, 'checkout'])->name('purchase.checkout');
    Route::get('/purchase/{item}/success', [PurchaseController::class, 'success'])->name('purchase.success');

    // 出品
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    // プロフィール画面
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/edit', [MypageController::class, 'edit'])->name('mypage.edit');
    Route::post('/mypage/edit', [MypageController::class, 'update']);
    Route::get('/mypage?page=buy', [MypageController::class, 'purchased'])->name('mypage.purchased');
    Route::get('/mypage?page=sell', [MypageController::class, 'exhibited'])->name('mypage.exhibited');
});

