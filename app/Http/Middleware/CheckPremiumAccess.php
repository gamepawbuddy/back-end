<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Token;
use Lcobucci\JWT\Configuration;
use App\Models\Users;

class CheckPremiumAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Use Laravel's built-in method to get the Bearer token
        $jwt = $request->bearerToken();
    
        if (!$jwt) {
            return response()->json([
                'success' => false,
                'message' => 'No Bearer token provided.'
            ], 401);  // 401 Unauthorized
        }
    
        // Attach the JWT to the request for further processing in the controller
        $request->jwt = $jwt;
    
        try {
            // Fetch the JWT configuration for parsing the token
            $config = Configuration::forUnsecuredSigner();
            
            // Parse the JWT token to extract its claims
            $jwtToken = $config->parser()->parse($jwt);
            
            // Get the jti (JWT ID) claim which represents a unique identifier for the token
            $jti = $jwtToken->claims()->get('jti');
            
            // Attempt to find the token in the database using its jti
            $token = Token::find($jti);
    
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token not found.'
                ], 401);  // 401 Unauthorized
            }
    
            $userId = $token->user_id;
    
            $user = Users::find($userId);
    
            if (!$user || !$user->isPremium()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User does not have the required permissions.'
                ], 403);  // 403 Forbidden
            }
        } catch (\Exception $e) {
            // Catch any exceptions that may occur during JWT parsing or database queries
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. ' . $e->getMessage()
            ], 500);  // 500 Internal Server Error
        }
    
        return $next($request);
    }
    
    
}