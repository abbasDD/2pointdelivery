<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\GetEstimateController;
use App\Http\Controllers\GoogleLoginController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\UserNotificationController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


//Front End Routes

Route::get('/command', function () {

    // //storage link
    // // Artisan::call('storage:link'); //This will link storages to public

    // Artisan::call('migrate:fresh --seed'); //This will refresh the complete database and add required data
    // Artisan::call('migrate'); //This will add new tables to data
    // Artisan::call('cache:clear');
    // Artisan::call('config:clear');
    // Artisan::call('view:clear');
    // return 'Storage linked successfully!';
});

// Route::get('/test-email', function () {
//     Mail::raw('This is a test email.', function ($message) {
//         $message->to('ghulamabbas0409@gmail.com')
//             ->subject('Test Email');
//     });

//     return 'Test email sent.';
// });

Route::middleware(['app_language'])->group(function () {


    // Impersonate Routes
    Route::impersonate();

    //Redirect to Home page as login is valid for user
    // Route::get('/login', [FrontendController::class, 'index'])->name('login');


    // Custom route for password reset
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Google URL
    Route::get('auth/{provider}', [GoogleLoginController::class, 'redirectToProvider'])->name('google.redirect');
    Route::get('auth/{provider}/callback', [GoogleLoginController::class, 'handleProviderCallback'])->name('google.callback');

    // Apple URL


    // Home Page
    Route::get('/', [FrontendController::class, 'index'])->name('index');
    Route::get('/index', [FrontendController::class, 'index'])->name('index');

    // Service Page
    Route::get('/services', [FrontendController::class, 'services'])->name('services');
    // About Us Page
    Route::get('/about-us', [FrontendController::class, 'about_us'])->name('about-us');
    // Contact Us Page
    Route::get('/contact-us', [FrontendController::class, 'contact_us'])->name('contact-us');

    // Help Page
    Route::get('/help', [FrontendController::class, 'help'])->name('help');
    Route::get('/topic/{id}', [FrontendController::class, 'topicQuestionList'])->name('topicQuestionList');
    Route::get('/topic/question/{id}', [FrontendController::class, 'topicQuestion'])->name('topicQuestion');
    Route::get('/topic-search', [FrontendController::class, 'topicSearch'])->name('topic.search');

    // Join Helper Page
    Route::get('/join-helper', [FrontendController::class, 'join_helper'])->name('join_helper');

    // Blog Page
    Route::get('/blog', [FrontendController::class, 'blog'])->name('blog');
    Route::get('/blog/{id}', [FrontendController::class, 'blogDetails'])->name('blog.view');

    // Terms & Conditions Page
    Route::get('/terms-and-conditions', [FrontendController::class, 'terms_and_conditions'])->name('terms_and_conditions');

    // Privacy Policy Page
    Route::get('/privacy-policy', [FrontendController::class, 'privacy_policy'])->name('privacy_policy');

    // Cancellation & Refund Policy Page
    Route::get('/cancellation-policy', [FrontendController::class, 'cancellation_policy'])->name('cancellation_policy');

    //Booking Routes
    Route::get('/new-booking', [FrontendController::class, 'newBooking'])->name('newBooking')->middleware('auth');
    Route::post('/estimate/index', [GetEstimateController::class, 'index'])->name('estimate.index'); //Get booking estimates
    Route::post('/estimate/insurance', [GetEstimateController::class, 'calculateInsuranceFromPackageValue'])->name('estimate.insurance'); //Get booking estimates
    Route::get('/fetch/service-categories/{serviceType?}', [FrontendController::class, 'fetch_services_categories'])->name('fetch.service.categories');

    // getTrackingDetail
    Route::get('/get-tracking-detail/{trackingCode?}', [FrontendController::class, 'getTrackingDetail'])->name('getTrackingDetail');

    //Track Booking
    Route::post('/track-booking', [FrontendController::class, 'track_booking'])->name('trackBooking');

    // testimonials
    Route::get('/testimonials', [FrontendController::class, 'testimonials'])->name('testimonials');

    // Route Chat Page
    Route::get('/chat', [ChatController::class, 'redirectChat'])->name('chat');

    // Change Language Routes
    Route::get('/change-language/{lang}', [FrontendController::class, 'changeLanguage'])->name('change-language');
});

// Generate pdf
Route::get('booking-invoice/{booking_id}', [BookingController::class, 'generateInvoice'])->name('invoice.download');
Route::get('shipping-label/{booking_id}', [BookingController::class, 'generateLabel'])->name('shippinglabel.download');



// User Notifications
Route::get('user/notifications', [UserNotificationController::class, 'index'])->name('user.notifications');
Route::get('user/notification/redirect/{id}', [UserNotificationController::class, 'notificationRedirect'])->name('user.notificationRedirect');
Route::get('user/notifications/read', [UserNotificationController::class, 'markAllAsRead'])->name('user.notifications.read');


// Get Address Routes
Route::get('/address/countries', [CountryController::class, 'countries'])->name('address.countries');
Route::get('/address/states/{country_id}', [StateController::class, 'states'])->name('address.states');
Route::get('/address/cities/{state_id}', [CityController::class, 'cities'])->name('address.cities');

// Test a PDF
Route::get('generate-pdf', [PDFController::class, 'generatePDF']);
Route::get('booking-invoice-pdf/{id}', [PDFController::class, 'bookingInvoicePDF'])->name('booking-invoice-pdf');
Route::get('label/{id}', [PDFController::class, 'downloadLabel'])->name('label');


// Upload Trix attachment
Route::post('/attachments', [AttachmentController::class, 'store'])->name('attachments.store');


Route::post('/secureship/estimate', [GetEstimateController::class, 'getSecureshipEstimate']);
