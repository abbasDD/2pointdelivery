<?php

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\HelperController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\TaxSettingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\VehicleTypeController;

//Admin Routes

//Admin Auth Routes

Route::get('/admin/login', [App\Http\Controllers\Auth\LoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\Auth\LoginController::class, 'postAdminLoginForm'])->name('admin.login');


// Admin Routes Group
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Dashboard Route
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    // Sub Admins Page Routes
    Route::get('/subadmins', [AdminController::class, 'subadmins'])->name('admin.subadmins');
    Route::get('/subadmins/create', [AdminController::class, 'createSubadmin'])->name('admin.subadmin.create');
    Route::post('/subadmins/store', [AdminController::class, 'storeSubadmin'])->name('admin.subadmin.store');
    Route::get('/subadmins/edit/{id}', [AdminController::class, 'editSubadmin'])->name('admin.subadmin.edit');
    Route::post('/subadmins/update', [AdminController::class, 'updateSubadmin'])->name('admin.subadmin.update');

    // Clients Page Routes
    Route::get('/clients', [ClientController::class, 'index'])->name('admin.clients');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('admin.client.create');
    Route::post('/clients/store', [ClientController::class, 'store'])->name('admin.client.store');
    Route::get('/clients/edit/{id}', [ClientController::class, 'edit'])->name('admin.client.edit');
    Route::post('/clients/update', [ClientController::class, 'update'])->name('admin.client.update');

    // Helpers Page Routes
    Route::get('/helpers', [HelperController::class, 'index'])->name('admin.helpers');
    Route::get('/requested-helpers', [HelperController::class, 'requestedHelpers'])->name('admin.requestedHelpers');
    Route::get('/helpers/create', [HelperController::class, 'create'])->name('admin.helper.create');
    Route::post('/helpers/store', [HelperController::class, 'store'])->name('admin.helper.store');
    Route::get('/helpers/edit/{id}', [HelperController::class, 'edit'])->name('admin.helper.edit');
    Route::post('/helpers/update', [HelperController::class, 'update'])->name('admin.helper.update');

    // Bookings Page Routes
    Route::get('/bookings', [BookingController::class, 'index'])->name('admin.bookings');
    Route::get('/bookings/view/{id}', [BookingController::class, 'show'])->name('admin.booking.show');

    // Vehicle Types Page Routes
    Route::get('/vehicle-types', [VehicleTypeController::class, 'index'])->name('admin.vehicleTypes');
    Route::get('/vehicle-type/create', [VehicleTypeController::class, 'create'])->name('admin.vehicleType.create');
    Route::post('/vehicle-type/store', [VehicleTypeController::class, 'store'])->name('admin.vehicleType.store');
    Route::get('/vehicle-type/edit/{id}', [VehicleTypeController::class, 'edit'])->name('admin.vehicleType.edit');
    Route::post('/vehicle-type/update', [VehicleTypeController::class, 'update'])->name('admin.vehicleType.update');


    // Service Categories Page Routes
    Route::get('/service-categories', [ServiceCategoryController::class, 'index'])->name('admin.serviceCategories');
    Route::get('/service-category/create', [ServiceCategoryController::class, 'create'])->name('admin.serviceCategory.create');
    Route::post('/service-category/store', [ServiceCategoryController::class, 'store'])->name('admin.serviceCategory.store');
    Route::get('/service-category/edit/{id}', [ServiceCategoryController::class, 'edit'])->name('admin.serviceCategory.edit');
    Route::post('/service-category/update', [ServiceCategoryController::class, 'update'])->name('admin.serviceCategory.update');
    Route::get('/service-category/update-status/{id}', [ServiceCategoryController::class, 'updateStatus'])->name('admin.serviceCategory.updateStatus');

    // Settings Page Routes
    Route::prefix('settings')->middleware(['auth'])->group(function () {
        Route::get('/system', [SystemSettingController::class, 'index'])->name('admin.systemSettings');
        Route::post('/system/update', [SystemSettingController::class, 'update'])->name('admin.systemSetting.update');
        // Tax
        Route::get('/tax', [TaxSettingController::class, 'index'])->name('admin.taxSettings');
        Route::get('/tax/create', [TaxSettingController::class, 'create'])->name('admin.taxSetting.create');
        Route::post('/tax/store', [TaxSettingController::class, 'store'])->name('admin.taxSetting.store');
        Route::get('/tax/edit/{id}', [TaxSettingController::class, 'edit'])->name('admin.taxSetting.edit');
        Route::post('/tax/update', [TaxSettingController::class, 'update'])->name('admin.taxSetting.update');
        Route::get('/tax/update-status/{id}', [TaxSettingController::class, 'updateStatus'])->name('admin.taxSetting.updateStatus');
    });


    // End of Admin Routes
});



// Route::get('/admin/helpers', [App\Http\Controllers\AdminController::class, 'helpers'])->name('admin.helpers');

Route::get('/admin/orders', [App\Http\Controllers\AdminController::class, 'orders'])->name('admin.orders');



// Vehicle Types Route

Route::get('/admin/vehicles', [App\Http\Controllers\AdminController::class, 'vehicles'])->name('admin.vehicles');
