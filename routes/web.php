<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\StateController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


// Public routes accessible without authentication

//Front End Routes


// Route::get('/test-email', function () {
//     Mail::raw('This is a test email.', function ($message) {
//         $message->to('ghulamabbas0409@gmail.com')
//             ->subject('Test Email');
//     });

//     return 'Test email sent.';
// });

//Redirect to Home page as login is valid for user
Route::get('/login', [FrontendController::class, 'index'])->name('login');

// Custom route for password reset
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');


// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Google URL
Route::get('/google/redirect', [App\Http\Controllers\GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/google/callback', [App\Http\Controllers\GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');

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
Route::get('/new-booking', [FrontendController::class, 'newBooking'])->name('newBooking');
Route::post('/estimate/delivery', [FrontendController::class, 'deliveryBooking'])->name('estimate.delivery'); //Get order details of delivery type booking
Route::post('/estimate/moving', [FrontendController::class, 'movingBooking'])->name('estimate.moving'); //Get order details of moving type booking
Route::get('/fetch/service-categories', [FrontendController::class, 'fetch_services_categories'])->name('fetch.service.categories');

// Route Chat Page
Route::get('/chat', [ChatController::class, 'redirectChat'])->name('chat');

// Route::get('/booking-detail', function () {
//     return view('frontend.booking_detail');
// })->name('booking_detail');


// Change Language Routes
Route::get('/change-language/{lang}', function ($lang) {
    // session(['applocale' => $lang]);
    session()->put('applocale', $lang);
    // app()->setLocale($lang);
    // dd(session('applocale'));
    session()->save();
    return back();
})->name('change-language');



// Get Address Routes
Route::get('/address/countries', [CountryController::class, 'countries'])->name('address.countries');
Route::get('/address/states/{country_id}', [StateController::class, 'states'])->name('address.states');
Route::get('/address/cities/{state_id}', [CityController::class, 'cities'])->name('address.cities');
