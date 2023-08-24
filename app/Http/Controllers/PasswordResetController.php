<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\PasswordReset;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Crypt;
use App\Traits\ApiResponseTrait;

class PasswordResetController extends Controller
{

    use ApiResponseTrait;
    /**
     * Send a reset password link via email to the user.
     *
     * This endpoint allows sending a reset password link to the user's email.
     *
     * API docs:
     *
     * @group Password
     * @bodyParam email string required The email address of the user.
     *
     * @response 200 {
     *     "message": "Password reset email sent successfully."
     * }
     * @response 400 {
     *     "message": "User not found with the provided email."
     * }
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetPasswordByEmail(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = Users::where('email', $request->email)->first();

        if (!$user) {
            return $this->respondBadRequest(trans('passwords.user'));
        }

        $token = $this->createPasswordResetToken($request->email);
        
        Mail::to($request->email)->send(new ResetPasswordMail($token));

        return $this->respondSuccess( trans('passwords.sent'));
    }

    private function createPasswordResetToken(string $email): string
    {
        // Create a random token
        $token = Str::random(255); // Assuming you're using Laravel's Str helper

        // Store email, token and created_at in PasswordReset model
        PasswordReset::create([
            'email' => $email,
            'token' => $token,
            'created_at' => now() // Assuming you're using Laravel's now() helper
        ]);

        return $token;
    }
        
    /**
     * Display the password reset form.
     *
     * @param string $token The unique token associated with the password reset request.
     * @return \Illuminate\Http\Response
     */
    public function showResetForm($token)
    {
    
        // Retrieve the password reset record based on the decrypted token.
        $passwordReset = PasswordReset::where('token', $token)->first();
    
    
        if (!$passwordReset) {
            return response()->json(['message' => 'Token does not exist'], 404);
        }
    
        // Retrieve the user associated with the email address from the password reset record.
        $user = Users::where('email', $passwordReset->email)->first();
    
        if (!$user) {
            $this->respondNotFound('User not found for the given token');
        }
    
        // Redirect to the reset form with the user's ID and token attached as parameters.
        return redirect()->to("reset-form?ref={$user->id}&token={$token}");
    }


    /**
     * Reset the user's password.
     *
     * This function handles the password reset process. It first validates the incoming request data,
     * ensuring the new password meets certain criteria. It then checks if the provided reset token
     * exists in the PasswordReset table. If the token is valid, the function deletes the token record,
     * finds the user by their ID, and updates their password.
     *
     * @group Password
     * @header Accept application/json
     * @header Content-Type application/json
     * 
     * @bodyParam password string required The new password. Must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.
     * @bodyParam password_confirmation string required The password confirmation. Must match the password.
     * @bodyParam user_id string required The ID of the user whose password is being reset.
     * @bodyParam token string required The password reset token.
     * 
     * @response 200 {
     *   "message": "Password reset successful!"
     * }
     * @response 400 {
     *   "message": "Invalid or expired token."
     * }
     * @response 404 {
     *   "message": "User not found."
     * }
     * @response 422 {
     *   "errors": {
     *     "password": ["The password format is invalid."]
     *   }
     * }
     *
     * @param Request $request The incoming request object, which should contain the new password, password confirmation, user ID, and reset token.
     * @return JsonResponse Returns a JSON response indicating the result of the password reset attempt.
     */
    public function resetPassword(Request $request)
    {
        try {
            // Validate the incoming request data
            $request->validate([
                'password' => [
                    'required',
                    'string',
                    'min:8',              // Minimum length of 8 characters
                    'regex:/[A-Z]/',      // Requires at least one uppercase letter
                    'regex:/[a-z]/',      // Requires at least one lowercase letter
                    'regex:/[0-9]/',      // Requires at least one number
                    'regex:/[\W]+/',      // Requires at least one special character
                    'confirmed'           // Requires the field 'password_confirmation' to match
                ],
                'password_confirmation' => 'required',
                'user_id' => 'required',
                'token' => 'required'
            ]);

            // Check if the token exists in the PasswordReset table
            $passwordReset = PasswordReset::where('token', $request->token)->first();

            // If the token doesn't exist, return an error response
            if (!$passwordReset) {
              return $this->respondBadRequest('Invalid or expired token');
            }

           // Delete the matching record from the PasswordReset table based on the token
            PasswordReset::where('token', $request->token)->delete();

            // Find the user by user_id
            $user = Users::find($request->user_id);

            // If the user doesn't exist, return an error response
            if (!$user) {
                return $this->respondNotFound('User not found');
            }

            // Update the user's password and save the changes
            $user->password = Hash::make($request->password);
            $user->save();

            // Return a success response
            return $this->respondSuccess('Password reset successful');

        } catch (ValidationException $e) {
            // If validation fails, return the validation errors
            return $this->respondWithValidationErrors($e->errors());
        }
    }
    
}