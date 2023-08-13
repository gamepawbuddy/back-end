<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\FailedLogin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{


    /**
     * Authenticate the user and issue an API token.
     *
     * @group Authenticating requests
     * 
     * This endpoint allows users to authenticate and retrieve an API token. You must provide the user's email and password in the request body.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @bodyParam email string required The email address of the user. Example: bruno@example.com
     * @bodyParam password string required The password of the user.
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @response 200 {
     *  "token": "api_token_here"
     * }
     * 
     * @response 401 {
     *  "error": "Invalid credentials"
     * }
     */
    public function login(Request $request): JsonResponse
    {
        // Validate the incoming request data for email and password.
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

          // Check if the user has exceeded the number of allowed failed attempts.
        if ($this->hasExceededFailedAttempts($credentials['email'])) {
            return response()->json(['error' => 'Too many failed attempts. Please try again later.'], 429);
        }
    
        // Attempt to authenticate the user with the provided credentials.
        if (Auth::attempt($credentials)) {
            // If authentication is successful, retrieve the authenticated user.
            $user = Auth::user();

            // Clear all failed login attempts for the user.
            $this->clearFailedAttempts($user);

            // Revoke any existing tokens for the user.
            $this->revokeAllUserTokens($user);
    
            // Create a new API token for the authenticated user.
            $token = $user->createToken('api-token')->accessToken;
    
            // Return the generated token as a response.
            return response()->json(['token' => $token]);
        }
    
        // If authentication fails, log the failed attempt.
        $this->logFailedAttempt($credentials, $request);
    
        // Return an error response indicating invalid credentials.
        return response()->json(['error' => 'Invalid credentials'], 401);
    }
    

    
    /**
     * Log a failed login attempt for a user.
     *
     * @param  array  $credentials
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function logFailedAttempt(array $credentials, Request $request): void
    {
        $user = Users::where('email', $credentials['email'])->first();

        if ($user) {
            FailedLogin::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
            ]);
        }
    }

    /**
     * Determines if a user has exceeded the allowed number of failed login attempts 
     * within the last 10 minutes.
     *
     * @param string $email The email address of the user to check.
     *
     * @return bool True if the user has made 5 or more failed login attempts in the 
     *              last 10 minutes, otherwise false.
     *
     * @throws \Illuminate\Database\QueryException If there's an issue with the database query.
     *
     * Usage:
     *   if (hasExceededFailedAttempts('user@example.com')) {
     *       // Handle excessive failed attempts (e.g., lock account or show captcha).
     *   }
     */
    private function hasExceededFailedAttempts($email): bool
    {
        // Retrieve the user's ID using the provided email.
        $userId = Users::where('email', $email)->value('id');

        // If the user doesn't exist, return false.
        if (!$userId) {
            return false;
        }

        // Check the number of failed attempts in the last 10 minutes using the user's ID.
        return FailedLogin::where('user_id', $userId)
                        ->where('created_at', '>=', now()->subMinutes(10))
                        ->count() >= 5;
    }

    /**
     * Clear all failed login attempts for the given user.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user The authenticated user object.

     * @return void
     */
    private function clearFailedAttempts($user): void
    {
        // Assuming you have a FailedLogin model and user_id column to identify the user.
        FailedLogin::where('user_id', $user->id)->delete();
    }

    /**
     * Revoke all tokens for the given user.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user The authenticated user object.
     *
     * @return void
     */
    private function revokeAllUserTokens($user): void
    {
        $user->tokens->each(function ($token) {
            $token->delete();
        });
    }

    

    /**
     * Get the authenticated user's tokens.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserTokens()
    {
        $tokens = Auth::user()->tokens;

        return response()->json(['tokens' => $tokens]);
    }

    /**
     * Revoke a specific token for the authenticated user.
     *
     * @param  int  $tokenId
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeToken($tokenId)
    {
        Auth::user()->tokens()->where('id', $tokenId)->delete();

        return response()->json(['message' => 'Token revoked successfully']);
    }

}