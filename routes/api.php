<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SubscriptionController;
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
        
        /**
         * @group Premium
         *
         * Check if a user has a premium subscription.
         *
         * This endpoint checks if the authenticated user has a premium subscription.
         * 
         * @authenticated
         * 
         * @headerParam Authorization string required The JWT token of the authenticated user. Example: "Bearer your_jwt_token_here"
         *
         * @response {
         *   "id": 1,
         *   "name": "John Doe",
         *   "email": "john.doe@example.com",
         *   "subscription": "Premium"
         * }
         */
         Route::middleware(['user-token-auth','premium-access'])->get('ispremium', function () {
            // Return the fake premium user details
            return response()->json([
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'subscription' => 'Premium'
            ]);
        });


        Route::put('update-subscription', [SubscriptionController::class, 'updateSubscription'])->middleware('user-token-auth');
    });
    
    
    // Grouping login-related routes under the 'login' prefix
    Route::prefix('login')->group(function () {
        // Authentication route for obtaining API tokens
        Route::post('user', [AuthController::class, 'loginUser']);
    });

    Route::prefix('logout')->group(function () {
        // Authentication route for logging out
        Route::post('user', [AuthController::class, 'logoutUser'])->middleware('jwt.auth');
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