<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Configuration;
use App\Models\Users;
use App\Traits\ApiResponseTrait;
use Laravel\Passport\Token;

class UserTokenAuthentication
{

    use ApiResponseTrait;
    
    /**
     * Handles incoming HTTP requests by verifying and parsing JWT tokens.
     * 
     * This middleware function is designed to:
     * 1. Extract a Bearer JWT token from the incoming request.
     * 2. Validate the token against records in the database.
     * 3. Extract relevant user data associated with the token and attach it to the request.
     * 
     * This is particularly useful in scenarios where you want to protect certain routes or endpoints,
     * ensuring only authorized users with a valid JWT token can access them.
     * 
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @param \Closure $next The next middleware or controller to pass the request to.
     * 
     * @return \Illuminate\Http\Response Returns a response based on the validity of the token.
     *      - If no token is provided, it will return an unauthorized response.
     *      - If the token is invalid, expired, or not associated with a user, it will return a forbidden response.
     *      - If there's a server error or exception, it will return a server error response.
     *      - If the token is valid and associated with a user, it continues to the next middleware or controller.
     * 
     * @throws \Exception Possible exceptions during JWT parsing or database queries.
     */
    public function handle($request, Closure $next)
    {
        // Use Laravel's built-in method to get the Bearer token from the request's headers
        $jwt = $request->bearerToken();
        
        // Check if a Bearer token is present in the request
        if (!$jwt) {
            // Respond with an unauthorized error message if no token is provided
            return $this->respondUnauthorized('No Bearer token provided.');
        }
        
        // Attach the extracted JWT to the request object for later use in the controller
        $request->jwt = $jwt;
        
        try {
            // Fetch the JWT configuration for parsing the token. This is likely related to a JWT library.
            // It sets up the configuration for parsing without requiring cryptographic signatures.
            $config = Configuration::forUnsecuredSigner();
        
            // Parse the JWT token to extract its claims using the configured parser
            $jwtToken = $config->parser()->parse($jwt);
        
            // Get the jti (JWT ID) claim from the parsed token, representing a unique identifier for the token
            $jti = $jwtToken->claims()->get('jti');
        
            // Attempt to find the token in the database using its jti
            $token = Token::find($jti);
        
            // If no token is found in the database, respond with a forbidden error message
            if (!$token) {
                return $this->respondForbidden('The token provided is invalid or expired.');
            }
        
            // Extract the user ID associated with the found token
            $userId = $token->user_id;
        
            // Find the user in the Users table based on the extracted user ID
            $user = Users::find($userId);
        
            // If no user is found with the extracted user ID, respond with a forbidden error message
            if (!$user) {
                return $this->respondForbidden('No user associated with the provided token.');
            }
    
            // Merge the found user object into the request's data, making it available in the controller
            $request->merge(['user' => $user]);
        } catch (\Exception $e) {
            // Catch any exceptions that may occur during JWT parsing or database queries,
            // and respond with a server error message containing the exception details
            return $this->respondServerError('An error occurred: ' . $e->getMessage());
        }
        
        // If everything is successful, continue processing the request by passing it to the next middleware or controller
        return $next($request);
    }
    
}