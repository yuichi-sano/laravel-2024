<?php

use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProjectSettingExcludedIpController;
use App\Http\Controllers\ProjectSettingExcludedKeywordController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\ProjectSettingExcludedIp;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:1000,1')->group(function () {

    Route::post('/signIn', [SignInController::class, 'signIn'])->name('signIn');
    Route::get('/signUp', [SignInController::class, 'signUp'])->name('signUp');
    Route::get('/health-check', [HealthCheckController::class, 'index'])->name('health_check');


    // Route::middleware('auth:api')->group(function () {
        // companies
        Route::prefix('companies')->group(function () {
            Route::get('/', [CompanyController::class, 'index']);
            Route::get('/sort', [CompanyController::class, 'sort']);
            Route::get('/{id}', [CompanyController::class, 'show']);
            Route::post('/register', [CompanyController::class, 'register']);
            Route::put('/{id}/update', [CompanyController::class, 'update']);
            Route::delete('/{id}/delete', [CompanyController::class, 'delete']);
        });

        // projectSetting-excludedIps
        Route::prefix('projectSetting-excludedIps')->group(function () {
            Route::get('/', [ProjectSettingExcludedIpController::class, 'index']);
            Route::get('/{id}', [ProjectSettingExcludedIpController::class, 'show']);
            Route::delete('/{id}/delete', [ProjectSettingExcludedIpController::class, 'delete']);
        });

        // projectSetting-excludedKeywords
        Route::prefix('projectSetting-excludedKeywords')->group(function () {
            Route::get('/', [ProjectSettingExcludedKeywordController::class, 'index']);
            Route::get('/{id}', [ProjectSettingExcludedKeywordController::class, 'show']);
            Route::delete('/{id}/delete', [ProjectSettingExcludedKeywordController::class, 'delete']);

        });

        // roles
        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index']);
            Route::get('/sort', [RoleController::class, 'sort']);
            Route::get('/{id}', [RoleController::class, 'show']);
            Route::post('/register', [RoleController::class, 'register']);
            Route::put('/{id}/update', [RoleController::class, 'update']);
            Route::delete('/{id}/delete', [RoleController::class, 'delete']);
        });
        // users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    // });
});
