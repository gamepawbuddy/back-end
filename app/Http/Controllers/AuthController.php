<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\FailedLogin;
use App\Models\OauthAccessToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Laravel\Passport\Token;
use Lcobucci\JWT\Configuration;

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
    public function loginUser(Request $request): JsonResponse
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
     * Handle user logout by revoking the authentication token.
     *
     * @authenticated
     * 
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @return \Illuminate\Http\JsonResponse The JSON response indicating the logout status.
     *
     * @response 200 {
     *     "message": "Logged out successfully"
     * }
     * @response 401 {
     *     "error": "Unauthorized"
     * }
     */
    public function logoutUser(Request $request)
    {
        // Check if the Authorization header is present in the request
        if (!$request->hasHeader('Authorization')) {
            // Respond with an Unauthorized error if the header is missing
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        // Extract the JWT token from the Authorization header by removing the "Bearer" prefix
        $authorizationHeader = $request->header('Authorization');
        $jwt = str_replace('Bearer ', '', $authorizationHeader);
    
        try {
            // Fetch the JWT configuration for parsing the token
            $config = Configuration::forUnsecuredSigner();
    
            // Parse the JWT token to extract its claims
            $jwtToken = $config->parser()->parse($jwt);
    
            // Get the jti (JWT ID) claim which represents a unique identifier for the token
            $jti = $jwtToken->claims()->get('jti');
    
            // Attempt to find the token in the database using its jti
            $token = Token::find($jti);
            if ($token) {
                // Mark the token as revoked in the database
                $token->revoked = true;
                $token->save();
    
                // Respond with a success message
                return response()->json(['message' => 'Logged out successfully']);
            } else {
                // Respond with an error if the token is not found in the database
                return response()->json(['error' => 'Token not found'], 404);
            }
    
        } catch (\Exception $e) {
            // Handle any exceptions that occur during token parsing and respond with an error
            return response()->json(['error' => 'Invalid token'], 400);
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