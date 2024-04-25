<?php

use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;


// Public routes accessible without authentication

//Front End Routes

//Redirect to Home page as login is valid for user
Route::get('/login', function () {
    return view('frontend.index');
    // redirect('/');
})->name('login');

// Home Page
Route::get('/', [FrontendController::class, 'index'])->name('index');
Route::get('/index', [FrontendController::class, 'index'])->name('index');

// Service Page
Route::get('/services', [FrontendController::class, 'services'])->name('services');
// About Us Page
Route::get('/about-us', [FrontendController::class, 'about_us'])->name('about-us');
// Help Page
Route::get('/help', [FrontendController::class, 'help'])->name('help');
// Join Helper Page
Route::get('/join-helper', [FrontendController::class, 'join_helper'])->name('join_helper');

//Booking Routes
Route::get('/new-booking', [FrontendController::class, 'new_booking'])->name('new_booking');
Route::get('/fetch/service-categories', [FrontendController::class, 'fetch_services_categories'])->name('fetch.service.categories');


Route::get('/booking-detail', function () {
    return view('frontend.booking_detail');
})->name('booking_detail');


// Change Language Routes
Route::get('/change-language/{lang}', function ($lang) {
    // session(['applocale' => $lang]);
    session()->put('applocale', $lang);
    // app()->setLocale($lang);
    // dd(session('applocale'));
    session()->save();
    return back();
})->name('change-language');
