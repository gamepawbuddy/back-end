<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subscriptions = [
            ['name' => 'basic', 'description' => 'Basic Subscription', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'premium', 'description' => 'Premium Subscription', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('subscriptions')->insert($subscriptions);
    }
}