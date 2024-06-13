<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PassportAuthController;


// ---------------- CLIENT ROUTES ---------------- //

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);

// Group API Routes with middleware auth:api with Client URLs
Route::group(['middleware' => 'auth:api', 'prefix' => 'client'], function () {
    // Get the authenticated User Data
    Route::get('index', [PassportAuthController::class, 'me']);

    // Logout User
    Route::post('logout', [PassportAuthController::class, 'logout']);

    // Client Routes End
});


// ---------------- HELPER ROUTES ---------------- //
