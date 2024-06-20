<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\HelperController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PassportAuthController;


// ---------------- PASSPORT AUTH ROUTES ---------------- //

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);

// ---------------- CLIENT ROUTES START ---------------- //


// Group API Routes with middleware auth:api with Client URLs
Route::group(['middleware' => 'auth:api', 'prefix' => 'client'], function () {
    // Get the authenticated User Data
    Route::get('index', [PassportAuthController::class, 'me']);


    // Update Client Personal Details
    Route::post('personal/update', [ClientController::class, 'personalUpdate']);

    // Logout User
    Route::post('logout', [PassportAuthController::class, 'logout']);

    // Client Routes End
});

// ---------------- CLIENT ROUTES ENDS ---------------- //

// ---------------- HELPER ROUTES START ---------------- //


// Group API Routes with middleware auth:api with Helper URLs
Route::group(['middleware' => 'auth:api', 'prefix' => 'helper'], function () {
    // Get the authenticated User Data
    Route::get('index', [PassportAuthController::class, 'me']);

    // Update Helper Personal Details
    Route::post('perosnal/update', [HelperController::class, 'personalUpdate']);

    // Logout User
    Route::post('logout', [PassportAuthController::class, 'logout']);

    // Helper Routes End
});

// ---------------- HELPER ROUTES ENDS ---------------- //
