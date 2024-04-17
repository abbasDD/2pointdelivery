<?php

use Illuminate\Support\Facades\Route;


//Admin Routes

//Admin Auth Routes

Route::get('/admin/login', [App\Http\Controllers\Auth\LoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\Auth\LoginController::class, 'postAdminLoginForm'])->name('admin.login');

Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');

//Sub Admins Page Routes
Route::get('/admin/subadmins', [App\Http\Controllers\AdminController::class, 'subadmins'])->name('admin.subadmins');
Route::get('/admin/subadmins/create', [App\Http\Controllers\AdminController::class, 'createSubadmin'])->name('admin.subadmin.create');
Route::post('/admin/subadmins/store', [App\Http\Controllers\AdminController::class, 'storeSubadmin'])->name('admin.subadmin.store');
Route::get('/admin/subadmins/edit/{id}', [App\Http\Controllers\AdminController::class, 'editSubadmin'])->name('admin.subadmin.edit');
Route::post('/admin/subadmins/update', [App\Http\Controllers\AdminController::class, 'updateSubadmin'])->name('admin.subadmin.update');

//Clients Page Routes
Route::get('/admin/clients', [App\Http\Controllers\AdminController::class, 'clients'])->name('admin.clients');
Route::get('/admin/clients/create', [App\Http\Controllers\AdminController::class, 'createClient'])->name('admin.client.create');
Route::post('/admin/clients/store', [App\Http\Controllers\AdminController::class, 'storeClient'])->name('admin.client.store');
Route::get('/admin/clients/edit/{id}', [App\Http\Controllers\AdminController::class, 'editClient'])->name('admin.client.edit');
Route::post('/admin/clients/update', [App\Http\Controllers\AdminController::class, 'updateClient'])->name('admin.client.update');

Route::get('/admin/helpers', [App\Http\Controllers\AdminController::class, 'helpers'])->name('admin.helpers');

Route::get('/admin/orders', [App\Http\Controllers\AdminController::class, 'orders'])->name('admin.orders');

Route::get('/admin/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('admin.settings');

// Services Route

Route::get('/admin/services', [App\Http\Controllers\AdminController::class, 'services'])->name('admin.services');

// Vehicle Types Route

Route::get('/admin/vehicles', [App\Http\Controllers\AdminController::class, 'vehicles'])->name('admin.vehicles');
