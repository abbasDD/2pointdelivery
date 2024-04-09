<?php

use Illuminate\Support\Facades\Route;


//Helper Routes

//Helper Auth Routes
Route::get('helper/login', 'App\Http\Controllers\Auth\LoginController@showLoginFormHelper')->name('helper.login');
Route::post('helper/login', 'App\Http\Controllers\Auth\LoginController@login')->name('helper.login');

Route::get('helper/register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationFormHelper')->name('helper.register');
Route::post('helper/register', 'App\Http\Controllers\Auth\RegisterController@register')->name('helper.register');

Route::get('/helper/complete-profile', [App\Http\Controllers\HelperController::class, 'complete_profile'])->name('helper.complete_profile');
Route::post('/helper/update-profile', [App\Http\Controllers\HelperController::class, 'update_profile'])->name('helper.update_profile');
Route::get('/helper', [App\Http\Controllers\HelperController::class, 'index'])->name('helper.index');
