<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DeliveryConfigController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FloorAssessController;
use App\Http\Controllers\Admin\FloorPlanController;
use App\Http\Controllers\Admin\FrontendSettingController;
use App\Http\Controllers\Admin\HelperController;
use App\Http\Controllers\Admin\HelpQuestionController;
use App\Http\Controllers\Admin\HelpTopicController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\JobDetailController;
use App\Http\Controllers\Admin\KycDetailController;
use App\Http\Controllers\Admin\KycTypeController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\MovingConfigController;
use App\Http\Controllers\Admin\MovingDetailController;
use App\Http\Controllers\Admin\NoOfRoomController;
use App\Http\Controllers\Admin\PaymentSettingController;
use App\Http\Controllers\Admin\SocialLoginSettingController;
use App\Http\Controllers\Admin\PrioritySettingController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceTypeController;
use App\Http\Controllers\Admin\SmtpSettingController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\TaxSettingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\VehicleTypeController;
use App\Http\Controllers\Admin\WalletAdmin;
use App\Http\Controllers\Admin\WalletAdminController;
use App\Http\Controllers\Auth\LoginController;

//Admin Routes

//Admin Auth Routes

Route::get('/admin/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'postAdminLoginForm'])->name('admin.login');


// Admin Routes Group
Route::prefix('admin')->middleware(['auth', 'isAdmin'])->name('admin.')->group(function () {
    // Dashboard Route
    Route::get('/', [HomeController::class, 'index'])->name('index');

    // Admin Page Routes
    Route::get('/admins', [AdminController::class, 'index'])->name('admins');
    Route::get('/admins/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admins/store', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admins/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
    Route::post('/admins/update', [AdminController::class, 'update'])->name('admin.update');

    // Create a route to redirect to user page as per user type
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('user.show');
    // Route::get('/users/{id}', [AdminController::class, 'users'])->name('users');

    // Search Users Route
    Route::post('/users/search', [AdminController::class, 'searchUsers'])->name('users.search');

    // KYC Type Routes
    Route::get('/kyc-types', [KycTypeController::class, 'index'])->name('kycTypes');
    Route::get('/kyc-types/create', [KycTypeController::class, 'create'])->name('kycType.create');
    Route::post('/kyc-types/store', [KycTypeController::class, 'store'])->name('kycType.store');
    Route::get('/kyc-types/edit/{id}', [KycTypeController::class, 'edit'])->name('kycType.edit');
    Route::post('/kyc-types/update', [KycTypeController::class, 'update'])->name('kycType.update');
    Route::post('/kyc-types/update-status', [KycTypeController::class, 'updateStatus'])->name('kycType.updateStatus');

    //KYC Details
    Route::get('/kyc-details', [KycDetailController::class, 'index'])->name('kycDetails');
    Route::get('/kyc-details/approve/{id}', [KycDetailController::class, 'approveKycDetail'])->name('kycDetail.approve');
    Route::get('/kyc-details/reject/{id}', [KycDetailController::class, 'rejectKycDetail'])->name('kycDetail.reject');
    Route::get('/kyc-details/show/{id}', [KycDetailController::class, 'show'])->name('kycDetail.show');

    // Clients Page Routes
    Route::get('/clients', [ClientController::class, 'index'])->name('clients');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('client.create');
    Route::get('/clients/show/{id}', [ClientController::class, 'show'])->name('client.show');
    Route::post('/clients/store', [ClientController::class, 'store'])->name('client.store');
    Route::get('/clients/edit/{id}', [ClientController::class, 'edit'])->name('client.profile');
    Route::post('/clients/update', [ClientController::class, 'update'])->name('client.update');
    Route::post('/clients/update-status', [ClientController::class, 'updateStatus'])->name('client.updateStatus');
    Route::post('/clients/reset-password', [ClientController::class, 'resetPassword'])->name('client.resetPassword');

    // Helpers Page Routes
    Route::get('/helpers', [HelperController::class, 'index'])->name('helpers');
    Route::get('/requested-helpers', [HelperController::class, 'newHelpers'])->name('newHelpers');
    Route::get('/helpers/create', [HelperController::class, 'create'])->name('helper.create');
    Route::get('/helpers/show/{id}', [HelperController::class, 'show'])->name('helper.show');
    Route::post('/helpers/store', [HelperController::class, 'store'])->name('helper.store');
    Route::get('/helpers/edit/{id}', [HelperController::class, 'edit'])->name('helper.profile');
    Route::post('/helpers/update', [HelperController::class, 'update'])->name('helper.update');
    Route::post('/helpers/update-status', [HelperController::class, 'updateStatus'])->name('helper.updateStatus');
    Route::post('/helpers/approve', [HelperController::class, 'approve'])->name('helper.approve');
    Route::post('/helpers/reject', [HelperController::class, 'reject'])->name('helper.reject');
    Route::post('/helpers/reset-password', [HelperController::class, 'resetPassword'])->name('client.resetPassword');

    // Helper Vehciles
    Route::get('/helper/vehicles/approve/{id}', [HelperController::class, 'approveHelperVehicles'])->name('helpers.vehicles.approve');
    Route::get('/helper/vehicles/reject/{id}', [HelperController::class, 'rejectHelperVehicles'])->name('helpers.vehicles.reject');

    // Service Types Page Routes
    Route::get('/service-types', [ServiceTypeController::class, 'index'])->name('serviceTypes');
    Route::get('/service-type/create', [ServiceTypeController::class, 'create'])->name('serviceType.create');
    Route::post('/service-type/store', [ServiceTypeController::class, 'store'])->name('serviceType.store');
    Route::get('/service-type/edit/{id}', [ServiceTypeController::class, 'edit'])->name('serviceType.edit');
    Route::post('/service-type/update', [ServiceTypeController::class, 'update'])->name('serviceType.update');
    Route::post('/service-type/update-status', [ServiceTypeController::class, 'updateStatus'])->name('serviceType.updateStatus');

    // Vehicle Types Page Routes
    Route::get('/vehicle-types', [VehicleTypeController::class, 'index'])->name('vehicleTypes');
    Route::get('/vehicle-type/create', [VehicleTypeController::class, 'create'])->name('vehicleType.create');
    Route::post('/vehicle-type/store', [VehicleTypeController::class, 'store'])->name('vehicleType.store');
    Route::get('/vehicle-type/edit/{id}', [VehicleTypeController::class, 'edit'])->name('vehicleType.edit');
    Route::post('/vehicle-type/update', [VehicleTypeController::class, 'update'])->name('vehicleType.update');
    Route::post('/vehicle-type/update-status', [VehicleTypeController::class, 'updateStatus'])->name('vehicleType.updateStatus');

    // Service Categories Page Routes
    Route::get('/service-categories', [ServiceCategoryController::class, 'index'])->name('serviceCategories');
    Route::get('/service-category/create', [ServiceCategoryController::class, 'create'])->name('serviceCategory.create');
    Route::post('/service-category/store', [ServiceCategoryController::class, 'store'])->name('serviceCategory.store');
    Route::get('/service-category/edit/{id}', [ServiceCategoryController::class, 'edit'])->name('serviceCategory.edit');
    Route::post('/service-category/update', [ServiceCategoryController::class, 'update'])->name('serviceCategory.update');
    Route::post('/service-category/update-status', [ServiceCategoryController::class, 'updateStatus'])->name('serviceCategory.updateStatus');
    Route::get('/service-category/list/{id}', [ServiceCategoryController::class, 'getVehicleTypes'])->name('serviceCategory.list');

    // Moving Configuration Page Routes
    Route::prefix('moving-config')->name('movingConfig.')->group(function () {
        Route::get('/', [MovingConfigController::class, 'index'])->name('index');
        // Route::post('/moving-config/update', [MovingConfigController::class, 'update'])->name('movingConfig.update');

        // No of Rooms Page Routes
        Route::get('/no-of-rooms/create', [NoOfRoomController::class, 'create'])->name('noOfRooms.create');
        Route::post('/no-of-rooms/store', [NoOfRoomController::class, 'store'])->name('noOfRooms.store');
        Route::get('/no-of-rooms/edit/{id}', [NoOfRoomController::class, 'edit'])->name('noOfRooms.edit');
        Route::post('/no-of-rooms/update', [NoOfRoomController::class, 'update'])->name('noOfRooms.update');
        Route::post('/no-of-rooms/update-status', [NoOfRoomController::class, 'updateStatus'])->name('noOfRooms.updateStatus');

        // Floor Plan Page Routes
        Route::get('/floor-plan/create', [FloorPlanController::class, 'create'])->name('floorPlan.create');
        Route::post('/floor-plan/store', [FloorPlanController::class, 'store'])->name('floorPlan.store');
        Route::get('/floor-plan/edit/{id}', [FloorPlanController::class, 'edit'])->name('floorPlan.edit');
        Route::post('/floor-plan/update', [FloorPlanController::class, 'update'])->name('floorPlan.update');
        Route::post('/floor-plan/update-status', [FloorPlanController::class, 'updateStatus'])->name('floorPlan.updateStatus');

        // Floor Access Page Routes
        Route::get('/floor-assess/create', [FloorAssessController::class, 'create'])->name('floorAssess.create');
        Route::post('/floor-assess/store', [FloorAssessController::class, 'store'])->name('floorAssess.store');
        Route::get('/floor-assess/edit/{id}', [FloorAssessController::class, 'edit'])->name('floorAssess.edit');
        Route::post('/floor-assess/update', [FloorAssessController::class, 'update'])->name('floorAssess.update');
        Route::post('/floor-assess/update-status', [FloorAssessController::class, 'updateStatus'])->name('floorAssess.updateStatus');

        // Job Details Page Routes
        Route::get('/job-detail/create', [JobDetailController::class, 'create'])->name('jobDetails.create');
        Route::post('/job-detail/store', [JobDetailController::class, 'store'])->name('jobDetails.store');
        Route::get('/job-detail/edit/{id}', [JobDetailController::class, 'edit'])->name('jobDetails.edit');
        Route::post('/job-detail/update', [JobDetailController::class, 'update'])->name('jobDetails.update');
        Route::post('/job-detail/update-status', [JobDetailController::class, 'updateStatus'])->name('jobDetails.updateStatus');

        // Priority
        Route::get('/priority/create', [PrioritySettingController::class, 'create'])->name('priority.create');
        Route::post('/priority/store', [PrioritySettingController::class, 'store'])->name('priority.store');
        Route::get('/priority/edit/{id}', [PrioritySettingController::class, 'edit'])->name('priority.edit');
        Route::post('/priority/update', [PrioritySettingController::class, 'update'])->name('priority.update');
        Route::post('/priority/update-status', [PrioritySettingController::class, 'updateStatus'])->name('priority.updateStatus');
    });

    // Delivery Configuration Page Routes
    Route::prefix('delivery-config')->name('deliveryConfig.')->group(function () {
        Route::get('/', [DeliveryConfigController::class, 'index'])->name('index');

        // Update Insurance API
        Route::post('/update-insurance', [DeliveryConfigController::class, 'updateInsurance'])->name('insurance.update');

        // Update Secureship API
        Route::post('/update-secureship', [DeliveryConfigController::class, 'updateSecureship'])->name('secureship.update');

        // Priority
        Route::get('/priority/create', [PrioritySettingController::class, 'createDelivery'])->name('priority.create');
        Route::post('/priority/store', [PrioritySettingController::class, 'store'])->name('priority.store');
        Route::get('/priority/edit/{id}', [PrioritySettingController::class, 'editDelivery'])->name('priority.edit');
        Route::post('/priority/update', [PrioritySettingController::class, 'update'])->name('priority.update');
        Route::post('/priority/update-status', [PrioritySettingController::class, 'updateStatus'])->name('priority.updateStatus');
    });

    // Moving Detail Categories Page Routes
    Route::post('/moving-detail-category/store', [MovingDetailController::class, 'storeCategory'])->name('movingDetailCategory.store');

    // Moving Detail Page Routes
    Route::get('/moving-detail/create', [MovingDetailController::class, 'create'])->name('movingDetail.create');
    Route::post('/moving-detail/store', [MovingDetailController::class, 'store'])->name('movingDetail.store');
    Route::get('/moving-detail/edit/{id}', [MovingDetailController::class, 'edit'])->name('movingDetail.edit');
    Route::post('/moving-detail/update', [MovingDetailController::class, 'update'])->name('movingDetail.update');
    Route::post('/moving-detail/update-status', [MovingDetailController::class, 'updateStatus'])->name('movingDetail.updateStatus');

    // Bookings Page Routes
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/view/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::get('/bookings/cancel/{id}', [BookingController::class, 'cancel'])->name('booking.cancel');

    // Chat Page Routes
    Route::get('/chats', [ChatController::class, 'index'])->name('chats');
    Route::post('/chat/create', [ChatController::class, 'create'])->name('chat.create');
    Route::get('/chat/messages/{id}', [MessageController::class, 'index'])->name('chat.messages');
    Route::post('/chat/messages/store', [MessageController::class, 'store'])->name('chat.messages.store');

    // FAQs Page Routes
    Route::get('/faqs', [FaqController::class, 'index'])->name('faqs');
    Route::get('/faq/create', [FaqController::class, 'create'])->name('faq.create');
    Route::post('faq/store', [FaqController::class, 'store'])->name('faq.store');
    Route::get('/faq/edit/{id}', [FaqController::class, 'edit'])->name('faq.edit');
    Route::post('/faq/update', [FaqController::class, 'update'])->name('faq.update');
    Route::post('/faq/update-status', [FaqController::class, 'updateStatus'])->name('faq.updateStatus');

    // Blogs Page Routes
    Route::get('/blogs', [BlogController::class, 'index'])->name('blogs');
    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('blog/store', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/edit/{id}', [BlogController::class, 'edit'])->name('blog.edit');
    Route::post('/blog/update', [BlogController::class, 'update'])->name('blog.update');
    Route::post('/blog/update-status', [BlogController::class, 'updateStatus'])->name('blog.updateStatus');

    // Settings Page Routes
    Route::prefix('settings')->group(function () {
        // Default Settings
        Route::get('/', [SystemSettingController::class, 'index'])->name('settings');

        // Route::get('/system', [SystemSettingController::class, 'index'])->name('systemSettings');
        Route::post('/system/update', [SystemSettingController::class, 'update'])->name('systemSetting.update');
        // Tax
        // Route::get('/tax', [TaxSettingController::class, 'index'])->name('taxSettings');
        Route::get('/tax/create', [TaxSettingController::class, 'create'])->name('taxSetting.create');
        Route::post('/tax/store', [TaxSettingController::class, 'store'])->name('taxSetting.store');
        Route::get('/tax/edit/{id}', [TaxSettingController::class, 'edit'])->name('taxSetting.edit');
        Route::post('/tax/update', [TaxSettingController::class, 'update'])->name('taxSetting.update');
        Route::post('/tax/update-status', [TaxSettingController::class, 'updateStatus'])->name('taxSetting.updateStatus');

        // Payment
        // Route::get('/payment', [PaymentSettingController::class, 'index'])->name('paymentSettings');
        Route::post('/payment/update', [PaymentSettingController::class, 'update'])->name('paymentSetting.update');

        // Social Login
        // Route::get('/social-logins', [SocialLoginSettingController::class, 'index'])->name('socialLoginSettings');
        Route::post('/social-login/update', [SocialLoginSettingController::class, 'update'])->name('socialLoginSetting.update');
        // Social Login
        // Route::get('/smtps', [SmtpSettingController::class, 'index'])->name('smtpSettings');
        Route::post('/smtp/update', [SmtpSettingController::class, 'update'])->name('smtpSetting.update');


        // End of Settings Prefix Route
    });

    // Email Templates Page Routes
    Route::prefix('email-templates')->name('emailTemplates.')->group(function () {
        Route::get('/', [EmailTemplateController::class, 'index'])->name('index');
        Route::post('/welcome/store', [EmailTemplateController::class, 'welcomeEmailStore'])->name('welcome.store');
        Route::post('/password-reset/store', [EmailTemplateController::class, 'passwordResetEmailStore'])->name('passwordReset.store');
        Route::post('/booking/status', [EmailTemplateController::class, 'bookingStatusStore'])->name('bookingStatus.store');
        Route::post('/delivery/notification', [EmailTemplateController::class, 'deliveryNotificationStore'])->name('deliveryNotification.store');
        Route::post('/feedback', [EmailTemplateController::class, 'feedbackEmailStore'])->name('feedback.store');
        Route::post('/request/feedback', [EmailTemplateController::class, 'requestFeedbackEmailStore'])->name('requestFeedback.store');
        Route::post('/refund/notification', [EmailTemplateController::class, 'refundNotificationEmailStore'])->name('refundNotification.store');
    });

    // Frontend Settings
    Route::prefix('frontend-settings')->name('frontendSettings.')->group(function () {
        Route::get('/', [FrontendSettingController::class, 'index'])->name('index');
        Route::post('/privacy-policy/store', [FrontendSettingController::class, 'privacyPolicyStore'])->name('privacyPolicy.store');
        Route::post('/terms-and-conditions/store', [FrontendSettingController::class, 'termsAndConditionsStore'])->name('termsAndConditions.store');
        Route::post('/canellation-policy/store', [FrontendSettingController::class, 'cancellationPolicyStore'])->name('cancellationPolicy.store');
    });

    // Help Topic Page Routes
    Route::post('/help-topic/store', [HelpTopicController::class, 'store'])->name('helpTopic.store');

    // Help Questions Page Routes
    Route::get('/help-questions', [HelpQuestionController::class, 'index'])->name('helpQuestions');
    Route::get('/help-question/create', [HelpQuestionController::class, 'create'])->name('helpQuestion.create');
    Route::post('help-question/store', [HelpQuestionController::class, 'store'])->name('helpQuestion.store');
    Route::get('/help-question/edit/{id}', [HelpQuestionController::class, 'edit'])->name('helpQuestion.edit');
    Route::post('/help-question/update', [HelpQuestionController::class, 'update'])->name('helpQuestion.update');
    Route::post('/help-question/update-status', [HelpQuestionController::class, 'updateStatus'])->name('helpQuestion.updateStatus');

    // Wallet
    Route::get('/wallet', [WalletAdminController::class, 'index'])->name('wallet');

    // End of Admin Routes
});
