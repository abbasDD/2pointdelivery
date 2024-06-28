<?php

use App\Http\Controllers\Api\Client\ClientBookingController;
use App\Http\Controllers\Api\Client\ClientController;
use App\Http\Controllers\Api\Helper\HelperBookingController;
use App\Http\Controllers\Api\Helper\HelperController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PassportAuthController;


// ---------------- PASSPORT AUTH ROUTES ---------------- //

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);

// ---------------- CLIENT ROUTES START ---------------- //


// Group API Routes with middleware auth:api with Client URLs
Route::group(['middleware' => 'auth:api', 'prefix' => 'client'], function () {
    // Get the authenticated User Data
    Route::get('index', [PassportAuthController::class, 'me']);


    // Update Client Personal Details
    Route::get('personal', [ClientController::class, 'getPersonalInfo']);
    Route::post('personal/update', [ClientController::class, 'personalUpdate']);

    // Update Client Address
    Route::get('address', [ClientController::class, 'getAddressInfo']);
    Route::post('address/update', [ClientController::class, 'addressUpdate']);

    // Update Client Password
    Route::post('password/update', [ClientController::class, 'passwordUpdate']);

    // Update Social Links
    Route::get('social-links', [ClientController::class, 'getSocialLinks']);
    Route::post('social-links/update', [ClientController::class, 'socialLinksUpdate']);

    // Get Client Home
    Route::get('home', [ClientController::class, 'home']);

    // Switch to Helper
    Route::post('switch', [ClientController::class, 'switchToHelper']);

    // Booking Form APIs
    Route::get('booking/new/page1', [ClientBookingController::class, 'newBookingPage1']);
    Route::get('booking/new/page2', [ClientBookingController::class, 'newBookingPage2']);
    Route::post('booking/estimate', [ClientBookingController::class, 'estimateBooking']);
    Route::get('booking/insurance', [ClientBookingController::class, 'insuranceBooking']);
    Route::post('booking/create', [ClientBookingController::class, 'createBooking']);
    Route::get('booking/payment/{id}', [ClientBookingController::class, 'getPaymentBooking']);
    Route::get('booking/details/{id}', [ClientBookingController::class, 'getBookingDetails']);

    // Active Bookings
    Route::get('booking/active', [ClientBookingController::class, 'activeBookings']);
    // Booking History
    Route::get('booking/history', [ClientBookingController::class, 'getBookingHistory']);

    // Logout User
    Route::post('logout', [PassportAuthController::class, 'logout']);

    // Client Routes End
});

// ---------------- CLIENT ROUTES ENDS ---------------- //

// ---------------- HELPER ROUTES START ---------------- //


// Group API Routes with middleware auth:api with Helper URLs
Route::group(['middleware' => 'auth:api', 'prefix' => 'helper'], function () {
    // Get the authenticated User Data
    Route::get('index', [PassportAuthController::class, 'me']);

    // Update Helper Personal Details
    Route::get('personal', [HelperController::class, 'getPersonalInfo']);
    Route::post('personal/update', [HelperController::class, 'personalUpdate']);

    // Update Helper Address
    Route::get('address', [HelperController::class, 'getAddressInfo']);
    Route::post('address/update', [HelperController::class, 'addressUpdate']);


    // Update Helper Password
    Route::post('password/update', [HelperController::class, 'passwordUpdate']);

    // Update Social Links
    Route::get('social-links', [HelperController::class, 'getSocialLinks']);
    Route::post('social-links/update', [HelperController::class, 'socialLinksUpdate']);

    // Helper Home
    Route::get('home', [HelperController::class, 'home']);

    // Switch to Client
    Route::post('switch', [HelperController::class, 'switchToClient']);

    // Bookings APIs
    Route::post('booking/accept', [HelperBookingController::class, 'acceptBooking']);
    Route::post('booking/start', [HelperBookingController::class, 'startBooking']);

    // Logout User
    Route::post('logout', [PassportAuthController::class, 'logout']);

    // Helper Routes End
});

// ---------------- HELPER ROUTES ENDS ---------------- //
