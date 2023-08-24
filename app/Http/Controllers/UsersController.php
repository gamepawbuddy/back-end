<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Http\Controllers\DogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\JsonResponse;
use App\Mail\NewUserWelcomeMail;
use App\Traits\ApiResponseTrait;

/**
 * UsersController handles the creation and management of users.
 */

class UsersController extends Controller
{

    use ApiResponseTrait;

    /**
     * Store the ID of the last created user.
     */
    private $lastUserId;
    
  /**
     * Handle the user creation request.
     * 
     * @param Request $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     * 
     * API docs:
     *
     * @group User
     *
     * @authenticated
     * 
     * This endpoint allows you to create a new user. The user's email and password should be provided in the request body.
     *
     * @bodyParam email string required The email address of the user. Example: john@example.com
     * @bodyParam password string required The password for the user. The password must meet the following criteria: a minimum length of 8 characters, at least one uppercase letter, at least one lowercase letter, at least one number, and at least one special character.
     * @bodyParam dogNameArray array us required for the user's dog name. At least, one pet name needs to be provided in the array.  
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
            $request->validate($this->getValidationRules());
    
            // Prepare user data for creation.
            $userData = [
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ];

            $dogNameArray = $request->input('petNameArray');
    
            
            if($this->createUser($userData) && DogController::registerUserDog($this->$lastUserId, $dogNameArray ) ) {
                Mail::to($userData['email'])->send(new NewUserWelcomeMail());
                return $this->respondCreated('User created successfully');
            }
    
            return $this->respondServerError('User or aninal creation failed.');
    
        } catch (ValidationException $e) {
            return $this->respondWithValidationErrors($e->errors());
        } catch (Exception $e) {  // For any unexpected errors
            return $this->respondServerError('An unexpected error occurred');
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
            'petNameArray' => 'required|array|min:1',
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
            $user->password = Hash::make($userData['password']);
            $user->save();

            // Store the user ID in the class property
            $this->lastUserId = $user->id;
            
            return true;
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return false;
        }
    }

}