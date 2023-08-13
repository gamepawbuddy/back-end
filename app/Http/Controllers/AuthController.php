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
    
        // Attempt to authenticate the user with the provided credentials.
        if (Auth::attempt($credentials)) {
            // If authentication is successful, retrieve the authenticated user.
            $user = Auth::user();
    
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
    protected function logFailedAttempt(array $credentials, Request $request): void
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