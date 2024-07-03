<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
// 追加
Route::get("/register", "Auth\RegisterController@showRegistrationForm")->name('auth.register_form');
Route::post("/register", "Auth\RegisterController@register")->name('auth.register');