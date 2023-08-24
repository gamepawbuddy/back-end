<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Dog;
use Exception;

class DogController extends Controller
{
    /**
     * Registers a user's dogs in the database.
     *
     * @param string $userId UUID of the user.
     * @param array $petNameArray Array of dog names.
     * @return bool
     */
    public function registerUserDog(string $userId, array $petNameArray): bool
    {
        // It would be better to validate $userId (as a UUID format) and $petNameArray here

        $dogsData = [];
        foreach ($petNameArray as $petName) {
            $dogsData[] = [
                'name' => $petName,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        try {
            Dog::insert($dogsData);
            return true;
        } catch (Exception $e) {
            Log::error('Error creating dog: ' . $e->getMessage());
            return false;
        }
    }
}