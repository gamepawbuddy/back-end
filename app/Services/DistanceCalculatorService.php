<?php

namespace App\Services;

class DistanceCalculatorService
{
    // Constants representing Earth's radius in different units.
    
    /** 
     * Earth's radius in kilometers.
     * @var float 
     */
    const EARTH_RADIUS = 6371;
    
    /** 
     * Earth's radius in miles.
     * @var float 
     */
    const EARTH_RADIUS_MILES = 3958.8;

    /**
     * Calculate the distance between two lat-long points using the Haversine formula.
     *
     * This formula gives great-circle distances between two points – a distance over the 
     * surface of the sphere. The distances account for the spherical shape of the Earth.
     *
     * @param float $lat1 Latitude of the first point.
     * @param float $long1 Longitude of the first point.
     * @param float $lat2 Latitude of the second point.
     * @param float $long2 Longitude of the second point.
     * @param string $unit Desired unit for the result: 'km' (default) or 'miles'.
     * @return float Distance between the two points in the specified unit.
     */
    public function calculateDistance(float $lat1, float $long1, float $lat2, float $long2, string $unit = 'km'): float
    {
        // Convert latitude and longitude from degrees to radians.
        $lat1 = deg2rad($lat1);
        $long1 = deg2rad($long1);
        $lat2 = deg2rad($lat2);
        $long2 = deg2rad($long2);

        // Calculate deltas between the two latitudes and longitudes.
        $deltaLat = $lat2 - $lat1;
        $deltaLong = $long2 - $long1;

        // Apply the Haversine formula.
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1) * cos($lat2) * sin($deltaLong / 2) * sin($deltaLong / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Determine the distance based on the desired unit (km or miles).
        $distance = $unit === 'miles' ? self::EARTH_RADIUS_MILES * $c : self::EARTH_RADIUS * $c;

        return $distance;
    }

    /**
     * Convert a distance from kilometers to miles.
     *
     * @param float $distanceKm The distance in kilometers.
     * @return float The converted distance in miles.
     */
    public function kmToMiles(float $distanceKm): float
    {
        return $distanceKm * 0.621371;
    }

    /**
     * Classify a park based on its distance from a reference point.
     *
     * Parks within 10 km of the reference point are considered "Nearby". 
     * Parks further than 10 km are considered "Distant".
     *
     * @param float $lat Latitude of the park.
     * @param float $long Longitude of the park.
     * @param float $refLat Latitude of the reference point.
     * @param float $refLong Longitude of the reference point.
     * @return string Classification of the park: 'Nearby' or 'Distant'.
     */
    public function classifyParkBasedOnLocation(float $lat, float $long, float $refLat, float $refLong): string
    {
        // Calculate the distance between the park and the reference point.
        $distance = $this->calculateDistance($lat, $long, $refLat, $refLong);
        
        // Classify the park based on the calculated distance.
        return $distance <= 10 ? 'Nearby' : 'Distant';
    }
}