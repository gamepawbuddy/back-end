<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parksLocation = [
            "Heaton Park" => [53.5222, -2.2734],
            "Whitworth Park" => [53.4593, -2.2481],
            "Platt Fields Park" => [53.4424, -2.2293],
            "Alexandra Park" => [53.4536, -2.2470],
            "Chorlton Water Park" => [53.4304, -2.2820],
            "Moss Side Community Allotments" => [53.4532, -2.2486],
            "Boggart Hole Clough" => [53.5185, -2.2295],
            "Fletcher Moss Botanical Garden" => [53.4092, -2.2292],
            "Marie Louise Gardens" => [53.4214, -2.2418],
            "Peel Park" => [53.4862, -2.2737],
            "Birchfields Park" => [53.4517, -2.2139],
        ];
        

        foreach ($parksLocation as $park => $coordinates) {
            DB::table('parks')->insert([
                'name' => $park,
                'location_id' => 29,
                'latitude' => $coordinates[0],
                'longitude' => $coordinates[1],
                'created_at' => now(), 
                'updated_at' => now()
            ]);
        }
    }
}