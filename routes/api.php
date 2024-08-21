<?php

use App\Http\Controllers\Api\Client\ClientBookingController;
use App\Http\Controllers\Api\Client\ClientController;
use App\Http\Controllers\Api\Helper\HelperBookingController;
use App\Http\Controllers\Api\Helper\HelperController;
use App\Http\Controllers\Api\KycController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PassportAuthController;


// ---------------- PASSPORT AUTH ROUTES ---------------- //

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);
Route::post('forget-password', [PassportAuthController::class, 'forgetPassword']);

Route::get('auth/{provider}', [PassportAuthController::class, 'redirectToProvider']);
Route::get('auth/{provider}/callback', [PassportAuthController::class, 'handleProviderCallback']);

// Send a test notification
Route::post('send-test-notification', [PassportAuthController::class, 'sendTestNotification']);

// Get app details
Route::get('app-details', [PassportAuthController::class, 'appDetails']);


// ---------------- CLIENT ROUTES START ---------------- //


// Group API Routes with middleware auth:api with Client URLs
Route::group(['middleware' => 'auth:api', 'prefix' => 'client'], function () {
    // Get the authenticated User Data
    Route::get('index', [PassportAuthController::class, 'me']);
    Route::get('address-book', [ClientController::class, 'getAddressBook']);

    // Get Logged In Client Personal Details
    Route::get('profile', [ClientController::class, 'index']);

    // Update Client Personal Details
    Route::get('personal', [ClientController::class, 'getPersonalInfo']);
    Route::post('personal/update', [ClientController::class, 'personalUpdate']);

    // Update Client Address
    Route::get('address', [ClientController::class, 'getAddressInfo']);
    Route::post('address/update', [ClientController::class, 'addressUpdate']);

    // Update Client Password
    Route::post('password/update', [ClientController::class, 'passwordUpdate']);

    // Get Company Details
    Route::get('company', [ClientController::class, 'getCompanyInfo']);
    Route::post('company/update', [ClientController::class, 'companyUpdate']);

    // Update Social Links
    Route::get('social-links', [ClientController::class, 'getSocialLinks']);
    Route::post('social-links/update', [ClientController::class, 'socialLinksUpdate']);

    // Get Client Home
    Route::get('home', [ClientController::class, 'home']);

    // Switch to Helper
    Route::post('switch', [ClientController::class, 'switchToHelper']);

    // Toggle Notification
    Route::post('notification/toggle', [ClientController::class, 'toggleNotification']);

    // Booking Form APIs
    Route::get('booking/new/page1', [ClientBookingController::class, 'newBookingPage1']);
    Route::get('booking/new/page2', [ClientBookingController::class, 'newBookingPage2']);
    Route::post('booking/estimate', [ClientBookingController::class, 'estimateBooking']);
    Route::get('booking/insurance', [ClientBookingController::class, 'insuranceBooking']);
    Route::post('booking/create', [ClientBookingController::class, 'createBooking']);
    Route::get('booking/payment/{id}', [ClientBookingController::class, 'getPaymentBooking']);
    Route::get('booking/details/{id}', [ClientBookingController::class, 'getBookingDetails']);
    Route::get('booking/cancel/{id}', [ClientBookingController::class, 'cancelBooking']);
    Route::get('booking/expire/{id}', [ClientBookingController::class, 'expireBooking']);
    Route::post('booking/payment/cod', [ClientBookingController::class, 'codPaymentBooking']);
    Route::post('booking/payment/paypal', [ClientBookingController::class, 'paypalPaymentBooking']);
    Route::post('booking/payment/stripe', [ClientBookingController::class, 'stripePaymentBooking']);

    // Secureship booking
    Route::get('booking/secureship', [ClientBookingController::class, 'secureshipBooking']);
    Route::post('booking/secureship/create', [ClientBookingController::class, 'createSecureshipBooking']);

    // Track Booking
    Route::post('booking/track', [ClientBookingController::class, 'trackBooking']);

    // Review Booking
    Route::post('booking/review', [ClientBookingController::class, 'reviewBooking']);

    // Active Bookings
    Route::get('booking/active', [ClientBookingController::class, 'activeBookings']);
    // Booking History
    Route::get('booking/history', [ClientBookingController::class, 'getBookingHistory']);

    // KYC CRUD
    Route::get('kyc', [KycController::class, 'index']);
    Route::get('kyc-types', [KycController::class, 'kycTypes']);
    Route::post('kyc/store', [KycController::class, 'store']);
    Route::get('kyc/show/{id}', [KycController::class, 'show']);
    Route::post('kyc/update', [KycController::class, 'update']);

    // Teams 
    Route::get('teams', [ClientController::class, 'getInvitedUsers']);
    Route::post('team/invite', [ClientController::class, 'inviteTeamMember']);
    Route::post('team/remove', [ClientController::class, 'removeTeamMember']);
    Route::post('team/switch-user', [ClientController::class, 'switchUser']);
    Route::post('team/switch-self', [ClientController::class, 'switchToSelf']);

    // invitations
    Route::get('invitations', [ClientController::class, 'getInvitations']);
    Route::post('invitation/accept', [ClientController::class, 'acceptInviation']);
    Route::post('invitation/decline', [ClientController::class, 'declineInvitation']);

    // Notifications
    Route::get('notifications', [ClientController::class, 'getNotifications']);
    Route::post('notifications/read-all', [ClientController::class, 'markAllNotificationsRead']);

    // Logout User
    Route::post('logout', [PassportAuthController::class, 'logout']);

    // Delete account API
    Route::post('account/delete', [ClientController::class, 'deleteAccount']);

    // Chats
    Route::get('chats', [ClientController::class, 'getChatList']);
    Route::post('chat/create', [ClientController::class, 'createChat']);
    Route::get('chat/user/{chat_id}', [ClientController::class, 'getUserChat']);
    Route::post('chat/message/send', [ClientController::class, 'sendMessage']);



    // Client Routes End
});

