<?php

use Illuminate\Support\Facades\Route;


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


//Booking Routes
Route::get('/new-booking', function () {
    return view('frontend.new_booking');
})->name('new_booking');

Route::get('/booking-detail', function () {
    return view('frontend.booking_detail');
})->name('booking_detail');
