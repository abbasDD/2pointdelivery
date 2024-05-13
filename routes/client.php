<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\KycDetailController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;


//Client Routes

//Client Auth Routes
// Route::get('client/login', 'App\Http\Controllers\Auth\LoginController@showLoginFormClient')->name('client.login');
Route::get('/client/login', [LoginController::class, 'showLoginFormClient'])->name('client.login');
Route::post('/client/login', [LoginController::class, 'postClientLoginForm'])->name('client.login');
// Route::post('client/login', 'App\Http\Controllers\Auth\LoginController@login')->name('client.login');

Route::get('client/register', [RegisterController::class, 'showRegistrationFormClient'])->name('client.register');
Route::post('client/register', [RegisterController::class, 'register'])->name('client.register');

Route::post('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');


Route::prefix('client')->middleware(['auth'])->name('client.')->group(function () {

    Route::get('complete-profile', [ClientController::class, 'complete_profile'])->name('complete_profile');
    Route::post('update-profile', [ClientController::class, 'update_profile'])->name('update_profile');
    Route::get('/', [ClientController::class, 'index'])->name('index');

    // Search Users Route
    Route::post('/users/search', [ClientController::class, 'searchUsers'])->name('users.search');

    // Reuqest Client Companpy
    Route::post('/company/request', [ClientController::class, 'requestCompany'])->name('company.request');

    //KYC Details
    Route::get('/kyc-details', [KycDetailController::class, 'index'])->name('kyc_details');
    Route::get('/kyc/create/', [KycDetailController::class, 'create'])->name('kyc.create');
    Route::post('/kyc/store/', [KycDetailController::class, 'store'])->name('kyc.store');
    Route::get('/kyc/edit/{id}', [KycDetailController::class, 'edit'])->name('kyc.edit');
    Route::post('/kyc/update', [KycDetailController::class, 'update'])->name('kyc.update');
    Route::get('/kyc/show/{id}', [KycDetailController::class, 'show'])->name('kyc.show');

    //Bookings
    Route::get('/orders', [ClientController::class, 'orders'])->name('orders');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/payment/{id}', [BookingController::class, 'payment'])->name('booking.payment');
    Route::get('/booking/show/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::get('/booking/payment/cod/{id}', [BookingController::class, 'codPayment'])->name('booking.payment.cod');
    Route::post('/booking/payment/paypal/create', [BookingController::class, 'createPaypalPayment'])->name('booking.payment.paypal.create');
    Route::get('/booking/payment/paypal/execute', [BookingController::class, 'executePaypalPayment'])->name('booking.payment.paypal.execute');
    Route::get('/booking/payment/paypal/cancel', [BookingController::class, 'cancelPaypalPayment'])->name('booking.payment.paypal.cancel');

    // Chat Page Routes
    Route::get('/chats', [ChatController::class, 'index'])->name('chats');
    Route::post('/chat/create', [ChatController::class, 'create'])->name('chat.create');
    Route::get('/chat/messages/{id}', [MessageController::class, 'index'])->name('chat.messages');
    Route::post('/chat/messages/store', [MessageController::class, 'store'])->name('chat.messages.store');


    //Invoices
    Route::get('/invoices', [ClientController::class, 'invoices'])->name('invoices');

    //referrals
    Route::get('/referrals', [ClientController::class, 'referrals'])->name('referrals');

    //settings
    Route::get('/settings', [ClientController::class, 'settings'])->name('settings');

    //Edit Profile -- Update Profile Routes
    Route::get('/edit', [ClientController::class, 'edit_profile'])->name('edit');
    Route::post('/update/personal', [ClientController::class, 'personalInfo'])->name('update.personal');
    Route::post('/update/address', [ClientController::class, 'addressInfo'])->name('update.address');
    Route::post('/update/company', [ClientController::class, 'companyInfo'])->name('update.company');
    Route::post('/update/social', [ClientController::class, 'socialInfo'])->name('update.social');
    Route::post('/update/password', [ClientController::class, 'passwordInfo'])->name('update.password');

    //Track Order
    Route::get('/track-order', [ClientController::class, 'track_order'])->name('trackOrder');
});
