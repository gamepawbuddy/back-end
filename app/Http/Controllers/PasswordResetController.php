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

class PasswordResetController extends Controller
{
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
            return response()->json(['message' => trans('passwords.user')], 400);
        }

        $token = $this->createPasswordResetToken($request->email);
        
        Mail::to($request->email)->send(new ResetPasswordMail($token));

        return response()->json(['message' => trans('passwords.sent')]);
    }

    private function createPasswordResetToken(string $email): string
    {
        // Create a random token
        $token = Str::random(100); // Assuming you're using Laravel's Str helper

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
            return response()->json(['message' => 'User not found for the given token'], 404);
        }
    
        // Redirect to the reset form with the user's ID and token attached as parameters.
        return redirect()->to("reset-form?ref={$user->id}&token={$token}");
    }
    
}