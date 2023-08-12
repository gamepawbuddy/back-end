<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
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

    /**
     * Authenticate the user and issue an API token.
     *
     * @group Authenticating requests
     * 
     * This endpoint allows users to authenticate and retrieve an API token. You must provide the user's email and password in the request body.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @bodyParam email string required The email address of the user. Example: john@example.com
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
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json(['token' => $token]);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }
}