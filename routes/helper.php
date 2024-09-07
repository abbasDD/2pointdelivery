<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Helper\ChatController;
use App\Http\Controllers\Helper\HelperBookingController;
use App\Http\Controllers\Helper\KycDetailController;
use App\Http\Controllers\Helper\MessageController;
use App\Http\Controllers\Helper\TeamInvitationController;
use App\Http\Controllers\HelperController;
use Illuminate\Support\Facades\Route;



Route::middleware(['app_language'])->group(function () {

    //Helper Routes

    //Helper Auth Routes
    Route::get('helper/login', [LoginController::class, 'showLoginFormHelper'])->name('helper.login');
    Route::post('helper/login', [LoginController::class, 'postHelperLoginForm'])->name('helper.login');

    Route::get('helper/register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationFormHelper')->name('helper.register');
    Route::post('helper/register', [RegisterController::class, 'register'])->name('helper.register');


    Route::prefix('helper')->middleware(['auth', 'isHelper'])->name('helper.')->group(function () {

        Route::get('/', [HelperController::class, 'index'])->name('index');
        Route::get('/index', [HelperController::class, 'index'])->name('index');

        // Switch to Client
        Route::get('switch-to-client', [HelperController::class, 'switchToClient'])->name('switchToClient');

        // Search Users Route
        Route::post('/users/search', [HelperController::class, 'searchUsers'])->name('users.search');

        // Reuqest Client Companpy
        Route::post('/company/request', [HelperController::class, 'requestCompany'])->name('company.request');

        //KYC Details
        Route::get('/kyc-details', [KycDetailController::class, 'index'])->name('kyc_details');
        Route::get('/kyc/create/', [KycDetailController::class, 'create'])->name('kyc.create');
        Route::post('/kyc/store/', [KycDetailController::class, 'store'])->name('kyc.store');
        Route::get('/kyc/edit/{id}', [KycDetailController::class, 'edit'])->name('kyc.edit');
        Route::post('/kyc/update', [KycDetailController::class, 'update'])->name('kyc.update');
        Route::get('/kyc/show/{id}', [KycDetailController::class, 'show'])->name('kyc.show');

        //Bookings
        Route::get('/bookings', [HelperBookingController::class, 'index'])->name('bookings');
        Route::get('/booking/accept/{id}', [HelperBookingController::class, 'acceptBooking'])->name('booking.accept');
        Route::get('/booking/show/{id}', [HelperBookingController::class, 'show'])->name('booking.show');
        Route::post('/booking/start/', [HelperBookingController::class, 'start'])->name('booking.start');
        Route::post('/bookings/cancel/', [HelperBookingController::class, 'cancel'])->name('booking.cancel');
        Route::post('/booking/in-transit/', [HelperBookingController::class, 'inTransit'])->name('booking.inTransit');
        Route::post('/booking/complete/', [HelperBookingController::class, 'complete'])->name('booking.complete');
        Route::post('/booking/incomplete/', [HelperBookingController::class, 'incomplete'])->name('booking.incomplete');

        // Team 
        Route::get('team/index', [TeamInvitationController::class, 'getInvitedUsers'])->name('team.index');
        Route::post('team/invite', [TeamInvitationController::class, 'invite'])->name('team.invite');
        Route::get('team/remove/{id}', [TeamInvitationController::class, 'removeTeamMemeber'])->name('team.remove');
        Route::get('team/switch-user/{user}', [TeamInvitationController::class, 'switchUser'])->name('team.switchUser');
        Route::get('team/switch-self', [TeamInvitationController::class, 'switchToSelf'])->name('team.switchToSelf');

        // Team Invites
        Route::get('/invitations', [TeamInvitationController::class, 'invitations'])->name('invitations');
        Route::get('/invitation/accept/{id}', [TeamInvitationController::class, 'acceptInvitation'])->name('invitation.accept');
        Route::get('/invitation/decline/{id}', [TeamInvitationController::class, 'declineInvitation'])->name('invitation.decline');
        Route::get('/invitation/get-accepted-list', [TeamInvitationController::class, 'getAcceptedInvites'])->name('invitation.getAcceptedList');

        // Chat Page Routes
        Route::get('/chats/{id?}', [ChatController::class, 'index'])->name('chats');
        Route::get('/chat/admin', [ChatController::class, 'adminChat'])->name('chat.admin');
        Route::post('/chat/create', [ChatController::class, 'create'])->name('chat.create');
        Route::get('/chat/messages/{id}', [MessageController::class, 'index'])->name('chat.messages');
        Route::post('/chat/messages/store', [MessageController::class, 'store'])->name('chat.messages.store');

        //Edit Profile -- Update Profile Routes
        Route::get('/profile', [HelperController::class, 'edit_profile'])->name('profile');
        Route::post('/update/personal', [HelperController::class, 'personalInfo'])->name('update.personal');
        Route::post('/update/address', [HelperController::class, 'addressInfo'])->name('update.address');
        Route::post('/update/vehicle', [HelperController::class, 'vehicleInfo'])->name('update.vehicle');
        Route::post('/update/company', [HelperController::class, 'companyInfo'])->name('update.company');
        Route::post('/update/social', [HelperController::class, 'socialInfo'])->name('update.social');
        Route::post('/update/password', [HelperController::class, 'passwordInfo'])->name('update.password');

        // Wallet
        Route::get('/wallet', [HelperController::class, 'wallet'])->name('wallet');
        Route::post('/wallet/withdraw-request', [HelperController::class, 'withdrawRequest'])->name('wallet.withdrawRequest');
        Route::post('/wallet/bank-account', [HelperController::class, 'addBankAccount'])->name('wallet.addBankAccount');

        //Teams
        Route::get('/teams', [HelperController::class, 'teams'])->name('teams');

        //Track Booking
        // Route::get('/track-order', [HelperController::class, 'track_order'])->name('trackOrder');
        Route::get('/track-order/{id?}', [HelperController::class, 'track_order'])->name('trackOrder');
    });

    // Language Routes
});
