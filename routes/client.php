<?php

use App\Http\Controllers\AddressBookController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingReviewController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\KycDetailController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TeamInvitationController;
use Illuminate\Support\Facades\Route;


Route::middleware(['app_language'])->group(function () {

    //Client Auth Routes
    // Route::get('client/login', 'App\Http\Controllers\Auth\LoginController@showLoginFormClient')->name('client.login');
    Route::get('/login', [LoginController::class, 'showLoginFormClient'])->name('login');
    Route::get('/client/login', [LoginController::class, 'showLoginFormClient'])->name('client.login');
    Route::post('/client/login', [LoginController::class, 'postClientLoginForm'])->name('client.login');
    // Route::post('client/login', 'App\Http\Controllers\Auth\LoginController@login')->name('client.login');

    Route::get('client/register', [RegisterController::class, 'showRegistrationFormClient'])->name('client.register');
    Route::post('client/register', [RegisterController::class, 'register'])->name('client.register');

    Route::post('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

    Route::prefix('client')->middleware(['auth', 'isClient'])->name('client.')->group(function () {

        // Route::get('complete-profile', [ClientController::class, 'complete_profile'])->name('complete_profile');
        // Route::post('update-profile', [ClientController::class, 'update_profile'])->name('update_profile');
        Route::get('/', [ClientController::class, 'index'])->name('index');

        // Search Users Route
        Route::post('/users/search', [ClientController::class, 'searchUsers'])->name('users.search');

        // Switch to Helper
        Route::get('switch-to-helper', [ClientController::class, 'switchToHelper'])->name('switchToHelper');

        // Request Client Companpy
        Route::post('/company/request', [ClientController::class, 'requestCompany'])->name('company.request');

        //Edit Profile -- Update Profile Routes
        Route::get('/profile', [ClientController::class, 'edit_profile'])->name('profile');
        Route::post('/update/personal', [ClientController::class, 'personalInfo'])->name('update.personal');
        Route::post('/update/address', [ClientController::class, 'addressInfo'])->name('update.address');
        Route::post('/update/company', [ClientController::class, 'companyInfo'])->name('update.company');
        Route::post('/update/social', [ClientController::class, 'socialInfo'])->name('update.social');
        Route::post('/update/password', [ClientController::class, 'passwordInfo'])->name('update.password');

        //KYC Details
        Route::get('/kyc-details', [KycDetailController::class, 'index'])->name('kyc_details');
        Route::get('/kyc/create/', [KycDetailController::class, 'create'])->name('kyc.create');
        Route::post('/kyc/store/', [KycDetailController::class, 'store'])->name('kyc.store');
        Route::get('/kyc/edit/{id}', [KycDetailController::class, 'edit'])->name('kyc.edit');
        Route::post('/kyc/update', [KycDetailController::class, 'update'])->name('kyc.update');
        Route::get('/kyc/show/{id}', [KycDetailController::class, 'show'])->name('kyc.show');

        // Team 
        Route::get('team/index', [TeamInvitationController::class, 'getInvitedUsers'])->name('team.index');
        Route::post('team/invite', [TeamInvitationController::class, 'inviteTeamMember'])->name('team.invite');
        Route::get('team/remove/{id}', [TeamInvitationController::class, 'removeTeamMemeber'])->name('team.remove');
        Route::get('team/switch-user/{user}', [TeamInvitationController::class, 'switchUser'])->name('team.switchUser');
        Route::get('team/switch-self', [TeamInvitationController::class, 'switchToSelf'])->name('team.switchToSelf');

        // Invitations
        Route::get('/invitations', [TeamInvitationController::class, 'invitations'])->name('invitations');
        Route::get('/invitation/accept/{id}', [TeamInvitationController::class, 'acceptInvitation'])->name('invitation.accept');
        Route::get('/invitation/decline/{id}', [TeamInvitationController::class, 'declineInvitation'])->name('invitation.decline');
        Route::get('/invitation/get-accepted-list', [TeamInvitationController::class, 'getAcceptedInvites'])->name('invitation.getAcceptedList');


        //Bookings
        Route::get('/orders', [ClientController::class, 'orders'])->name('orders');
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');
        Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
        Route::get('/booking/payment/{id}', [BookingController::class, 'payment'])->name('booking.payment');
        Route::get('/booking/show/{id}', [BookingController::class, 'show'])->name('booking.show');
        Route::get('/bookings/cancel/{id}', [BookingController::class, 'cancel'])->name('booking.cancel');
        Route::get('/booking/payment/cod/{id}', [BookingController::class, 'codPayment'])->name('booking.payment.cod');
        // Paypal
        Route::post('/booking/payment/paypal/create', [BookingController::class, 'createPaypalPayment'])->name('booking.payment.paypal.create');
        Route::get('/booking/payment/paypal/execute', [BookingController::class, 'executePaypalPayment'])->name('booking.payment.paypal.execute');
        Route::get('/booking/payment/paypal/cancel', [BookingController::class, 'cancelPaypalPayment'])->name('booking.payment.paypal.cancel');
        // Stripe
        Route::post('/booking/payment/stripe/charge', [BookingController::class, 'chargeStripePayment'])->name('booking.payment.stripe.charge');

        Route::get('/booking/payment/securehsip/{booking_uuid}', [BookingController::class, 'createSecureshipBookingUsingAPI']);

        // Review to Booking
        Route::post('/booking/review/', [BookingReviewController::class, 'reviewBooking'])->name('booking.review');

        // Chat Page Routes
        Route::get('/chats', [ChatController::class, 'index'])->name('chats');
        Route::post('/chat/create', [ChatController::class, 'create'])->name('chat.create');
        Route::get('/chat/messages/{id}', [MessageController::class, 'index'])->name('chat.messages');
        Route::post('/chat/messages/store', [MessageController::class, 'store'])->name('chat.messages.store');

        //referrals
        Route::get('/referrals', [ClientController::class, 'referrals'])->name('referrals');

        //Track Booking
        Route::get('/track-order/{id?}', [ClientController::class, 'track_order'])->name('trackOrder');

        // Wallet
        Route::get('/wallet', [ClientController::class, 'wallet'])->name('wallet');

        //Address Book
        Route::get('/address-books', [AddressBookController::class, 'index'])->name('addressBooks');
        Route::get('/address-book/create/', [AddressBookController::class, 'create'])->name('addressBook.create');
        Route::post('/address-book/store/', [AddressBookController::class, 'store'])->name('addressBook.store');
        Route::get('/address-book/edit/{id}', [AddressBookController::class, 'edit'])->name('addressBook.edit');
        Route::post('/address-book/update', [AddressBookController::class, 'update'])->name('addressBook.update');
        Route::get('/address-book/show/{id}', [AddressBookController::class, 'show'])->name('addressBook.show');
    });
});
