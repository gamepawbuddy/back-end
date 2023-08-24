<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

class SubscriptionController extends Controller
{

    use ApiResponseTrait;
    
    /**
     * Update the user's subscription level.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSubscription(Request $request)
    {
        $user = $request->user();
    
        // Validate the request against the rules
        $validationRules = [
            'subscription' => ['required', Rule::in(['basic', 'premium'])],
        ];
        
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return $this->respondBadRequest($validator->errors());
        }

        // Update the user's subscription based on the selected option
        $subscription = $request->input('subscription');
        $subscriptionLevel = ($subscription === 'basic') ? 1 : 2;
        $user->subscription_level = $subscriptionLevel;
        $user->save();
        return $this->respondSuccess('Subscription updated successfully');
    }
}