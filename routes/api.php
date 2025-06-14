<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PickupRequestController;
use App\Http\Controllers\ExchangeController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:api')->group(function () {
    Route::get('/donation', [DonationController::class, 'getDonationItems']);
    Route::get('/exchange', [ExchangeController::class, 'getExchangeItems']);
    Route::post('/deactivate', [AuthController::class, 'deactivateAccount']);
    Route::post('/logout', [AuthController::class, 'logout']); // tambahkan ini jika belum
    Route::post('/pickuprequest', [PickupRequestController::class, 'store']);
    Route::get('/history', [PickupRequestController::class, 'getPickupData']);
    Route::get('/home', [HomeController::class, 'getHomeData']);
    Route::post('/redeem', [ExchangeController::class, 'redeem']);
    Route::get('/exchange', [ExchangeController::class, 'index']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/login', [AuthController::class, 'index'])->name('login');
 

Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API berhasil diakses!'
    ]);
});








