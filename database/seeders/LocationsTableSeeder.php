<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
            "Bath" => [51.3813, -2.3590],
            "Birmingham" => [52.4862, -1.8904],
            "Bradford" => [53.7950, -1.7594],
            "Brighton & Hove" => [50.8225, -0.1372],
            "Bristol" => [51.4545, -2.5879],
            "Cambridge" => [52.2053, 0.1218],
            "Canterbury" => [51.2809, 1.0780],
            "Carlisle" => [54.8924, -2.9326],
            "Chelmsford" => [51.7356, 0.4680],
            "Chester" => [53.1915, -2.8966],
            "Chichester" => [50.8367, -0.7800],
            "Colchester" => [51.8892, 0.9042],
            "Coventry" => [52.4068, -1.5197],
            "Derby" => [52.9228, -1.4769],
            "Doncaster" => [53.5228, -1.1312],
            "Durham" => [54.7753, -1.5849],
            "Ely" => [52.3991, 0.2624],
            "Exeter" => [50.7184, -3.5339],
            "Gloucester" => [51.8642, -2.2382],
            "Hereford" => [52.0567, -2.7159],
            "Kingston-upon-Hull" => [53.7445, -0.3353],
            "Lancaster" => [54.0466, -2.8007],
            "Leeds" => [53.8008, -1.5491],
            "Leicester" => [52.6369, -1.1398],
            "Lichfield" => [52.6832, -1.8261],
            "Lincoln" => [53.2307, -0.5406],
            "Liverpool" => [53.4084, -2.9916],
            "London" => [51.5074, -0.1278],
            "Manchester" => [53.4831, -2.2441],
            "Milton Keynes" => [52.0406, -0.7594],
            "Newcastle-upon-Tyne" => [54.9713, -1.6174],
            "Norwich" => [52.6309, 1.2974],
            "Nottingham" => [52.9548, -1.1581],
            "Oxford" => [51.7520, -1.2577],
            "Peterborough" => [52.5695, -0.2405],
            "Plymouth" => [50.3755, -4.1427],
            "Portsmouth" => [50.8161, -1.0672],
            "Preston" => [53.7609, -2.7044],
            "Ripon" => [54.1365, -1.5214],
            "Salford" => [53.4875, -2.2901],
            "Salisbury" => [51.0688, -1.7945],
            "Sheffield" => [53.3811, -1.4701],
            "Southampton" => [50.9097, -1.4044],
            "Southend-on-Sea" => [51.5450, 0.7075],
            "St Albans" => [51.7519, -0.3343],
            "Stoke on Trent" => [53.0027, -2.1794],
            "Sunderland" => [54.9046, -1.3822],
            "Truro" => [50.2632, -5.0510],
            "Wakefield" => [53.6833, -1.4974],
            "Wells" => [51.2090, -2.6477],
            "Westminster" => [51.4975, -0.1357],
            "Winchester" => [51.0598, -1.3101],
            "Wolverhampton" => [52.5862, -2.1288],
            "Worcester" => [52.1936, -2.2216],
            "York" => [53.9591, -1.0815],
            "Armagh" => [54.3503, -6.6528],
            "Bangor" => [54.6546, -5.6681],
            "Belfast" => [54.5973, -5.9301],
            "Lisburn" => [54.5096, -6.0435],
            "Londonderry" => [55.0068, -7.3183],
            "Newry" => [54.1750, -6.3408],
            "Aberdeen" => [57.1497, -2.0943],
            "Dundee" => [56.4620, -2.9707],
            "Dunfermline" => [56.0708, -3.4525],
            "Edinburgh" => [55.9533, -3.1883],
            "Glasgow" => [55.8642, -4.2518],
            "Inverness" => [57.4778, -4.2247],
            "Perth" => [56.3965, -3.4376],
            "Stirling" => [56.1165, -3.9369],
            "Bangor (Wales)" => [53.2274, -4.1293],
            "Cardiff" => [51.4816, -3.1791],
            "Newport" => [51.5884, -2.9975],
            "St Asaph" => [53.2561, -3.4391],
            "St Davids" => [51.8812, -5.2681],
            "Swansea" => [51.6214, -3.9436],
            "Wrexham" => [53.0466, -2.9913]
        ];
        

        foreach ($locations as $cityName => $coordinates) {
            DB::table('locations')->insert([
                'city_name' => $cityName,
                'latitude' => $coordinates[0],
                'longitude' => $coordinates[1],
            ]);
        }
    }
}