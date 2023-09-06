<?php

namespace App\Services;

use App\Models\Location;
use App\Services\DistanceCalculatorService;

class ClosestCityService
{
    protected $distanceCalculator;

    /**
     * Construct the ClosestCityService with the necessary dependencies.
     *
     * @param DistanceCalculatorService $distanceCalculator A service to calculate distances between points.
     */
    public function __construct(DistanceCalculatorService $distanceCalculator)
    {
        $this->distanceCalculator = $distanceCalculator;
    }

    /**
     * Get the closest city to a given latitude and longitude.
     * 
     * This method fetches cities from the database using the Location model,
     * then iterates over each city to calculate its distance to the given point.
     * The closest city is then returned.
     *
     * @param float $lat Latitude of the reference point.
     * @param float $long Longitude of the reference point.
     * @return Location Information of the closest city.
     */
    public function getClosestCity(float $lat, float $long): Location
    {
        $closestCity = null;
        $shortestDistance = INF;

        // Fetch cities from the database using the Location model.
        $cities = Location::all();

        foreach ($cities as $city) {
            // Calculate distance between given point and current city.
            $distance = $this->distanceCalculator->calculateDistance($lat, $long, $city->latitude, $city->longitude);

            // If the current city's distance is shorter than the previous shortest distance,
            // update the closest city and shortest distance values.
            if ($distance < $shortestDistance) {
                $shortestDistance = $distance;
                $closestCity = $city;
            }
        }

        // Return the closest city.
        return $closestCity;
    }
}