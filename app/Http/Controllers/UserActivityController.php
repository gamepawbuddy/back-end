<?php

    namespace App\Http\Controllers;


    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;
    use App\Models\User;
    use App\Models\UserActivity;
    
    class UserActivityController extends Controller
    {
        /**
         * Log a user activity.
         *
         * @param $user The user for whom the activity is being logged.
         * @param string $activityType The type of activity being logged.
         * @param array $activityDetails Additional details about the activity (optional).
         * @param string $ipAddress The IP address associated with the activity.
         * @return void
         */
        public function logActivity($user, string $activityType, array $activityDetails = [], string $ipAddress): void
        {
            try {
                // Create a new user activity record
                UserActivity::create([
                    'user_id' => $user->id,
                    'ip_address' => $ipAddress,
                    'activity_type' => $activityType,
                    'activity_details' => json_encode($activityDetails),
                    'performed_by_id' => $user->id,
                    'performed_by_type' => 'User',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                // Log an error message if an exception occurs
                Log::error("Error logging user activity: {$e->getMessage()}", [
                    'exception' => $e
                ]);
            }
        }
    }
    