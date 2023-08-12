<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

/**
 * UsersController handles the creation and management of users.
 */

class UsersController extends Controller
{
    
  /**
     * Handle the user creation request.
     * 
     * @param Request $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     * 
     * API docs:
     *
     * Create a new user.
     *
     * This endpoint allows you to create a new user. The user's email and password should be provided in the request body.
     *
     * @bodyParam email string required The email address of the user. Example: john@example.com
     * @bodyParam password string required The password for the user. The password must meet the following criteria: a minimum length of 8 characters, at least one uppercase letter, at least one lowercase letter, at least one number, and at least one special character.
     *
     * @response 201 {
     *  "message": "User created successfully"
     * }
     * @response 422 {
     *  "message": "Validation failed",
     *  "errors": {
     *     "email": ["The email field is required."],
     *     "password": ["The password field is required."]
     *  }
     * }
     * @response 500 {
     *  "message": "User creation failed"
     * }
     */
    public function create(Request $request)
    {
        try {
            // Get the validation rules and validate the request data.
            $validationRules = $this->getValidationRules();
            $request->validate($validationRules);

            // Prepare user data for creation.
            $userData = [
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ];

            // Attempt to create the user.
            if ($this->createUser($userData)) {
                return response()->json(['message' => 'User created successfully'], 201);
            }

            // If user creation fails, return a generic error message.
            return response()->json(['message' => 'User creation failed'], 500);
        } catch (ValidationException $e) {
            // If validation fails, return detailed error messages.
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }
    }

    /**
     * Define the validation rules for creating a user.
     * 
     * Example Password:
     * 
     * 1. Xyz123$A
     * 2. P@ssw0rd
     * 3. Secur3P@ss
     *
     * @return array The validation rules.
     */
    private function getValidationRules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',              // Minimum length of 8 characters
                'regex:/[A-Z]/',      // Requires at least one uppercase letter
                'regex:/[a-z]/',      // Requires at least one lowercase letter
                'regex:/[0-9]/',      // Requires at least one number
                'regex:/[\W]+/',      // Requires at least one special character
            ],
        ];
    }

    /**
     * Create a new user using the provided data.
     *
     * @param array $userData The user data.
     * @return bool True if the user was created successfully, otherwise false.
     */
    private function createUser(array $userData): bool
    {
        try {
            $user = new Users();
            $user->id = (string) Str::uuid();
            $user->email = $userData['email'];
            $user->password_hash = Hash::make($userData['password']);
            $user->save();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}