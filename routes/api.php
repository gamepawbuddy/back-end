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

Route::prefix('v1')->group(function () {
    
    // User creation route
    Route::post('create-user', [UsersController::class, 'create']);

    // Authentication route for obtaining API tokens
    Route::post('login', [AuthController::class, 'login']);

     // Password reset route
     Route::post('user-password-reset-email', [PasswordResetController::class, 'sendResetPasswordByEmail']);

     // Route to display the password reset form with a given token
     Route::get('show-reset-password-form/{token}', [PasswordResetController::class, 'showResetForm']);

});



// Route::middleware('auth:sanctum')->group(function () {

//     // Route to get the authenticated user's tokens
//     Route::get('tokens', [AuthController::class, 'getUserTokens']);

//     // Route to revoke a specific token
//     Route::delete('tokens/{token_id}', [AuthController::class, 'revokeToken']);
// });