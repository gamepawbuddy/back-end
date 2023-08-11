<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;

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

    // // Authentication route for obtaining API tokens
    // Route::post('login', [AuthController::class, 'login']);
});



// Route::middleware('auth:sanctum')->group(function () {

//     // Route to get the authenticated user's tokens
//     Route::get('tokens', [AuthController::class, 'getUserTokens']);

//     // Route to revoke a specific token
//     Route::delete('tokens/{token_id}', [AuthController::class, 'revokeToken']);
// });