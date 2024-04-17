<?php

use Illuminate\Support\Facades\Route;


//Client Routes

//Client Auth Routes
Route::get('client/login', 'App\Http\Controllers\Auth\LoginController@showLoginFormClient')->name('client.login');
Route::post('client/login', 'App\Http\Controllers\Auth\LoginController@login')->name('client.login');

Route::get('client/register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationFormClient')->name('client.register');
Route::post('client/register', 'App\Http\Controllers\Auth\RegisterController@register')->name('client.register');

Route::post('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Route::get('/client/complete-profile', [App\Http\Controllers\ClientController::class, 'complete_profile'])->name('client.complete_profile');
Route::post('/client/update-profile', [App\Http\Controllers\ClientController::class, 'update_profile'])->name('client.update_profile');
Route::get('/client', [App\Http\Controllers\ClientController::class, 'index'])->name('client.index');

//KYC Details
Route::get('/client/kyc-details', [App\Http\Controllers\ClientController::class, 'kyc_details'])->name('client.kyc_details');

//Orders
Route::get('/client/orders', [App\Http\Controllers\ClientController::class, 'orders'])->name('client.orders');

//Invoices
Route::get('/client/invoices', [App\Http\Controllers\ClientController::class, 'invoices'])->name('client.invoices');

//referrals
Route::get('/client/referrals', [App\Http\Controllers\ClientController::class, 'referrals'])->name('client.referrals');

//settings
Route::get('/client/settings', [App\Http\Controllers\ClientController::class, 'settings'])->name('client.settings');

//Edit Profile
Route::get('/client/edit', [App\Http\Controllers\ClientController::class, 'edit_profile'])->name('client.edit');

//Track Order
Route::get('/client/track-order', [App\Http\Controllers\ClientController::class, 'track_order'])->name('client.trackOrder');
