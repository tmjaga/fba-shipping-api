<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FbaServiceController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/shipping/{buyer_id}/{order_id}', [FbaServiceController::class, 'fulfillOrder'])->name('fulfill-order');
});
