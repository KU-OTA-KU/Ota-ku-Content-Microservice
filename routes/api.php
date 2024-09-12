<?php

// routes/api.php Laravel routes/api.php
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Faq\Faq;
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
    Route::post('otp/generate', [OtpController::class, 'generateOtp']);
    Route::post('otp/verify', [OtpController::class, 'verifyOtp']);
});

// faq
Route::prefix('faq')->group(function () {
    Route::get('get/all', [Faq::class, 'getAll']);
    Route::get('get/{id}', [Faq::class, 'getById']);
});
