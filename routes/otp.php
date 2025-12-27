<?php

use App\Http\Controllers\JSON\OTP\GetOtpTimerController;
use App\Http\Controllers\JSON\OTP\SendOtpController;
use App\Http\Controllers\JSON\OTP\VerifyOtpController;
use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Route;

Route::middleware(['prevent.back_history'])->group(function () {
    Route::middleware(['must.login', 'otp.check_config', 'otp.need_verify'])->group(function () {
        Route::get('/otp', [OtpController::class, 'index'])->name('otp.index');

        Route::prefix('/json')->name('otp.')->group(function () {
            Route::post('/send-top', SendOtpController::class)->name('send');
            Route::post('/verify-otp', VerifyOtpController::class)->name('verify');
            route::get('/otp-timer', GetOtpTimerController::class)->name('timer');
        });
    });
});