// ---------------- CLIENT ROUTES ENDS ---------------- //

// ----------------------------------------------------------------------------------------------------- //

// ---------------- HELPER ROUTES START ---------------- //


// Group API Routes with middleware auth:api with Helper URLs
Route::group(['middleware' => 'auth:api', 'prefix' => 'helper'], function () {
    // Get the authenticated User Data
    Route::get('index', [PassportAuthController::class, 'me']);

    // Get Logged In Helper Personal Details
    Route::get('profile', [HelperController::class, 'index']);

    // Update Helper Personal Details
    Route::get('personal', [HelperController::class, 'getPersonalInfo']);
    Route::post('personal/update', [HelperController::class, 'personalUpdate']);

    // Update Helper Address
    Route::get('address', [HelperController::class, 'getAddressInfo']);
    Route::post('address/update', [HelperController::class, 'addressUpdate']);

    // Get Company Details
    Route::get('company', [HelperController::class, 'getCompanyInfo']);
    Route::post('company/update', [HelperController::class, 'companyUpdate']);

    // Update Helper Vehicle
    Route::get('vehicle', [HelperController::class, 'getVehicleInfo']);
    Route::post('vehicle/update', [HelperController::class, 'vehicleInfoUpdate']);

    // Update Helper Password
    Route::post('password/update', [HelperController::class, 'passwordUpdate']);

    // Update Social Links
    Route::get('social-links', [HelperController::class, 'getSocialLinks']);
    Route::post('social-links/update', [HelperController::class, 'socialLinksUpdate']);

    // Helper Home
    Route::get('home', [HelperController::class, 'home']);

    // Switch to Client
    Route::post('switch', [HelperController::class, 'switchToClient']);

    // Toggle Notification
    Route::post('notification/toggle', [HelperController::class, 'toggleNotification']);

    // Bookings APIs
    Route::get('booking/details/{id}', [HelperBookingController::class, 'getBookingDetails']);
    Route::post('booking/accept', [HelperBookingController::class, 'acceptBooking']);
    Route::post('booking/start', [HelperBookingController::class, 'startBooking']);
    Route::post('booking/in-transit', [HelperBookingController::class, 'inTransitBooking']);
    Route::post('booking/complete', [HelperBookingController::class, 'completeBooking']);
    Route::post('booking/in-complete', [HelperBookingController::class, 'incompleteBooking']);

    // Pending Bookings
    Route::get('booking/pending', [HelperBookingController::class, 'pendingBookings']);
    // Active Bookings
    Route::get('booking/active', [HelperBookingController::class, 'activeBookings']);
    // Booking History
    Route::get('booking/history', [HelperBookingController::class, 'getBookingHistory']);


    // KYC CRUD
    Route::get('kyc', [KycController::class, 'index']);
    Route::get('kyc-types', [KycController::class, 'kycTypes']);
    Route::post('kyc/store', [KycController::class, 'store']);
    Route::get('kyc/show/{id}', [KycController::class, 'show']);
    Route::post('kyc/update', [KycController::class, 'update']);

    // Teams 
    Route::get('teams', [HelperController::class, 'getInvitedUsers']);
    Route::post('team/invite', [HelperController::class, 'inviteTeamMember']);
    Route::post('team/remove', [HelperController::class, 'removeTeamMember']);
    Route::post('team/switch-user', [HelperController::class, 'switchUser']);
    Route::post('team/switch-self', [HelperController::class, 'switchToSelf']);

    // invitations
    Route::get('invitations', [HelperController::class, 'getInvitations']);
    Route::post('invitation/accept', [HelperController::class, 'acceptInviation']);
    Route::post('invitation/decline', [HelperController::class, 'declineInvitation']);

    // Notifications
    Route::get('notifications', [HelperController::class, 'getNotifications']);
    Route::post('notifications/read-all', [HelperController::class, 'markAllNotificationsRead']);

    // Logout User
    Route::post('logout', [PassportAuthController::class, 'logout']);

    // Delete account API
    Route::post('account/delete', [HelperController::class, 'deleteAccount']);

    // Chats
    Route::get('chats', [HelperController::class, 'getChatList']);
    Route::post('chat/create', [HelperController::class, 'createChat']);
    Route::get('chat/user/{chat_id}', [HelperController::class, 'getUserChat']);
    Route::post('chat/message/send', [HelperController::class, 'sendMessage']);

    // Wallet
    Route::get('wallet/balance', [HelperController::class, 'getWalletBalance']);
    Route::get('wallet/earning', [HelperController::class, 'getWalletEarning']);
    Route::get('wallet/requests', [HelperController::class, 'getWalletWithdrawRequests']);
    Route::post('wallet/withdraw/request', [HelperController::class, 'postWalletWithdrawRequest']);
    // Bank Accounts
    Route::get('wallet/bank-accounts', [HelperController::class, 'getBankAccounts']);

    // Helper Routes End
});

// ---------------- HELPER ROUTES ENDS ---------------- //
