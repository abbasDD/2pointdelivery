<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\HelperController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceTypeController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\TaxSettingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\VehicleTypeController;

//Admin Routes

//Admin Auth Routes

Route::get('/admin/login', [App\Http\Controllers\Auth\LoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\Auth\LoginController::class, 'postAdminLoginForm'])->name('admin.login');


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
    Route::get('/users/{id}', [AdminController::class, 'users'])->name('users');

    // Search Users Route
    Route::post('/users/search', [AdminController::class, 'searchUsers'])->name('users.search');

    // Clients Page Routes
    Route::get('/clients', [ClientController::class, 'index'])->name('clients');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('client.create');
    Route::get('/clients/show/{id}', [ClientController::class, 'show'])->name('client.show');
    Route::post('/clients/store', [ClientController::class, 'store'])->name('client.store');
    Route::get('/clients/edit/{id}', [ClientController::class, 'edit'])->name('client.edit');
    Route::post('/clients/update', [ClientController::class, 'update'])->name('client.update');
    Route::post('/clients/update-status', [ClientController::class, 'updateStatus'])->name('client.updateStatus');

    // Helpers Page Routes
    Route::get('/helpers', [HelperController::class, 'index'])->name('helpers');
    Route::get('/requested-helpers', [HelperController::class, 'requestedHelpers'])->name('requestedHelpers');
    Route::get('/helpers/create', [HelperController::class, 'create'])->name('helper.create');
    Route::get('/helpers/show/{id}', [HelperController::class, 'show'])->name('helper.show');
    Route::post('/helpers/store', [HelperController::class, 'store'])->name('helper.store');
    Route::get('/helpers/edit/{id}', [HelperController::class, 'edit'])->name('helper.edit');
    Route::post('/helpers/update', [HelperController::class, 'update'])->name('helper.update');
    Route::post('/helpers/update-status', [HelperController::class, 'updateStatus'])->name('helper.updateStatus');
    Route::post('/helpers/approve', [HelperController::class, 'approve'])->name('helper.approve');
    Route::post('/helpers/reject', [HelperController::class, 'reject'])->name('helper.reject');

    // Service Types Page Routes
    Route::get('/service-types', [ServiceTypeController::class, 'index'])->name('serviceTypes');
    Route::get('/service-type/create', [ServiceTypeController::class, 'create'])->name('serviceType.create');
    Route::post('/service-type/store', [ServiceTypeController::class, 'store'])->name('serviceType.store');
    Route::get('/service-type/edit/{id}', [ServiceTypeController::class, 'edit'])->name('serviceType.edit');
    Route::post('/service-type/update', [ServiceTypeController::class, 'update'])->name('serviceType.update');
    Route::post('/service-type/update-status', [ServiceTypeController::class, 'updateStatus'])->name('serviceType.updateStatus');

    // Service Categories Page Routes
    Route::get('/service-categories', [ServiceCategoryController::class, 'index'])->name('serviceCategories');
    Route::get('/service-category/create', [ServiceCategoryController::class, 'create'])->name('serviceCategory.create');
    Route::post('/service-category/store', [ServiceCategoryController::class, 'store'])->name('serviceCategory.store');
    Route::get('/service-category/edit/{id}', [ServiceCategoryController::class, 'edit'])->name('serviceCategory.edit');
    Route::post('/service-category/update', [ServiceCategoryController::class, 'update'])->name('serviceCategory.update');
    Route::post('/service-category/update-status', [ServiceCategoryController::class, 'updateStatus'])->name('serviceCategory.updateStatus');

    // Vehicle Types Page Routes
    Route::get('/vehicle-types', [VehicleTypeController::class, 'index'])->name('vehicleTypes');
    Route::get('/vehicle-type/create', [VehicleTypeController::class, 'create'])->name('vehicleType.create');
    Route::post('/vehicle-type/store', [VehicleTypeController::class, 'store'])->name('vehicleType.store');
    Route::get('/vehicle-type/edit/{id}', [VehicleTypeController::class, 'edit'])->name('vehicleType.edit');
    Route::post('/vehicle-type/update', [VehicleTypeController::class, 'update'])->name('vehicleType.update');
    Route::post('/vehicle-type/update-status', [VehicleTypeController::class, 'updateStatus'])->name('vehicleType.updateStatus');

    // Bookings Page Routes
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/view/{id}', [BookingController::class, 'show'])->name('booking.show');

    // Chat Page Routes
    Route::get('/chats', [ChatController::class, 'index'])->name('chats');
    Route::post('/chat/create', [ChatController::class, 'create'])->name('chat.create');
    Route::get('/chat/messages/{id}', [MessageController::class, 'index'])->name('chat.messages');
    Route::post('/chat/messages/store', [MessageController::class, 'store'])->name('chat.messages.store');

    // Settings Page Routes
    Route::prefix('settings')->group(function () {
        Route::get('/system', [SystemSettingController::class, 'index'])->name('systemSettings');
        Route::post('/system/update', [SystemSettingController::class, 'update'])->name('systemSetting.update');
        // Tax
        Route::get('/tax', [TaxSettingController::class, 'index'])->name('taxSettings');
        Route::get('/tax/create', [TaxSettingController::class, 'create'])->name('taxSetting.create');
        Route::post('/tax/store', [TaxSettingController::class, 'store'])->name('taxSetting.store');
        Route::get('/tax/edit/{id}', [TaxSettingController::class, 'edit'])->name('taxSetting.edit');
        Route::post('/tax/update', [TaxSettingController::class, 'update'])->name('taxSetting.update');
        Route::get('/tax/update-status/{id}', [TaxSettingController::class, 'updateStatus'])->name('taxSetting.updateStatus');
    });


    // End of Admin Routes
});



// Route::get('/admin/helpers', [App\Http\Controllers\AdminController::class, 'helpers'])->name('admin.helpers');

Route::get('/admin/orders', [App\Http\Controllers\AdminController::class, 'orders'])->name('admin.orders');



// Vehicle Types Route

Route::get('/admin/vehicles', [App\Http\Controllers\AdminController::class, 'vehicles'])->name('admin.vehicles');
