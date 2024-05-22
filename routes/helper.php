<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Helper\BookingController;
use App\Http\Controllers\Helper\ChatController;
use App\Http\Controllers\Helper\KycDetailController;
use App\Http\Controllers\Helper\MessageController;
use App\Http\Controllers\HelperController;
use Illuminate\Support\Facades\Route;


//Helper Routes



//Helper Auth Routes
Route::get('helper/login', 'App\Http\Controllers\Auth\LoginController@showLoginFormHelper')->name('helper.login');
Route::post('helper/login', 'App\Http\Controllers\Auth\LoginController@postHelperLoginForm')->name('helper.login');

Route::get('helper/register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationFormHelper')->name('helper.register');
Route::post('helper/register', [RegisterController::class, 'register'])->name('helper.register');


Route::prefix('helper')->middleware(['auth', 'isHelper'])->name('helper.')->group(function () {

    Route::get('/complete-profile', [HelperController::class, 'complete_profile'])->name('complete_profile');
    Route::post('/update-profile', [HelperController::class, 'update_profile'])->name('update_profile');
    Route::get('/', [HelperController::class, 'index'])->name('index');
    Route::get('/index', [HelperController::class, 'index'])->name('index');

    // Search Users Route
    Route::post('/users/search', [HelperController::class, 'searchUsers'])->name('users.search');

    // Reuqest Client Companpy
    Route::post('/company/request', [HelperController::class, 'requestCompany'])->name('company.request');

    //KYC Details
    // Route::get('/kyc-details', [HelperController::class, 'kyc_details'])->name('kyc_details');
    //KYC Details
    // Route::get('/kyc-details', [KycDetailController::class, 'index'])->name('kyc_details');
    // Route::post('/kyc/update', [KycDetailController::class, 'update'])->name('kyc.update');
    //KYC Details
    Route::get('/kyc-details', [KycDetailController::class, 'index'])->name('kyc_details');
    Route::get('/kyc/create/', [KycDetailController::class, 'create'])->name('kyc.create');
    Route::post('/kyc/store/', [KycDetailController::class, 'store'])->name('kyc.store');
    Route::get('/kyc/edit/{id}', [KycDetailController::class, 'edit'])->name('kyc.edit');
    Route::post('/kyc/update', [KycDetailController::class, 'update'])->name('kyc.update');
    Route::get('/kyc/show/{id}', [KycDetailController::class, 'show'])->name('kyc.show');

    //Bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');
    Route::get('/booking/accept/{id}', [BookingController::class, 'acceptBooking'])->name('booking.accept');
    Route::get('/booking/show/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking/start/', [BookingController::class, 'start'])->name('booking.start');
    Route::post('/booking/in-transit/', [BookingController::class, 'inTransit'])->name('booking.inTransit');
    Route::post('/booking/complete/', [BookingController::class, 'complete'])->name('booking.complete');
    Route::post('/booking/incomplete/', [BookingController::class, 'incomplete'])->name('booking.incomplete');


    // Chat Page Routes
    Route::get('/chats', [ChatController::class, 'index'])->name('chats');
    Route::post('/chat/create', [ChatController::class, 'create'])->name('chat.create');
    Route::get('/chat/messages/{id}', [MessageController::class, 'index'])->name('chat.messages');
    Route::post('/chat/messages/store', [MessageController::class, 'store'])->name('chat.messages.store');


    //settings
    Route::get('/settings', [HelperController::class, 'settings'])->name('settings');

    //Edit Profile -- Update Profile Routes
    Route::get('/profile', [HelperController::class, 'edit_profile'])->name('profile');
    Route::post('/update/personal', [HelperController::class, 'personalInfo'])->name('update.personal');
    Route::post('/update/address', [HelperController::class, 'addressInfo'])->name('update.address');
    Route::post('/update/vehicle', [HelperController::class, 'vehicleInfo'])->name('update.vehicle');
    Route::post('/update/company', [HelperController::class, 'companyInfo'])->name('update.company');
    Route::post('/update/social', [HelperController::class, 'socialInfo'])->name('update.social');
    Route::post('/update/password', [HelperController::class, 'passwordInfo'])->name('update.password');


    //Teams
    Route::get('/teams', [HelperController::class, 'teams'])->name('teams');

    //Track Order
    Route::get('/track-order', [HelperController::class, 'track_order'])->name('trackOrder');
});
