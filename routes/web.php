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


//Helper Routes

//Helper Auth Routes
Route::get('helper/login', 'App\Http\Controllers\Auth\LoginController@showLoginFormHelper')->name('helper.login');
Route::post('helper/login', 'App\Http\Controllers\Auth\LoginController@login')->name('helper.login');

Route::get('helper/register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationFormHelper')->name('helper.register');
Route::post('helper/register', 'App\Http\Controllers\Auth\RegisterController@register')->name('helper.register');

Route::get('/helper/complete-profile', [App\Http\Controllers\HelperController::class, 'complete_profile'])->name('helper.complete_profile');
Route::post('/helper/update-profile', [App\Http\Controllers\HelperController::class, 'update_profile'])->name('helper.update_profile');
Route::get('/helper', [App\Http\Controllers\HelperController::class, 'index'])->name('helper.index');



// Public routes accessible without authentication

//Front End Routes

//Redirect to Home page as login is valid for user
Route::get('/login', function () {
    return view('frontend.index');
    // redirect('/');
})->name('login');

Route::get('/', function () {
    return view('frontend.index');
})->name('index');

Route::get('/index', function () {
    return view('frontend.index');
})->name('index');

Route::get('/services', function () {
    return view('frontend.services');
})->name('services');

Route::get('/about-us', function () {
    return view('frontend.about_us');
})->name('about-us');

Route::get('/help', function () {
    return view('frontend.help');
})->name('help');


Route::get('/join-helper', function () {
    return view('frontend.join_helper');
})->name('join_helper');
