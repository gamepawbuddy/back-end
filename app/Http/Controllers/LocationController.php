<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function findClosestPark(Request $request)
    {
        // Validate the request. One of 'city' or 'latitude' and 'longitude' should be provided.
        $validatedData = $request->validate([
            'city' => 'sometimes|required|string',
            'latitude' => 'sometimes|required_with:longitude|numeric',
            'longitude' => 'sometimes|required_with:latitude|numeric',
        ]);

        // Check if the request has a city
        if ($request->filled('city')) {
            $city = $request->input('city');
            // Store the city in your database or do any other required action.
            // For demonstration purposes, I'm just returning a response.
            return response()->json(['message' => "City '$city' has been posted successfully."]);
        }

        // Check if the request has latitude and longitude
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            // Store the latitude and longitude in your database or do any other required action.
            // For demonstration purposes, I'm just returning a response.
            return response()->json(['message' => "Latitude: $latitude, Longitude: $longitude has been posted successfully."]);
        }

        // Fallback error response in case the above conditions aren't met.
        return response()->json(['error' => 'Invalid data provided.'], 400);
    }
}