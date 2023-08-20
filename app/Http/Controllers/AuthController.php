<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\FailedLogin;
use App\Models\OauthAccessToken;
use Illuminate\Http\Request;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Laravel\Passport\Token;
use Lcobucci\JWT\Configuration;

class AuthController extends Controller
{

    /**
     * Authenticate the user and generate an API token.
     *
     * @group Authentication
     * 
     * This endpoint allows users to authenticate and obtain an API token. The user's email and password must be provided in the request body.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @bodyParam email string required The user's email address. Example: bruno@example.com
     * @bodyParam password string required The user's password.
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
    
        $userActivityController = new UserActivityController();

    
        // Attempt to authenticate the user with the provided credentials.
        if (Auth::attempt($credentials)) {
            // If authentication is successful, retrieve the authenticated user.
            $user = Auth::user();

            // Clear all failed login attempts for the user.
            $this->clearFailedAttempts($user);

            // Revoke any existing tokens for the user and logs the activity.
            $this->revokeAllUserToken($user,$request->ip());
    
            // Create a new API token for the authenticated user.
            $token = $user->createToken('api-token')->accessToken;

            // Log successful login activity.
            $userActivityController->logActivity($user, 'login_success', [], $request->ip());

            // Return the generated token as a response.
            return response()->json(['token' => $token]);
        }

        // Log failed login attempt activity and include IP address.
        $userActivityController->logActivity($user, 'login_failure', [], $request->ip());
    
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
     * Revoke all tokens for the given user and log the revocation activity.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user The authenticated user object.
     * @param string $ip The IP address from which the revocation is being performed.
     * @return void
     */
    private function revokeAllUserToken($user, $ip): void
    {
        $user->tokens->each(function ($token) use ($user, $ip) {
            $token->delete();
            $userActivityController = new UserActivityController();

            $userActivityController->logActivity(
                $user,
                'revoked_existing_token',
                ['message' => "Token '. $token .' was revoked when user '. $user->id. ' logged in."],
                $ip
            );
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
        // Use the JWT token attached by the middleware
        $jwt = $request->jwt;
    
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
                
                $userActivityController = new UserActivityController();
                $userActivityController->logActivity($user, 'logout_success', [], $request->ip());
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