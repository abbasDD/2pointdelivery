<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;


//Client Routes

//Client Auth Routes
// Route::get('client/login', 'App\Http\Controllers\Auth\LoginController@showLoginFormClient')->name('client.login');
Route::get('/client/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginFormClient'])->name('client.login');
Route::post('/client/login', [App\Http\Controllers\Auth\LoginController::class, 'postClientLoginForm'])->name('client.login');
// Route::post('client/login', 'App\Http\Controllers\Auth\LoginController@login')->name('client.login');

Route::get('client/register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationFormClient')->name('client.register');
Route::post('client/register', 'App\Http\Controllers\Auth\RegisterController@register')->name('client.register');

Route::post('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');


Route::prefix('client')->middleware(['auth'])->name('client.')->group(function () {

    Route::get('complete-profile', [ClientController::class, 'complete_profile'])->name('complete_profile');
    Route::post('update-profile', [ClientController::class, 'update_profile'])->name('update_profile');
    Route::get('/', [ClientController::class, 'index'])->name('index');

    //KYC Details
    Route::get('/kyc-details', [ClientController::class, 'kyc_details'])->name('kyc_details');

    //Bookings
    Route::get('/orders', [ClientController::class, 'orders'])->name('orders');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/payment/{id}', [BookingController::class, 'payment'])->name('booking.payment');
    Route::get('/booking/show/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::get('/booking/payment/cod/{id}', [BookingController::class, 'codPayment'])->name('booking.payment.cod');

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

    //Edit Profile
    Route::get('/edit', [ClientController::class, 'edit_profile'])->name('edit');

    //Track Order
    Route::get('/track-order', [ClientController::class, 'track_order'])->name('trackOrder');
});
