<?php

use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\HealthCheckController;
use Illuminate\Support\Facades\Route;
//DIコンテナ練習用
use App\Http\Controllers\FileLoggerController;


Route::middleware('throttle:1000,1')->group(function () {

    Route::post('/signIn', [SignInController::class, 'signIn'])->name('signIn');
    Route::get('/signUp', [SignInController::class, 'signUp'])->name('signUp');
    Route::get('/health-check', [HealthCheckController::class, 'index'])->name('health_check');

    //DIコンテナ練習用
    Route::get('/log', [FileLoggerController::class, 'writeLog']);


});
