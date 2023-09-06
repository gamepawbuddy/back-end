<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DistanceCalculatorService;
use App\Services\ClosestCityService;
use App\Models\Park;
use App\Models\Location;

class LocationController extends Controller
{

    use ApiResponseTrait;
    
    protected $distanceCalculator;
    protected $closestCity;

    public function __construct(DistanceCalculatorService $distanceCalculator, ClosestCityService $closestCity)
    {
        $this->distanceCalculator = $distanceCalculator;
        $this->closestCity = $closestCity;
    }

    public function getParksByCity(Request $request)
    {
        // Validate the request.
        $validatedData = $request->validate([
            'city' => 'sometimes|required|string',
            'latitude' => 'sometimes|required_with:longitude|numeric',
            'longitude' => 'sometimes|required_with:latitude|numeric',
        ]);;

        // Check if the request has a city.
        if ($request->filled('city')) {
            return $this->handleCitySearch($request->input('city'));
        }

        // Check if the request has latitude and longitude.
        if ($request->filled('latitude') && $request->filled('longitude')) {
            return $this->handleLocationSearch($request->input('latitude'), $request->input('longitude'));
        }

        // Fallback error response in case the above conditions aren't met.
        return $this->respondBadRequest('Invalid data provided.');
    }


    private function handleCitySearch(string $cityName)
    {
        $city = Location::where('name', $cityName)->first();
        // return $this->respondSuccess($this->getParksWithDistances($city->latitude, $city->longitude, $city->id));
    }

    private function handleLocationSearch(float $latitude, float $longitude)
    {
        $closestCity = $this->closestCity->getClosestCity($latitude, $longitude);
        // return $this->respondSuccess($this->getParksWithDistances($latitude, $longitude, $closestCity->id));
    }

    private function getParksWithDistances(float $latitude, float $longitude, int $locationId): array
    {
        $parksInTheCity = Park::where('location_id', $locationId)->get();

        $parksWithDistances = [];
        foreach ($parksInTheCity as $park) {
            $distance = $this->distanceCalculator->calculateDistance($latitude, $longitude, $park->latitude, $park->longitude);
            $parksWithDistances[] = [
                'park_id' => $park->id,
                'park_name' => $park->name,
                'latitude' => $park->latitude,
                'longitude' => $park->longitude,
                'distance_to_user' => $distance,
            ];
        }

        return $parksWithDistances;
    }
}