<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User; // Corrected the namespace for the User model
use App\Traits\ApiResponseTrait;

/**
 * Middleware to check if the user has premium access.
 */
class CheckPremiumAccess
{
    use ApiResponseTrait;
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the authenticated user from the request
        $user = $request->user;
        
        // Check if the user has premium access
        if (!$user->isPremium()) { 
            // If not, respond with a forbidden error message
            return $this->respondForbidden('Access denied. Only subscribers allowed.');
        }
    
        // If the user has premium access, continue with the request
        return $next($request);
    }
}