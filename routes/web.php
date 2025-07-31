<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\MypageController;
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
Route::middleware(['auth'])->group(function () {
    // マイリスト
    Route::get('/?tab=mylist', [ItemController::class, 'index'])->name('items.mylist');

    // 購入関係
    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::post('/purchase/address/{item}', [PurchaseController::class, 'updateAddress'])->name('purchase.updateAddress');

    // 出品
    Route::get('/sell', [ExhibitionController::class, 'create'])->name('exhibition.create');

    // マイページ
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    Route::post('/mypage/profile', [MypageController::class, 'update'])->name('mypage.update');
    Route::get('/mypage', [MypageController::class, 'purchased'])->name('mypage.purchased')->where('page', 'buy');
    Route::get('/mypage', [MypageController::class, 'exhibited'])->name('mypage.exhibited')->where('page', 'sell');

    // プロフィール設定画面
    Route::get('/profile/setup', function () {
        return view('mypage.profile-setup'); // Bladeは仮でOK
    })->name('profile.setup');
});

