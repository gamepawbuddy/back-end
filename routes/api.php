<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PasswordResetController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Grouping routes under the 'v1' version prefix
Route::prefix('v1')->group(function () {

    // Grouping user-related routes under the 'user' prefix
    Route::prefix('user')->group(function () {
        // Route for creating users
        Route::post('create', [UsersController::class, 'create']);
    });
    
    // Grouping login-related routes under the 'login' prefix
    Route::prefix('login')->group(function () {
        // Authentication route for obtaining API tokens
        Route::post('user', [AuthController::class, 'loginUser']);
    });

    Route::prefix('logout')->group(function () {
        // Authentication route for logging out
        Route::post('user', [AuthController::class, 'logoutUser']);
    });
    
    // Grouping password-related routes under the 'password' prefix
    Route::prefix('password')->group(function () {
        // Route for sending reset password email
        Route::post('reset-email', [PasswordResetController::class, 'sendResetPasswordByEmail']);
        // Route to display the password reset form with a given token
        Route::get('reset-password-form/{token}', [PasswordResetController::class, 'showResetForm']);
        // Route for actually resetting the password
        Route::post('reset-password', [PasswordResetController::class, 'resetPassword']);
    });
    
});