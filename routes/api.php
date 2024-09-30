<?php

// routes/api.php Laravel routes/api.php
use App\Http\Controllers\Subscriptions\SubscriptionsController;
use App\Http\Controllers\Faq\FaqController;
use App\Http\Controllers\Advantages\advantagesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// auth
Route::prefix('auth')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::prefix('otp')->group(function () {
        Route::post('generate', [OtpController::class, 'generateOtp']);
        Route::post('verify', [OtpController::class, 'verifyOtp']);
    });
});

// advantages
Route::prefix('advantages')->group(function () {
    Route::get('/', [advantagesController::class, 'getAll']);
    Route::get('id/{id}', [advantagesController::class, 'getById']);
});

// faq
Route::prefix('faq')->group(function () {
    Route::get('/', [FaqController::class, 'getAll']);
    Route::get('id/{id}', [FaqController::class, 'getById']);
});

// subscription
Route::prefix('subscriptions')->group(function () {
    Route::get('/', [SubscriptionsController::class, 'getAll']);
    Route::get('id/{id}', [SubscriptionsController::class, 'getById']);
});
