<?php

use Illuminate\Support\Facades\Route;


//Helper Routes

//Helper Auth Routes
Route::get('helper/login', 'App\Http\Controllers\Auth\LoginController@showLoginFormHelper')->name('helper.login');
Route::post('helper/login', 'App\Http\Controllers\Auth\LoginController@postHelperLoginForm')->name('helper.login');

Route::get('helper/register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationFormHelper')->name('helper.register');
Route::post('helper/register', 'App\Http\Controllers\Auth\RegisterController@register')->name('helper.register');

Route::get('/helper/complete-profile', [App\Http\Controllers\HelperController::class, 'complete_profile'])->name('helper.complete_profile');
Route::post('/helper/update-profile', [App\Http\Controllers\HelperController::class, 'update_profile'])->name('helper.update_profile');
Route::get('/helper', [App\Http\Controllers\HelperController::class, 'index'])->name('helper.index');

//KYC Details
Route::get('/helper/kyc-details', [App\Http\Controllers\HelperController::class, 'kyc_details'])->name('helper.kyc_details');

//Bookings
Route::get('/helper/bookings', [App\Http\Controllers\HelperController::class, 'bookings'])->name('helper.bookings');

//settings
Route::get('/helper/settings', [App\Http\Controllers\HelperController::class, 'settings'])->name('helper.settings');

//Edit Profile
Route::get('/helper/edit', [App\Http\Controllers\HelperController::class, 'edit_profile'])->name('helper.edit');

//Teams
Route::get('/helper/teams', [App\Http\Controllers\HelperController::class, 'teams'])->name('helper.teams');

//Track Order
Route::get('/helper/track-order', [App\Http\Controllers\HelperController::class, 'track_order'])->name('helper.trackOrder');
