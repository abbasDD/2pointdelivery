<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\HelperController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;



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
    Route::get('/helpers/create', [HelperController::class, 'create'])->name('admin.helper.create');
    Route::post('/helpers/store', [HelperController::class, 'store'])->name('admin.helper.store');
    Route::get('/helpers/edit/{id}', [HelperController::class, 'edit'])->name('admin.helper.edit');
    Route::post('/helpers/update', [HelperController::class, 'update'])->name('admin.helper.update');
});



// Route::get('/admin/helpers', [App\Http\Controllers\AdminController::class, 'helpers'])->name('admin.helpers');

Route::get('/admin/orders', [App\Http\Controllers\AdminController::class, 'orders'])->name('admin.orders');

Route::get('/admin/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('admin.settings');

// Services Route

Route::get('/admin/services', [App\Http\Controllers\AdminController::class, 'services'])->name('admin.services');

// Vehicle Types Route

Route::get('/admin/vehicles', [App\Http\Controllers\AdminController::class, 'vehicles'])->name('admin.vehicles');
