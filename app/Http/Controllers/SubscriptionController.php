<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    use ApiResponseTrait;

    /**
     * Update the user's subscription level.
     *
     * @group User
     * 
     * @authenticated
     * 
     * This endpoint allows you to update the subscription level of a user.
     * 
     * @headerParam Authorization string required The JWT token of the authenticated user. Example: "Bearer your_jwt_token_here"
     * 
     * @bodyParam subscription string required The desired subscription level. Possible values: basic, premium. Example: basic
     * 
     * @response 200 {
     *     "message": "Subscription updated successfully"
     * }
     * @response 400 {
     *     "message": "Bad request"
     * }
     * @response 422 {
     *     "message": "Validation failed",
     *     "errors": {
     *         "subscription": ["The selected subscription is invalid"]
     *     }
     * }
     * @response 500 {
     *     "message": "Failed to update subscription"
     * }
     * @response 404 {
     *     "message": "User not found"
     * }
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateSubscription(Request $request)
    {
        $user = $request->user;

        // Validate the request against the rules
        $validationRules = [
            'subscription' => ['required', Rule::in(['basic', 'premium'])],
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors());
        }

        // Update the user's subscription based on the selected option
        $subscription = $request->input('subscription');
        $subscriptionLevel = ($subscription === 'basic') ? 1 : 2;
        $user->subscription_id = $subscriptionLevel;
        $user->save();

        return $this->respondSuccess('Subscription updated successfully');
    }


    
}